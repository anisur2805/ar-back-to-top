=== AR Back To Top ===
Contributors: anisur8294
Tags: back to top, scroll to top, scroll top, scroll up, smooth top button
Requires at least: 4.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 3.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AR Back To Top is a standard WordPress plugin for back to top.

== Description ==

AR Back To Top plugin will help them who don't wants to write code. For use this plugin simply download or add new plugin from WordPress plugin directory.

== AR Back To Top Features ==

- Displays a **Back to Top** button when the user scrolls down the page.
- Smooth scroll animation with **multiple easing effects** (linear, ease-in, ease-out, ease-in-out).
- Fully customizable — no coding required.
- **Modern tabbed settings UI** with live preview panel.
- Choose from **19 Font Awesome 6 icons** via an interactive icon picker.
- **Custom icon upload** — upload PNG, JPG, GIF, or SVG from your media library.
- **Button shape presets** — circle, square, rounded square, or custom border-radius.
- Supports **custom button size**, **icon size**, and **padding**.
- Customize **background**, **text**, **border colors** with separate **hover states**.
- Set the **button position** — left, right, or center bottom.
- **Separate mobile positioning** — independent bottom/side offsets for mobile.
- Define **scroll offset** — control when the button appears.
- Adjustable **scroll duration** for a smoother UX.
- **Auto-hide** button after configurable seconds of inactivity.
- **Scroll progress indicator** — circular SVG progress ring around the button.
- **Page/post display filter** — show or hide on specific pages/posts with Select2 search.
- **Device visibility** — hide on desktop, tablet, or mobile with custom breakpoints.
- **Show in WP admin area** — enable the button in the WordPress dashboard.
- Enable or disable the button with a single click.
- **Font Awesome version control** — choose FA 6, FA 5, or skip loading if already enqueued.
- Supports **custom CSS** for advanced styling with CSS hook hints.
- **Tooltip text** and **z-index control**.
- **Reset to defaults** with one click.
- Option to load JavaScript **asynchronously** for performance.
- **Zero jQuery on frontend** — vanilla JavaScript, requestAnimationFrame throttled.
- **Conditional asset loading** — CSS/JS not loaded on pages where button is hidden.
- **SVG upload support** for custom icons (admin-only for security).
- **Accessible** — semantic `<button>` element, ARIA labels, screen reader text, keyboard navigation.
- **PHP 8.2+ compatible** — no deprecated dynamic properties.
- Compatible with all modern WordPress themes.
- Cross-browser compatible (Chrome, Firefox, Safari, Edge, etc.).

== Installation ==

###Install via WordPress Dashboard

1. Log in to your WordPress admin panel.
2. Navigate to **Plugins → Add New**.
3. In the search field, type **AR Back To Top**.
4. Locate the plugin in the results and click **Install Now**.
5. Once installed, click **Activate**.

---

###Manual Installation (ZIP Upload)

