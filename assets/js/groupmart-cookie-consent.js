/**
 * GroupMart Cookie Consent Plugin
 * Version: 1.0.0
 * 
 * IMPORTANT: Before using, replace the following placeholder IDs:
 * - Line 120: Replace 'G-XXXXXXXXXX' with your Google Analytics ID
 * - Line 129: Replace 'XXXXXXXXXX' with your Microsoft Clarity ID
 * - Line 137: Replace 'XXXXXXXXXX' with your Facebook Pixel ID
 */

const GroupMartCookieConsent = {
    cookieName: 'gm_cookie_consent',
    cookieExpiry: 365, // days

    // Configuration - Gets tracking IDs from WordPress settings or uses defaults
    config: {
        googleAnalyticsId: (typeof gmccSettings !== 'undefined' && gmccSettings.googleAnalyticsId) ? gmccSettings.googleAnalyticsId : 'G-C1Q10KPDCS',
        clarityId: (typeof gmccSettings !== 'undefined' && gmccSettings.clarityId) ? gmccSettings.clarityId : 'oaaudxp6cj'
    },

    init: function() {
        // Check if user has already made a choice
        const consent = this.getCookie(this.cookieName);
        
        if (!consent) {
            // Show banner if no consent found
            this.showBanner();
        } else {
            // Apply saved preferences
            this.applySavedConsent(consent);
        }
    },

    showBanner: function() {
        const banner = document.getElementById('gm-cookie-consent-banner');
        
        if (banner) {
            banner.classList.add('show');
        }
    },

    hideBanner: function() {
        const banner = document.getElementById('gm-cookie-consent-banner');
        
        if (banner) {
            banner.classList.remove('show');
        }
    },

    toggleOptions: function() {
        const options = document.getElementById('gm-cookie-options');
        if (options) {
            options.classList.toggle('show');
        }
    },

    acceptAll: function() {
        const consent = {
            essential: true,
            analytics: true,
            marketing: true,
            timestamp: new Date().toISOString()
        };
        
        this.saveConsent(consent);
        this.loadScripts(consent);
        this.hideBanner();
    },

    rejectAll: function() {
        const consent = {
            essential: true,
            analytics: false,
            marketing: false,
            timestamp: new Date().toISOString()
        };
        
        this.saveConsent(consent);
        this.loadScripts(consent);
        this.hideBanner();
    },

    savePreferences: function() {
        const consent = {
            essential: true,
            analytics: document.getElementById('analytics-cookies').checked,
            marketing: document.getElementById('marketing-cookies').checked,
            timestamp: new Date().toISOString()
        };
        
        this.saveConsent(consent);
        this.loadScripts(consent);
        this.hideBanner();
    },

    saveConsent: function(consent) {
        this.setCookie(this.cookieName, JSON.stringify(consent), this.cookieExpiry);
        
        // Trigger custom event for other scripts
        window.dispatchEvent(new CustomEvent('cookieConsentUpdated', { 
            detail: consent 
        }));

        console.log('GroupMart: Cookie preferences saved', consent);
    },

    applySavedConsent: function(consentString) {
        try {
            const consent = JSON.parse(consentString);
            this.loadScripts(consent);
        } catch (e) {
            console.error('GroupMart: Error parsing cookie consent:', e);
        }
    },

    loadScripts: function(consent) {
        console.log('GroupMart: Loading scripts based on consent', consent);

        // Load Google Analytics
        if (consent.analytics && !window.ga && !window.gtag) {
            this.loadGoogleAnalytics();
        }

        // Load Microsoft Clarity
        if (consent.analytics && !window.clarity) {
            this.loadMicrosoftClarity();
        }
    },

    loadGoogleAnalytics: function() {
        console.log('GroupMart: Loading Google Analytics');
        
        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${this.config.googleAnalyticsId}`;
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        window.gtag = gtag;
        gtag('js', new Date());
        gtag('config', this.config.googleAnalyticsId, {
            'anonymize_ip': true,
            'cookie_flags': 'SameSite=None;Secure'
        });
    },

    loadMicrosoftClarity: function() {
        console.log('GroupMart: Loading Microsoft Clarity');
        
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, "clarity", "script", this.config.clarityId);
    },

    setCookie: function(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/;SameSite=Lax";
    },

    getCookie: function(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    deleteCookie: function(name) {
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";
    },

    // Method to allow users to update preferences later
    showPreferences: function() {
        const consent = this.getCookie(this.cookieName);
        if (consent) {
            try {
                const consentObj = JSON.parse(consent);
                document.getElementById('analytics-cookies').checked = consentObj.analytics;
                document.getElementById('marketing-cookies').checked = consentObj.marketing;
            } catch (e) {
                console.error('GroupMart: Error loading preferences:', e);
            }
        }
        this.showBanner();
        this.toggleOptions();
    },

    // Method to reset all cookie preferences
    resetPreferences: function() {
        this.deleteCookie(this.cookieName);
        console.log('GroupMart: Cookie preferences reset');
        location.reload();
    }
};

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        GroupMartCookieConsent.init();
    });
} else {
    GroupMartCookieConsent.init();
}

// Expose globally for manual preference management
window.GroupMartCookieConsent = GroupMartCookieConsent;

// Listen for consent updates
window.addEventListener('cookieConsentUpdated', function(event) {
    console.log('GroupMart: Cookie consent updated', event.detail);
});
