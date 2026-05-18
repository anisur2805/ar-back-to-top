# Changelog

All notable changes to AR Back To Top are documented here.

## 3.1.1

**Bug Fix**
- Fix admin notices from other plugins (e.g. Elementor) injecting inside the plugin header and breaking layout

## 3.0.3

**Compatibility Fix**
- Lower minimum WordPress requirement from 6.8 to 4.8 — restores compatibility with the vast majority of WordPress sites
- Add automatic upgrade routine that populates missing option defaults for users upgrading from v2.x, preventing broken buttons after update
- Fix stable tag to match 3.0.3

## 3.0.2

**Bug Fixes**
- Fix stable tag for WordPress.org plugin directory
- All fixes from 3.0.1 included

## 3.0.1

**Bug Fixes**
- Fix color and breakpoint fields not saving — add fallback defaults to all render fields
- Fix icon hover color inline default mismatch (#fff → #000)
- Fix icon picker not updating the live sidebar preview immediately
- Reduce default button offsets from 100px to 30px for better out-of-the-box positioning
- Add autocomplete="off" to color picker inputs
- Update screenshots and expand FAQ section

## 3.0.0

**Major Release — Complete Overhaul**

### Accessibility
- Use semantic `<button>` element instead of `<div>` for the back-to-top button
- Add `aria-label`, screen reader text, and `aria-hidden` on decorative elements
- Add `:focus-visible` outline for keyboard navigation

### Performance
- Remove jQuery dependency — rewrite frontend JS in vanilla JavaScript
- Add `requestAnimationFrame` throttling on scroll events
- Use passive event listeners for scroll/resize
- Conditional asset loading — skip CSS/JS on pages where button is hidden

### New Features
- Page/post display filter — show or hide button on specific pages/posts (Select2 search)
- Auto-hide timer — button fades out after configurable seconds of inactivity
- Button tooltip text and z-index control
- Multiple scroll easing effects (linear, ease-in, ease-out, ease-in-out)
- Button shape presets — circle, square, rounded square, or custom
- Center bottom position option
- Hide on desktop toggle with breakpoint
- Custom icon upload via WordPress media library (PNG, JPG, GIF, SVG)
- Separate mobile positioning controls (bottom and side offsets)
- Show button in WP admin area
- Font Awesome version control — choose FA 6, FA 5, or skip if already loaded
- Auto-detect existing Font Awesome enqueue to avoid duplicates
- Reset to defaults button with confirmation

### UI Redesign
- Modern tabbed admin settings (General, Appearance, Colors, Position, Scroll, Visibility, Advanced)
- Live preview panel with browser chrome mockup
- Select2 searchable multi-select for page/post filter
- Scroll progress forces circle shape with locked dropdown and notice

### Upgrades
- Upgrade Font Awesome from 4.7 to 6.5.1 with automatic icon migration
- SVG upload support for admin users
- PHP 8.2+ compatible — no dynamic property deprecation warnings

### Bug Fixes
- Fix dependent fields visibility (auto-hide, display pages, mobile offsets)
- Fix button shape not applying on frontend
- Fix scroll progress overriding shape selection
- Add `!important` with high-specificity selectors for theme compatibility

## 2.11.4

- Added Website Status Page

## 2.11.3

- Added Progress Bar Options
- Added Button Image Position options
- Improve Code Structure

## 2.11.2

- Added Load JS Async/Defer Option for Performance
- Added Custom CSS
- Added Hide Button On Mobile & Tablet
- Added Button Style
- Improve Code Structure
- Fixed previous error

## 2.10.0

- Added Missing Readme

## 2.0.9

- Added Single Post View Page Word(s), Character(s), View(s) Count
- Added Meta Info View Position Like: Top/Bottom/Both of Content
- Code Structure Improve

## 2.0.4

- Update WordPress and PHP compatible version
- Improve code structure and more

## 2.0.2

- Initial release on WordPress.org
