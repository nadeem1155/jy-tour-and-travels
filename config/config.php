<?php
/**
 * JY TOUR and TRAVELS - Application Configuration & Settings Registry
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

// Detect Base URL dynamically
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$scriptDir = str_replace('\\', '/', $scriptDir);
// If within admin directory, trim '/admin'
$basePath = preg_replace('#/admin.*$#', '', $scriptDir);
$basePath = rtrim($basePath, '/');
define('BASE_URL', $protocol . $host . $basePath);
define('ROOT_PATH', dirname(__DIR__));

// Business Fallback Defaults (Strictly based on reference advertisement)
$DEFAULT_SETTINGS = [
    'business_name'             => 'JY TOUR and TRAVELS',
    'tagline'                   => 'Reliable Travel & Car Rental Services in Lucknow',
    'phone'                     => '+91 9450150697',
    'phone_raw'                 => '9450150697',
    'email'                     => 'jytourandtravels32@gmail.com',
    'address'                   => '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India',
    'address_short'             => '8/273 Rajni Khand, Sharda Nagar, Lucknow',
    'city'                      => 'Lucknow',
    'state'                     => 'Uttar Pradesh',
    'country'                   => 'India',
    'whatsapp_number'           => '+919450150697',
    'whatsapp_default_message'  => 'Hello JY TOUR and TRAVELS, I would like to enquire about vehicle booking.',
    'all_india_permit_badge'    => 'WITH ALL INDIA PERMIT',
    'hero_headline'             => 'Reliable Travel & Car Rental Services in Lucknow',
    'hero_supporting_text'      => 'Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus',
    'hero_cta_primary'          => 'Book Now',
    'hero_cta_secondary'        => 'Call +91 9450150697',
    'about_intro'               => 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.',
    'permit_banner_title'       => 'TRAVEL ACROSS INDIA WITH JY TOUR AND TRAVELS',
    'permit_banner_highlight'   => 'ALL INDIA PERMIT',
    'permit_banner_desc'        => 'Book your preferred vehicle for your travel requirements with complete legal authorization and interstate permits.',
    'business_hours'            => '24x7 Booking & Customer Support Available',
    'google_map_embed'          => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14245.549248744577!2d80.916892!3d26.779774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bf9f96b99b5a7%3A0xbce962141505c8a!2sRajni%20Khand%2C%20Sharda%20Nagar%2C%20Lucknow%2C%20Uttar%20Pradesh%20226002!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin',
    'meta_title'                => 'JY TOUR and TRAVELS | Reliable Travel & Car Rental Services in Lucknow',
    'meta_description'          => 'JY TOUR and TRAVELS in Lucknow offers reliable car rental and tourist transportation with All India Permit. Dzire, Aura, Ertiga, Toyota Innova Crysta and Luxury Bus.',
    'meta_keywords'             => 'JY Tour and Travels Lucknow, car rental Lucknow, tour and travels Lucknow, luxury car rental Lucknow, Toyota Innova Crysta rental Lucknow, luxury bus rental Lucknow, outstation cab Lucknow, All India permit taxi Lucknow'
];

/**
 * Retrieve setting value by key with fallback
 */
function get_setting(string $key, string $default = ''): string {
    global $DEFAULT_SETTINGS;
    static $settingsCache = null;

    if ($settingsCache === null) {
        $settingsCache = [];
        $pdo = get_db_connection();
        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT setting_key, setting_value FROM website_settings");
                while ($row = $stmt->fetch()) {
                    $settingsCache[$row['setting_key']] = $row['setting_value'];
                }
            } catch (Exception $e) {
                // table might not be created yet; keep empty cache
            }
        }
    }

    if (isset($settingsCache[$key]) && trim($settingsCache[$key]) !== '') {
        return $settingsCache[$key];
    }

    if (isset($DEFAULT_SETTINGS[$key])) {
        return $DEFAULT_SETTINGS[$key];
    }

    return $default;
}
