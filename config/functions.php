<?php
/**
 * Helper and Utility Functions
 */

/**
 * Escape string for safe HTML output
 */
function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize generic string input
 */
function sanitize(?string $data): string {
    return trim(filter_var($data ?? '', FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_LOW));
}

/**
 * Generate CSRF Token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output hidden CSRF form input field
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Verify CSRF Token
 */
function verify_csrf_token(?string $token): bool {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set Flash Message
 */
function set_flash(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type'    => $type, // success, danger, warning, info
        'message' => $message
    ];
}

/**
 * Retrieve and Clear Flash Message
 */
function get_flash(): ?array {
    if (!empty($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

/**
 * Generate unique human-readable booking reference
 */
function generate_booking_number(): string {
    return 'JY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
}

/**
 * Safe email notification dispatcher
 */
function send_notification_email(string $to, string $subject, string $messageBody): bool {
    $fromEmail = 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'jytourandtravels.com');
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: JY TOUR and TRAVELS <{$fromEmail}>\r\n";
    $headers .= "Reply-To: " . get_setting('email', 'jytourandtravels32@gmail.com') . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Mail may not be configured on local dev; catch warning gracefully
    try {
        return @mail($to, $subject, $messageBody, $headers);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Slugify text for URLs
 */
function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    return strtolower($text);
}

/**
 * Format date for display
 */
function format_date(?string $date, string $format = 'd M Y'): string {
    if (empty($date) || $date === '0000-00-00') {
        return 'N/A';
    }
    $time = strtotime($date);
    return $time ? date($format, $time) : 'N/A';
}

/**
 * Build WhatsApp click-to-chat URL
 */
function get_whatsapp_url(string $customMessage = ''): string {
    $phone = preg_replace('/[^0-9]/', '', get_setting('whatsapp_number', '919450150697'));
    $msg = !empty($customMessage) ? $customMessage : get_setting('whatsapp_default_message', 'Hello JY TOUR and TRAVELS, I want to enquire about vehicle booking.');
    return 'https://api.whatsapp.com/send?phone=' . urlencode($phone) . '&text=' . urlencode($msg);
}

/**
 * Fallback vehicles list when database is starting up
 */
function get_fallback_vehicles(): array {
    return [
        [
            'id' => 1,
            'name' => 'Dzire',
            'slug' => 'dzire',
            'category_name' => 'Luxury Sedan',
            'tagline' => 'Premium Sedan for Executive and Family Travel',
            'image_url' => 'assets/images/vehicles/sedan_dzire.jpg',
            'passenger_capacity' => '4 + 1 Passengers',
            'luggage_capacity' => '2 Bags',
            'ac_type' => 'Climate Control AC',
            'fuel_type' => 'Petrol / Diesel',
            'pricing_display' => 'Affordable Tariff on Call',
            'all_india_permit' => 1,
            'description' => 'Maruti Suzuki Dzire delivers a refined sedan journey with plush upholstery, impressive legroom, and effortless highway cruising across India.',
            'features' => 'All India Permit, Air Conditioned, Pushback Seats, Bluetooth Audio, Professional Chauffeur, Sanitized Cabin',
            'status' => 1
        ],
        [
            'id' => 2,
            'name' => 'Aura',
            'slug' => 'aura',
            'category_name' => 'Luxury Sedan / Hatch Back',
            'tagline' => 'Modern Compact Sedan with Smooth Ride Quality',
            'image_url' => 'assets/images/vehicles/aura_sedan.jpg',
            'passenger_capacity' => '4 + 1 Passengers',
            'luggage_capacity' => '2 Bags',
            'ac_type' => 'Powerful AC',
            'fuel_type' => 'Petrol / CNG',
            'pricing_display' => 'Competitive Rates on Call',
            'all_india_permit' => 1,
            'description' => 'Hyundai Aura is known for its contemporary design, comfortable rear seats, and smooth suspension, making both city drives and outstation tours pleasant.',
            'features' => 'All India Permit, Full Air Conditioning, Modern Sound System, Ample Boot Space, Experienced Driver, Clean Interior',
            'status' => 1
        ],
        [
            'id' => 3,
            'name' => 'Ertiga',
            'slug' => 'ertiga',
            'category_name' => 'Luxury MUV',
            'tagline' => 'Versatile and Spacious 7-Seater Family MUV',
            'image_url' => 'assets/images/vehicles/ertiga_muv.jpg',
            'passenger_capacity' => '6 + 1 Passengers',
            'luggage_capacity' => '3 Bags',
            'ac_type' => 'Front & Rear AC',
            'fuel_type' => 'Diesel / Hybrid',
            'pricing_display' => 'Best Group Rates on Call',
            'all_india_permit' => 1,
            'description' => 'Maruti Suzuki Ertiga is the ideal choice for family vacations and corporate outings, offering flexible 3-row seating and dedicated rear air-conditioning vents.',
            'features' => 'All India Permit, 3-Row Air Conditioning, Reclining Seats, Large Luggage Room, Fast-Tag Enabled, Verified Driver',
            'status' => 1
        ],
        [
            'id' => 4,
            'name' => 'Toyota Innova Crysta',
            'slug' => 'toyota-innova-crysta',
            'category_name' => 'Luxury SUV / Premium MUV',
            'tagline' => 'The Gold Standard of Premium Highway Travel',
            'image_url' => 'assets/images/vehicles/innova_crysta_crop.jpg',
            'passenger_capacity' => '7 + 1 Passengers',
            'luggage_capacity' => '4 Bags',
            'ac_type' => 'Dual Automatic Climate Control',
            'fuel_type' => 'Diesel',
            'pricing_display' => 'Premium Quality at Fair Rates',
            'all_india_permit' => 1,
            'description' => 'Toyota Innova Crysta represents royal travel comfort with unmatched safety, captains chair seating options, and silent high-speed cruising with All India Permit.',
            'features' => 'All India Permit, Captain Seats, Automatic Climate Control, Highway Cruiser, Mobile Charging Points, Safety Airbags',
            'status' => 1
        ],
        [
            'id' => 5,
            'name' => 'Luxury Bus',
            'slug' => 'luxury-bus',
            'category_name' => 'Luxury Bus',
            'tagline' => 'Heavy Passenger Luxury Coach for Tours and Events',
            'image_url' => 'assets/images/vehicles/luxury_bus.jpg',
            'passenger_capacity' => '18 to 45+ Passengers',
            'luggage_capacity' => 'Dedicated Luggage Bay',
            'ac_type' => 'High-Capacity Central AC',
            'fuel_type' => 'Diesel',
            'pricing_display' => 'Custom Tour Quotes on Call',
            'all_india_permit' => 1,
            'description' => 'Our fleet of luxury coaches and buses is designed for group pilgrimage, destination weddings, school/college excursions, and intercity corporate tours across India.',
            'features' => 'All India Permit, Air Suspension, Pushback Recliners, Central Audio/Video, Overhead Storage, Professional Tour Driver',
            'status' => 1
        ]
    ];
}

/**
 * Fallback services matching reference advertisement
 */
function get_fallback_services(): array {
    return [
        [
            'id' => 1,
            'name' => 'Luxury Sedan',
            'slug' => 'luxury-sedan',
            'category_code' => 'sedan',
            'icon_file' => 'luxury-sedan.svg',
            'short_desc' => 'Executive sedan travel featuring vehicles like Dzire and Aura for corporate, airport transfers and outstation journeys.',
            'full_desc' => 'Experience quiet rides and smooth journeys with our Luxury Sedan service. Equipped with professional chauffeurs and All India Permit.',
            'image_url' => 'assets/images/vehicles/sedan_dzire.jpg'
        ],
        [
            'id' => 2,
            'name' => 'Luxury MUV',
            'slug' => 'luxury-muv',
            'category_code' => 'muv',
            'icon_file' => 'luxury-muv.svg',
            'short_desc' => 'Multi-utility vehicle service including Ertiga for comfortable family trips, group tours and weekend getaways.',
            'full_desc' => 'Our Luxury MUV service provides superior legroom, luggage capacity and flexibility for families and medium-sized groups traveling across India.',
            'image_url' => 'assets/images/vehicles/ertiga_muv.jpg'
        ],
        [
            'id' => 3,
            'name' => 'Luxury SUV',
            'slug' => 'luxury-suv',
            'category_code' => 'suv',
            'icon_file' => 'luxury-suv.svg',
            'short_desc' => 'High-end SUV comfort with Toyota Innova Crysta for long-distance highway travel, VIP tours and holiday tours.',
            'full_desc' => 'Step up to highest comfort with our flagship Toyota Innova Crysta fleet. Unmatched ride stability and premier seating with All India Permit.',
            'image_url' => 'assets/images/vehicles/innova_crysta_crop.jpg'
        ],
        [
            'id' => 4,
            'name' => 'Luxury Bus',
            'slug' => 'luxury-bus',
            'category_code' => 'bus',
            'icon_file' => 'luxury-bus.svg',
            'short_desc' => 'Spacious luxury buses and coaches for large group tours, pilgrimage circuits, destination weddings and group travel.',
            'full_desc' => 'Equipped with pushback seats, air suspension, and experienced long-haul drivers to make group transportation seamless and secure.',
            'image_url' => 'assets/images/vehicles/luxury_bus.jpg'
        ],
        [
            'id' => 5,
            'name' => 'Luxury Hatch Back',
            'slug' => 'luxury-hatch-back',
            'category_code' => 'hatchback',
            'icon_file' => 'luxury-hatchback.svg',
            'short_desc' => 'Economical and agile compact vehicle options for budget-friendly city navigation and regional travel.',
            'full_desc' => 'Ideal for quick errands, station pickups, and cost-effective individual transportation without compromising on comfort and safety.',
            'image_url' => 'assets/images/vehicles/aura_sedan.jpg'
        ]
    ];
}

/**
 * Fallback gallery items
 */
function get_fallback_gallery(): array {
    return [
        ['id' => 1, 'title' => 'Toyota Innova Crysta Fleet', 'category' => 'luxury_cars', 'image_url' => 'assets/images/vehicles/innova_crysta_crop.jpg', 'caption' => 'Our flagship Toyota Innova Crysta ready for outstation journeys.'],
        ['id' => 2, 'title' => 'Luxury Tour Bus for Groups', 'category' => 'bus', 'image_url' => 'assets/images/vehicles/luxury_bus.jpg', 'caption' => 'Air-conditioned luxury coach for group expeditions.'],
        ['id' => 3, 'title' => 'Executive Dzire Sedan', 'category' => 'vehicles', 'image_url' => 'assets/images/vehicles/sedan_dzire.jpg', 'caption' => 'Spacious and sanitized Dzire sedan for comfortable transit.'],
        ['id' => 4, 'title' => 'Lucknow Heritage Tour', 'category' => 'travel', 'image_url' => 'assets/images/gallery/lucknow_rumi.jpg', 'caption' => 'Local sightseeing and historical monument tours in Lucknow.'],
        ['id' => 5, 'title' => 'National Highway Travel', 'category' => 'road_trips', 'image_url' => 'assets/images/gallery/road_trip_highway.jpg', 'caption' => 'Long distance travel across India with All India Permit.'],
        ['id' => 6, 'title' => 'Premium Car Interior', 'category' => 'luxury_cars', 'image_url' => 'assets/images/gallery/luxury_car_interior.jpg', 'caption' => 'Clean, comfortable and well-maintained passenger cabin.'],
        ['id' => 7, 'title' => 'Scenic Mountain Route', 'category' => 'road_trips', 'image_url' => 'assets/images/gallery/scenic_mountain_drive.jpg', 'caption' => 'Reliable and safe hill station journeys with experienced drivers.'],
        ['id' => 8, 'title' => 'Group Vacation Bus', 'category' => 'bus', 'image_url' => 'assets/images/gallery/tourist_group_bus.jpg', 'caption' => 'Comfortable group transit for families and corporate teams.']
    ];
}
