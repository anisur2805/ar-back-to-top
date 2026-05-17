# AR Back To Top

![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/ar-back-to-top?label=Version)
![WordPress Plugin Active Installs](https://img.shields.io/wordpress/plugin/installs/ar-back-to-top?label=Active+Installs)
![WordPress Plugin Rating](https://img.shields.io/wordpress/plugin/rating/ar-back-to-top?label=Rating)
![WordPress Tested Up To](https://img.shields.io/wordpress/plugin/tested/ar-back-to-top?label=WordPress)
![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-8892BF)
![License](https://img.shields.io/github/license/anisur2805/ar-back-to-top)

A lightweight, fully customizable **scroll to top button** for WordPress with smooth animation, scroll progress indicator, and mobile-friendly positioning. Zero jQuery — pure vanilla JavaScript.

> [Download from WordPress.org](https://wordpress.org/plugins/ar-back-to-top/) | [Live Demo](https://wordpress.org/plugins/ar-back-to-top/)

<!-- TODO: Add a GIF demo here — record the button in action and replace this line -->
<!-- ![AR Back To Top Demo](assets/demo.gif) -->

## Features

- **19 built-in SVG icons** — no external CDN, no Font Awesome dependency on frontend
- **Custom icon upload** — PNG, JPG, GIF, or SVG from your media library
- **Scroll progress indicator** — circular SVG ring showing scroll position
- **Auto-hide** — button fades out after configurable seconds of inactivity
- **Multiple easing effects** — linear, ease-in, ease-out, ease-in-out
- **Button shapes** — circle, square, rounded square, or custom border-radius
- **Full color control** — background, text, border with separate hover states
- **Device-specific positioning** — independent offsets for mobile
- **Page/post visibility** — show or hide on specific pages with searchable dropdown
- **Device visibility** — hide on desktop, tablet, or mobile with custom breakpoints
- **Custom CSS** — advanced styling with CSS hooks
- **Accessible** — semantic `<button>`, ARIA labels, keyboard navigation
- **Zero jQuery** — vanilla JS with `requestAnimationFrame` throttling
- **Conditional loading** — assets only load where the button is visible
- **Admin area button** — optional back-to-top in WordPress dashboard

## Installation

### WordPress Plugin Directory

1. Go to **Plugins > Add New** in your WordPress admin
2. Search for **AR Back To Top**
3. Click **Install Now** then **Activate**

### Manual Upload

1. Download the latest `.zip` from [WordPress.org](https://wordpress.org/plugins/ar-back-to-top/)
2. Go to **Plugins > Add New > Upload Plugin**
3. Upload the zip and activate

## Development

```bash
# Clone the repository
git clone https://github.com/anisur2805/ar-back-to-top.git

# The plugin is ready to use — activate it in WordPress
```

### File Structure

```
ar-back-to-top/
├── ar-back-to-top.php          # Main plugin file
├── inc/
│   ├── class-ar-assets.php     # Asset enqueue logic
│   ├── class-ar-frontend.php   # Frontend rendering
│   ├── class-ar-settings-menu.php # Single post settings
│   ├── class-ar-status.php     # Server status page
│   ├── class-ar-svg-icons.php  # Inline SVG icon registry
│   ├── class-ar-svg-sanitizer.php # SVG upload sanitizer
│   └── dynamic-style.css.php   # Dynamic CSS output
├── assets/
│   ├── css/                    # Admin and frontend styles
│   ├── js/                     # Admin and frontend scripts
│   ├── images/                 # Bundled button images
│   ├── minicolors/             # Color picker library
│   └── select2/                # Searchable dropdown library
└── readme.txt                  # WordPress.org readme
```

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## Changelog

See [readme.txt](readme.txt) or the [WordPress.org changelog](https://wordpress.org/plugins/ar-back-to-top/#developers) for full version history.

## License

GPLv2 or later. See [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html) for details.

## Author

**Anisur Rahman** — [WordPress.org Profile](https://profiles.wordpress.org/anisur8294/) | [GitHub](https://github.com/anisur2805)
