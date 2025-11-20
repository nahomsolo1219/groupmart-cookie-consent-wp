# GroupMart Cookie Consent - WordPress Plugin

Cookie consent banner for GroupMart with Google Analytics and Microsoft Clarity integration. Compliant with Australian Privacy Principles.

## Features

- ✅ Pre-configured with your Google Analytics and Microsoft Clarity IDs
- ✅ Compliant with Australian Privacy Principles (APPs)
- ✅ Easy-to-use WordPress admin settings page
- ✅ Mobile responsive design
- ✅ Remembers user preferences for 365 days
- ✅ Only loads tracking scripts after consent
- ✅ Shortcode support for cookie preferences link

## Installation

1. Download the plugin ZIP file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin"
4. Choose the ZIP file and click "Install Now"
5. Click "Activate Plugin"

## Configuration

After activation:

1. Go to **Settings → Cookie Consent** in your WordPress admin
2. Your tracking IDs are already pre-configured:
   - Google Analytics: G-C1Q10KPDCS
   - Microsoft Clarity: oaaudxp6cj
3. Update the Privacy Policy and Terms URLs if needed
4. Click "Save Changes"

## Usage

The cookie banner will automatically appear on all pages of your website.

### Add Cookie Preferences Link

To add a link that lets users change their cookie preferences, use one of these methods:

**Method 1: Shortcode (easiest)**
Add this to any page, post, or widget:
```
[gmcc_preferences_link]
```

You can customize the link text:
```
[gmcc_preferences_link text="Manage Cookies"]
```

**Method 2: Add to theme footer**
Edit your theme's `footer.php` and add:
```html
<a href="#" onclick="GroupMartCookieConsent.showPreferences(); return false;">
    Cookie Preferences
</a>
```

## Testing

1. Open your website in an incognito/private browser window
2. The cookie banner should appear at the bottom
3. Test all buttons:
   - Accept All
   - Reject All
   - Customize
   - Save Preferences
4. Open browser console (F12) to see tracking scripts loading

## Important Notes

### Remove Old Tracking Codes

If you have Google Analytics or Microsoft Clarity code added elsewhere:
- Through other plugins
- In your theme's header/footer
- Via theme customizer
- Via Google Tag Manager

**You must remove them** to avoid duplicate tracking. This plugin handles all tracking based on user consent.

### Privacy Policy

Make sure your Privacy Policy mentions:
- Use of cookies
- Google Analytics
- Microsoft Clarity
- User's right to manage cookie preferences

## Troubleshooting

### Banner doesn't appear
- Clear your browser cookies and cache
- Check if you've already accepted/rejected cookies (they're stored for 365 days)
- Open browser console (F12) and check for JavaScript errors

### Tracking not working
- Verify your tracking IDs in Settings → Cookie Consent
- Check if ad blockers are interfering
- Open browser console and look for "GroupMart: Loading..." messages
- Wait a few minutes for data to appear in Google Analytics/Clarity

### Conflicts with other plugins
- Disable other cookie consent or analytics plugins
- Check for JavaScript conflicts in browser console
- Try switching to a default WordPress theme to test

## Support

For support, please contact: support@beta.groupmart.com.au

## Changelog

### 1.0.0
- Initial release
- Pre-configured with GroupMart tracking IDs
- WordPress admin settings page
- Shortcode support
- Australian Privacy Principles compliant

## License

Proprietary - Created for GroupMart
