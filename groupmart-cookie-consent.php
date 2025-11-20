<?php
/**
 * Plugin Name: GroupMart Cookie Consent
 * Plugin URI: https://beta.groupmart.com.au
 * Description: Cookie consent banner for GroupMart with Google Analytics and Microsoft Clarity integration. Compliant with Australian Privacy Principles.
 * Version: 1.0.0
 * Author: GroupMart
 * Author URI: https://beta.groupmart.com.au
 * License: Proprietary
 * Text Domain: groupmart-cookie-consent
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GMCC_VERSION', '1.0.0');
define('GMCC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GMCC_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Enqueue plugin styles and scripts
 */
function gmcc_enqueue_assets() {
    // Enqueue CSS
    wp_enqueue_style(
        'groupmart-cookie-consent',
        GMCC_PLUGIN_URL . 'assets/css/groupmart-cookie-consent.css',
        array(),
        GMCC_VERSION
    );

    // Enqueue JavaScript
    wp_enqueue_script(
        'groupmart-cookie-consent',
        GMCC_PLUGIN_URL . 'assets/js/groupmart-cookie-consent.js',
        array(),
        GMCC_VERSION,
        false // Load in header, not footer
    );
}
add_action('wp_enqueue_scripts', 'gmcc_enqueue_assets');

/**
 * Add cookie consent banner HTML to footer
 */
function gmcc_add_banner() {
    ?>
    <!-- GroupMart Cookie Consent Banner -->
    <div id="gm-cookie-consent-overlay"></div>
    <div id="gm-cookie-consent-banner">
        <div class="gm-cookie-content">
            <div class="gm-cookie-header">
                <div class="gm-cookie-icon"></div>
                <h2 class="gm-cookie-title">We Value Your Privacy</h2>
            </div>
            
            <div class="gm-cookie-description">
                <p>GroupMart uses cookies and tracking technologies to enhance your browsing experience, personalize content, and analyze our traffic. We use cookies for essential website functionality and performance monitoring via Google Analytics and Microsoft Clarity.</p>
                <p>By clicking "Accept All", you consent to our use of cookies. You can customize your preferences or learn more in our <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" target="_blank">Privacy Policy</a> and <a href="<?php echo esc_url(home_url('/terms')); ?>" target="_blank">Terms and Conditions</a>.</p>
            </div>

            <div id="gm-cookie-options" class="gm-cookie-options">
                <div class="gm-cookie-option">
                    <div class="gm-cookie-option-content">
                        <div class="gm-cookie-option-title">Essential Cookies</div>
                        <div class="gm-cookie-option-description">
                            These cookies are necessary for the website to function and cannot be disabled. They enable core functionality such as security, account authentication, and shopping cart features.
                        </div>
                    </div>
                    <div class="gm-cookie-toggle">
                        <input type="checkbox" id="essential-cookies" checked disabled>
                        <label for="essential-cookies" class="gm-cookie-toggle-slider"></label>
                    </div>
                </div>

                <div class="gm-cookie-option">
                    <div class="gm-cookie-option-content">
                        <div class="gm-cookie-option-title">Analytics Cookies</div>
                        <div class="gm-cookie-option-description">
                            These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. We use Google Analytics and Microsoft Clarity to improve our service.
                        </div>
                    </div>
                    <div class="gm-cookie-toggle">
                        <input type="checkbox" id="analytics-cookies" checked>
                        <label for="analytics-cookies" class="gm-cookie-toggle-slider"></label>
                    </div>
                </div>
            </div>

            <div class="gm-cookie-buttons">
                <button class="gm-cookie-btn gm-cookie-btn-primary" onclick="GroupMartCookieConsent.acceptAll()">
                    Accept All
                </button>
                <button class="gm-cookie-btn gm-cookie-btn-secondary" onclick="GroupMartCookieConsent.savePreferences()">
                    Save Preferences
                </button>
                <button class="gm-cookie-btn gm-cookie-btn-secondary" onclick="GroupMartCookieConsent.rejectAll()">
                    Reject All
                </button>
                <button class="gm-cookie-btn gm-cookie-btn-link" onclick="GroupMartCookieConsent.toggleOptions()">
                    Customize
                </button>
            </div>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'gmcc_add_banner');

/**
 * Add settings link to plugins page
 */
function gmcc_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=groupmart-cookie-consent') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'gmcc_add_settings_link');

/**
 * Add admin menu
 */
function gmcc_add_admin_menu() {
    add_options_page(
        'GroupMart Cookie Consent Settings',
        'Cookie Consent',
        'manage_options',
        'groupmart-cookie-consent',
        'gmcc_settings_page'
    );
}
add_action('admin_menu', 'gmcc_add_admin_menu');

/**
 * Register settings
 */
function gmcc_register_settings() {
    register_setting('gmcc_settings', 'gmcc_google_analytics_id');
    register_setting('gmcc_settings', 'gmcc_clarity_id');
    register_setting('gmcc_settings', 'gmcc_privacy_policy_url');
    register_setting('gmcc_settings', 'gmcc_terms_url');
}
add_action('admin_init', 'gmcc_register_settings');

/**
 * Settings page
 */
