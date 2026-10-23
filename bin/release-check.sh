#!/usr/bin/env bash
#
# AR Back To Top — release gate.
#
# Asserts the invariants whose violation caused the 3.0.x–3.1.x breakage
# streak (see upgrade audit): version parity, resolvable callbacks, JS/localization
# key parity, no hardcoded selected= attribute, and clean lint/phpcs/tests.
#
# Usage: bash bin/release-check.sh
# Exit 0 = safe to tag. Any failure blocks the release.

set -u -o pipefail

cd "$(dirname "$0")/.."

FAILURES=0

fail() {
	echo "FAIL: $1"
	FAILURES=$(( FAILURES + 1 ))
}

ok() {
	echo "ok:   $1"
}

# ---------------------------------------------------------------- versions ---
HEADER=$(sed -n 's/^[[:space:]]*\* Version: \(.*\)$/\1/p' ar-back-to-top.php | head -1)
STABLE=$(sed -n 's/^Stable tag: \(.*\)$/\1/p' readme.txt | head -1)
CONST=$(grep -oE "define\( 'ARBTTOP_VERSION', *'[^']+' \)" ar-back-to-top.php | head -1)

if [ -z "$HEADER" ] || [ "$HEADER" != "$STABLE" ]; then
	fail "header Version ($HEADER) != readme Stable tag ($STABLE)"
else
	ok "header Version == Stable tag == $HEADER"
fi

# Constant is derived from the header via get_file_data(); only the
# fallback literal may exist, and it must not be a stale hand-written one.
if [ -n "$CONST" ]; then
	fail "hand-edited ARBTTOP_VERSION define found ($CONST) — version must come from the plugin header"
else
	ok "no hand-edited ARBTTOP_VERSION define (derived from header)"
fi

# ---------------------------------------------------------- changelog entry ---
if ! grep -q "= $HEADER =" readme.txt; then
	fail "no changelog entry '= $HEADER =' in readme.txt"
else
	ok "changelog entry exists for $HEADER"
fi

# ------------------------------------------------------- resolvable callbacks ---
# Every add_action/add_filter/add_shortcode callback must resolve to a real
# method on the class (the 3.1.0 dead-shortcode failure mode).
for CB in $(grep -oE "add_(action|filter|shortcode)\( *'[a-z_0-9-]+', *array\( *\\\$this, *'[a-zA-Z_0-9]+' *\)" ar-back-to-top.php inc/*.php \
		| grep -oE "'[a-zA-Z_0-9]+' *\)$" | tr -d "' )"); do
	if ! grep -qE "function $CB\b" ar-back-to-top.php inc/*.php; then
		fail "callback '$CB' has no matching function declaration"
	else
		ok "callback $CB resolves"
	fi
done

# ---------------------------------------------------- JS/localization key parity ---
# Every arbtt_obj.<key> read in the shipped frontend JS must have a matching
# key in the wp_localize_script() payload (the 3.1.0 wrong-key failure mode).
for KEY in $(grep -oE 'obj\.[a-z_]+' assets/js/arbtt-fe.js | sed 's/^obj\.//' | sort -u); do
	if ! grep -q "'$KEY'" inc/class-ar-assets.php ar-back-to-top.php; then
		fail "JS reads key '$KEY' that is not localized"
	else
		ok "JS key $KEY localized"
	fi
done

# -------------------------------------------------- hard-GUARDED settings markup ---
# The 3.0.1–3.1.2 dropdown corruption shipped because selected="selected" was
# hardcoded outside selected()/checked(). Nothing outside them may hardcode it.
if grep -nE 'selected="selected"|selected=.selected.' ar-back-to-top.php inc/*.php | grep -vE 'selected\(|checked\(' >/dev/null; then
	fail "hardcoded selected= attribute found outside selected()/checked()"
else
	ok "no hardcoded selected= markup"
fi

# ------------------------------------------------------------------- linting ---
if command -v php >/dev/null 2>&1; then
	if php -l ar-back-to-top.php >/dev/null 2>&1 && for F in inc/*.php; do php -l "$F" >/dev/null 2>&1; done; then
		ok "php -l clean on all PHP files"
	else
		fail "php -l reported a syntax error"
	fi
else
	fail "php not found — cannot lint"
fi

if command -v phpcs >/dev/null 2>&1; then
	# Baseline: pre-existing errors on master at the time this gate was added.
	# The gate blocks on NEW errors, not on the historical backlog. Baseline
	# count is asserted exactly: if it drops, update it; if it rises, do not tag.
	BASELINE_FILE=bin/.phpcs-baseline-count
	BASELINE=58
	if ! BASELINE=$(cat "$BASELINE_FILE" 2>/dev/null); then BASELINE=58; fi
	case "$BASELINE" in
		''|*[!0-9]*) BASELINE=58 ;;
	esac
	PHPCS_COUNT=$(phpcs --standard=WordPress --extensions=php --report=json ar-back-to-top.php inc/ 2>/dev/null \
		| php -r 'echo (int) ( json_decode( stream_get_contents( STDIN ), true )["totals"]["errors"] ?? -1 );')
	if [ "$PHPCS_COUNT" -ge 0 ] && [ "$PHPCS_COUNT" -le "$BASELINE" ]; then
		ok "phpcs WordPress: $PHPCS_COUNT errors (baseline $BASELINE — no new errors)"
		if [ "$PHPCS_COUNT" -lt "$BASELINE" ]; then
			echo "note: phpcs errors dropped below baseline — update bin/.phpcs-baseline-count to $PHPCS_COUNT"
		fi
	else
		fail "phpcs: could not count errors (got '$PHPCS_COUNT') or exceeds baseline $BASELINE — do not tag"
	fi
else
	fail "phpcs not found — cannot run coding standards check"
fi

if command -v pnpm >/dev/null 2>&1; then
	JEST_OUT=$(pnpm test 2>&1)
	if [ $? -eq 0 ]; then
		ok "jest tests pass"
	else
		fail "jest tests failed:"
		echo "$JEST_OUT" | tail -25
	fi
else
	fail "pnpm not found — cannot run jest"
fi

echo
if [ "$FAILURES" -gt 0 ]; then
	echo "release-check: $FAILURES failure(s) — DO NOT TAG."
	exit 1
fi
echo "release-check: all green — safe to tag $STABLE."
exit 0