1. Visit the [WordPress.org Plugins Directory](https://wordpress.org/plugins/) and search for **AR Back To Top**.
2. Download the plugin `.zip` file to your computer.
3. In your WordPress admin panel, go to **Plugins → Add New → Upload Plugin**.
4. Click **Choose File**, select the downloaded ZIP file, and click **Install Now**.
5. After installation, click **Activate Plugin**.

---

###Install via FTP or File Manager

1. Download the plugin ZIP file from [WordPress.org](https://wordpress.org/plugins/).
2. Extract the contents of the ZIP file to your computer.
3. Open your preferred FTP client (e.g., FileZilla, Cyberduck) or use your hosting provider’s File Manager.
4. Upload the extracted folder to your WordPress plugin directory:  
   `wp-content/plugins/`
5. Log in to your WordPress admin dashboard.
6. Navigate to **Plugins → Installed Plugins**.
7. Find **AR Back To Top** in the list and click **Activate**.

== Changelog ==

= 3.0.3 =

**Compatibility Fix**
* Lower minimum WordPress requirement from 6.8 to 4.8 — restores compatibility with the vast majority of WordPress sites
* Add automatic upgrade routine that populates missing option defaults for users upgrading from v2.x, preventing broken buttons after update
* Fix stable tag to match 3.0.3

= 3.0.2 =

**Bug Fixes**
* Fix stable tag for WordPress.org plugin directory
* All fixes from 3.0.1 included

= 3.0.1 =

**Bug Fixes**
* Fix color and breakpoint fields not saving — add fallback defaults to all render fields
* Fix icon hover color inline default mismatch (#fff → #000)
* Fix icon picker not updating the live sidebar preview immediately
* Reduce default button offsets from 100px to 30px for better out-of-the-box positioning
* Add autocomplete="off" to color picker inputs
* Update screenshots and expand FAQ section

= 3.0.0 =

**Major Release — Complete Overhaul**

**Accessibility**
* Use semantic `<button>` element instead of `<div>` for the back-to-top button
* Add `aria-label`, screen reader text, and `aria-hidden` on decorative elements
* Add `:focus-visible` outline for keyboard navigation

**Performance**
* Remove jQuery dependency — rewrite frontend JS in vanilla JavaScript
* Add `requestAnimationFrame` throttling on scroll events
* Use passive event listeners for scroll/resize
* Conditional asset loading — skip CSS/JS on pages where button is hidden

**New Features**
* Page/post display filter — show or hide button on specific pages/posts (Select2 search)
* Auto-hide timer — button fades out after configurable seconds of inactivity
* Button tooltip text and z-index control
* Multiple scroll easing effects (linear, ease-in, ease-out, ease-in-out)
* Button shape presets — circle, square, rounded square, or custom
* Center bottom position option
* Hide on desktop toggle with breakpoint
* Custom icon upload via WordPress media library (PNG, JPG, GIF, SVG)
* Separate mobile positioning controls (bottom and side offsets)
* Show button in WP admin area
* Font Awesome version control — choose FA 6, FA 5, or skip if already loaded
* Auto-detect existing Font Awesome enqueue to avoid duplicates
* Reset to defaults button with confirmation

**UI Redesign**
* Modern tabbed admin settings (General, Appearance, Colors, Position, Scroll, Visibility, Advanced)
* Live preview panel with browser chrome mockup
* Select2 searchable multi-select for page/post filter
* Scroll progress forces circle shape with locked dropdown and notice

**Upgrades**
* Upgrade Font Awesome from 4.7 to 6.5.1 with automatic icon migration
* SVG upload support for admin users
* PHP 8.2+ compatible — no dynamic property deprecation warnings

**Bug Fixes**
* Fix dependent fields visibility (auto-hide, display pages, mobile offsets)
* Fix button shape not applying on frontend
* Fix scroll progress overriding shape selection
* Add `!important` with high-specificity selectors for theme compatibility

= 2.11.4 =

+ Added Website Status Page

= 2.11.3 =

+ Added Progress Bar Options
+ Added Button Image Position options
+ Improve Code Structure

= 2.11.2 =

+ Added Load JS Async/Defer Option for Performance
+ Added Custom CSS
+ Added Hide Button On Mobile & Tablet
+ Added Button Style
+ Improve Code Structure
+ Fixed previous error

= 2.10.0 =

+ Added Missing Readme.

= 2.0.9 =

+ Added Single Post View Page Word(s), Character(s), View(s) Count.
+ Added Meta Info View Position Like: Top/Bottom/Both of Content.
+ Code Structure Improve.

= 2.0.4 =

Update WordPress and PHP compatible version.
Improve code structure and more.

= 2.0.2 =

There is no need to upgrade just yet.

== Screenshots ==

1. General settings tab with enable/disable toggle, async loading, and live preview panel.
2. Appearance settings — choose from SVG Icon, Text Only, Image, Both, External URL, or Custom Upload.
3. Visibility settings — control display mode, device-specific hiding, and custom breakpoints.
4. Frontend output — the back-to-top button displayed on a live WordPress site.

== Frequently Asked Questions ==

= What does this plugin do? =
AR Back To Top adds a customizable "Back to Top" button to your WordPress site. When visitors scroll down the page, the button appears and smoothly scrolls them back to the top when clicked.

= How do I enable the button? =
After activating the plugin, go to **Back To Top** in your WordPress admin menu. Toggle **Enable Back To Top** on the General tab and click **Save Changes**.

= Can I use my own icon or image? =
Yes. On the Appearance tab, set **Button Style** to "SVG Icon" to pick from 19 built-in icons, "Image Only" for bundled images, "External Image URL" for a remote image, or "Custom Upload" to upload your own PNG, JPG, GIF, or SVG icon from the media library.

= How do I change the button colors? =
Go to the **Colors & Style** tab. You can set background, text/icon, and border colors for both normal and hover states using the color picker.

= Can I hide the button on mobile or tablet? =
Yes. On the **Visibility** tab, toggle **Hide on Tablet** or **Hide on Mobile** and set the breakpoints to control at which screen widths the button is hidden.

= Can I show the button only on specific pages? =
Yes. On the **Visibility** tab, change the **Display Mode** to "Show only on selected pages" or "Hide on selected pages" and search for the pages/posts you want.

= Does this plugin slow down my site? =
No. The frontend uses zero jQuery — it is written in pure vanilla JavaScript with `requestAnimationFrame` throttling and passive event listeners. Assets are only loaded on pages where the button is displayed. You can also enable async script loading for additional performance.

= Can I add a scroll progress indicator? =
Yes. On the **Scroll Behavior** tab, enable **Scroll Progress** to display a circular progress ring around the button that fills as the user scrolls down the page.

= How do I change the button position? =
On the **Position & Size** tab, choose **left**, **right**, or **center** bottom placement. You can also adjust the bottom and side offsets in pixels, and set separate offsets for mobile devices.

= Can I use this button in the WordPress admin area? =
Yes. On the **General** tab, enable **Show in Admin Area** to display the back-to-top button inside the WordPress dashboard.

= How do I reset all settings to defaults? =
Click the **Reset to Defaults** button at the bottom of the settings page. A confirmation dialog will appear before resetting.

= Is the plugin accessible? =
Yes. The button uses a semantic `<button>` element with ARIA labels, screen reader text, and a `:focus-visible` outline for keyboard navigation.

= Is this plugin compatible with my theme? =
AR Back To Top is compatible with all standard WordPress themes. It uses high-specificity CSS selectors with `!important` to ensure the button displays correctly regardless of your theme's styles.

= Where can I report bugs or suggest features? =
Please email anisur2805@gmail.com for any questions, bug reports, or feature suggestions.