function gmcc_settings_page() {
    // Set default values if not set
    if (get_option('gmcc_google_analytics_id') === false) {
        update_option('gmcc_google_analytics_id', 'G-C1Q10KPDCS');
    }
    if (get_option('gmcc_clarity_id') === false) {
        update_option('gmcc_clarity_id', 'oaaudxp6cj');
    }
    if (get_option('gmcc_privacy_policy_url') === false) {
        update_option('gmcc_privacy_policy_url', home_url('/privacy-policy'));
    }
    if (get_option('gmcc_terms_url') === false) {
        update_option('gmcc_terms_url', home_url('/terms'));
    }

    ?>
    <div class="wrap">
        <h1>GroupMart Cookie Consent Settings</h1>
        
        <div style="background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0;">✅ Plugin Active!</h3>
            <p>The cookie consent banner is now active on your website. Your tracking IDs have been pre-configured.</p>
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('gmcc_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="gmcc_google_analytics_id">Google Analytics ID</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="gmcc_google_analytics_id" 
                               name="gmcc_google_analytics_id" 
                               value="<?php echo esc_attr(get_option('gmcc_google_analytics_id', 'G-C1Q10KPDCS')); ?>" 
                               class="regular-text" 
                               placeholder="G-XXXXXXXXXX">
                        <p class="description">Your Google Analytics 4 (GA4) Measurement ID</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="gmcc_clarity_id">Microsoft Clarity ID</label>
                    </th>
                    <td>
                        <input type="text" 
                               id="gmcc_clarity_id" 
                               name="gmcc_clarity_id" 
                               value="<?php echo esc_attr(get_option('gmcc_clarity_id', 'oaaudxp6cj')); ?>" 
                               class="regular-text" 
                               placeholder="xxxxxxxxxx">
                        <p class="description">Your Microsoft Clarity Project ID</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="gmcc_privacy_policy_url">Privacy Policy URL</label>
                    </th>
                    <td>
                        <input type="url" 
                               id="gmcc_privacy_policy_url" 
                               name="gmcc_privacy_policy_url" 
                               value="<?php echo esc_attr(get_option('gmcc_privacy_policy_url', home_url('/privacy-policy'))); ?>" 
                               class="regular-text" 
                               placeholder="<?php echo esc_attr(home_url('/privacy-policy')); ?>">
                        <p class="description">Link to your Privacy Policy page</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="gmcc_terms_url">Terms & Conditions URL</label>
                    </th>
                    <td>
                        <input type="url" 
                               id="gmcc_terms_url" 
                               name="gmcc_terms_url" 
                               value="<?php echo esc_attr(get_option('gmcc_terms_url', home_url('/terms'))); ?>" 
                               class="regular-text" 
                               placeholder="<?php echo esc_attr(home_url('/terms')); ?>">
                        <p class="description">Link to your Terms & Conditions page</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>

        <hr>

        <h2>Testing</h2>
        <p>To test the cookie banner:</p>
        <ol>
            <li>Open your website in an incognito/private browser window</li>
            <li>You should see the cookie banner at the bottom of the page</li>
            <li>Test all buttons (Accept All, Reject All, Customize)</li>
            <li>Open browser console (F12) to see tracking scripts loading</li>
        </ol>

        <h2>Add Cookie Preferences Link</h2>
        <p>Add this shortcode to your footer or any page to let users change their preferences:</p>
        <code>[gmcc_preferences_link]</code>
        <p>Or add this HTML anywhere in your theme:</p>
        <code>&lt;a href="#" onclick="GroupMartCookieConsent.showPreferences(); return false;"&gt;Cookie Preferences&lt;/a&gt;</code>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
            <h3 style="margin-top: 0;">⚠️ Important: Remove Old Tracking Codes</h3>
            <p>If you have Google Analytics or Microsoft Clarity code added elsewhere in your theme or through other plugins, <strong>you must remove them</strong> to avoid duplicate tracking. This plugin will handle all tracking based on user consent.</p>
        </div>
    </div>
    <?php
}

/**
 * Shortcode for cookie preferences link
 */
function gmcc_preferences_link_shortcode($atts) {
    $atts = shortcode_atts(array(
        'text' => 'Cookie Preferences'
    ), $atts);
    
    return '<a href="#" onclick="GroupMartCookieConsent.showPreferences(); return false;">' . esc_html($atts['text']) . '</a>';
}
add_shortcode('gmcc_preferences_link', 'gmcc_preferences_link_shortcode');

/**
 * Pass settings to JavaScript
 */
function gmcc_localize_script() {
    $ga_id = get_option('gmcc_google_analytics_id', 'G-C1Q10KPDCS');
    $clarity_id = get_option('gmcc_clarity_id', 'oaaudxp6cj');
    
    ?>
    <script type="text/javascript">
        var gmccSettings = {
            googleAnalyticsId: '<?php echo esc_js($ga_id); ?>',
            clarityId: '<?php echo esc_js($clarity_id); ?>'
        };
    </script>
    <?php
}
add_action('wp_head', 'gmcc_localize_script', 5);

/**
 * Activation hook
 */
function gmcc_activate() {
    // Set default options
    add_option('gmcc_google_analytics_id', 'G-C1Q10KPDCS');
    add_option('gmcc_clarity_id', 'oaaudxp6cj');
    add_option('gmcc_privacy_policy_url', home_url('/privacy-policy'));
    add_option('gmcc_terms_url', home_url('/terms'));
}
register_activation_hook(__FILE__, 'gmcc_activate');

/**
 * Deactivation hook
 */
function gmcc_deactivate() {
    // Clean up if needed
}
register_deactivation_hook(__FILE__, 'gmcc_deactivate');
