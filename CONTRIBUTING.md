# Contributing to AR Back To Top

Thanks for your interest in contributing! Here's how you can help.

## Reporting Bugs

1. Check [existing issues](https://github.com/anisur2805/ar-back-to-top/issues) to avoid duplicates
2. Open a new issue with:
   - WordPress version
   - PHP version
   - Plugin version
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots if applicable

## Suggesting Features

Open an issue with the **Feature Request** label. Describe:
- The problem you're trying to solve
- Your proposed solution
- Any alternatives you've considered

## Submitting Code

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Test on WordPress 4.8+ and PHP 7.4+
5. Commit with a clear message
6. Push to your fork
7. Open a Pull Request

### Code Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use `snake_case` for PHP functions and variables
- Use `camelCase` for JavaScript functions and variables
- Prefix all functions and classes with `arbtt_` or `AR_`
- Escape all output with `esc_html()`, `esc_attr()`, `esc_url()`
- Sanitize all input with `sanitize_text_field()`, `intval()`, etc.
- Use WordPress nonces for all form submissions

### Testing

- Test with WordPress 4.8 through the latest version
- Test with PHP 7.4 and 8.2+
- Test with popular themes (Twenty Twenty-Four, Astra, etc.)
- Test with popular page builders (Elementor, Divi)
- Verify no JavaScript errors in browser console
- Verify no PHP errors/warnings with `WP_DEBUG` enabled

## Translations

Help translate AR Back To Top on [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/ar-back-to-top/).

## Questions?

Email anisur2805@gmail.com or open a [GitHub Discussion](https://github.com/anisur2805/ar-back-to-top/discussions).
