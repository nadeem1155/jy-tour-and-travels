<?php
/**
 * JY TOUR and TRAVELS - Database Configuration
 * Dual PDO Connection Handler with automatic SQLite local development fallback.
 */

defined('APP_INIT') or define('APP_INIT', true);

// Database Credentials for MySQL (Production / XAMPP / Live server)
define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'jy_tours_travels');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO Database Connection
 *
 * @return PDO|null
 */
function get_db_connection(): ?PDO {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    // 1. Try MySQL First
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // MySQL is not reachable or database not created yet; fallback to SQLite for local execution
        $sqliteFile = __DIR__ . '/../database/local_dev.sqlite';
        try {
            $pdo = new PDO("sqlite:" . $sqliteFile, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            // Auto-initialize SQLite schema if freshly created
            init_sqlite_tables($pdo);
            return $pdo;
        } catch (Exception $sqliteErr) {
            error_log("Database connection failure: " . $sqliteErr->getMessage());
            return null;
        }
    }
}

/**
 * Initialize SQLite schema & seed records for local zero-config testing
 */
function init_sqlite_tables(PDO $pdo): void {
    static $initialized = false;
    if ($initialized) return;

    // Check if admins table already exists
    $check = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='admins'")->fetch();
    if ($check) {
        $initialized = true;
        return;
    }

    $queries = [
        "CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE,
            email TEXT UNIQUE,
            password_hash TEXT,
            full_name TEXT,
            role TEXT DEFAULT 'superadmin',
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            last_login DATETIME
        );",
        "CREATE TABLE IF NOT EXISTS vehicle_categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            slug TEXT UNIQUE,
            icon_svg TEXT,
            short_desc TEXT,
            display_order INTEGER DEFAULT 0,
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS vehicles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            category_id INTEGER,
            name TEXT,
            slug TEXT UNIQUE,
            category_name TEXT,
            tagline TEXT,
            image_url TEXT,
            passenger_capacity TEXT,
            luggage_capacity TEXT,
            ac_type TEXT,
            fuel_type TEXT,
            pricing_display TEXT,
            all_india_permit INTEGER DEFAULT 1,
            description TEXT,
            features TEXT,
            is_featured INTEGER DEFAULT 1,
            display_order INTEGER DEFAULT 0,
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS services (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            slug TEXT UNIQUE,
            category_code TEXT,
            icon_file TEXT,
            short_desc TEXT,
            full_desc TEXT,
            image_url TEXT,
            display_order INTEGER DEFAULT 0,
            status INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS bookings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            booking_number TEXT UNIQUE,
            full_name TEXT,
            phone TEXT,
            email TEXT,
            pickup_location TEXT,
            destination TEXT,
            travel_date DATE,
            return_date DATE,
            vehicle_id INTEGER,
            vehicle_name TEXT,
            passengers INTEGER DEFAULT 1,
            trip_type TEXT DEFAULT 'Outstation',
            additional_requirements TEXT,
            status TEXT DEFAULT 'New',
            admin_notes TEXT,
            ip_address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT,
            phone TEXT,
            email TEXT,
            subject TEXT,
            message TEXT,
            status TEXT DEFAULT 'New',
            ip_address TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS gallery (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT,
            category TEXT,
            image_url TEXT,
            caption TEXT,
            display_order INTEGER DEFAULT 0,
            is_featured INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );",
        "CREATE TABLE IF NOT EXISTS website_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT UNIQUE,
            setting_value TEXT,
            setting_group TEXT DEFAULT 'general',
            display_label TEXT,
            field_type TEXT DEFAULT 'text',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );"
    ];

    foreach ($queries as $q) {
        $pdo->exec($q);
    }

    // Seed default admin
    $adminHash = '$2b$10$nQ0PEpbpiGHJob/ZAAWlZOSBSZCyefOHisl1zBDZlY4KbgZpCtsc6';
    $pdo->exec("INSERT OR IGNORE INTO admins (id, username, email, password_hash, full_name, role, status) VALUES
        (1, 'admin', 'jytourandtravels32@gmail.com', '{$adminHash}', 'JY Administrator', 'superadmin', 1);");

    // Seed categories
    $pdo->exec("INSERT OR IGNORE INTO vehicle_categories (id, name, slug, icon_svg, short_desc, display_order, status) VALUES
        (1, 'Luxury Sedan', 'luxury-sedan', 'luxury-sedan.svg', 'Comfortable, fuel-efficient and executive travel with spacious seating.', 1, 1),
        (2, 'Luxury MUV', 'luxury-muv', 'luxury-muv.svg', 'Multi-utility vehicles engineered for smooth family and group journeys.', 2, 1),
        (3, 'Luxury SUV', 'luxury-suv', 'luxury-suv.svg', 'Commanding road presence, superior comfort and robust outstation capability.', 3, 1),
        (4, 'Luxury Bus', 'luxury-bus', 'luxury-bus.svg', 'Heavy passenger capacity luxury buses for tours, groups and events.', 4, 1),
        (5, 'Luxury Hatch Back', 'luxury-hatch-back', 'luxury-hatchback.svg', 'Agile and economical rides suitable for convenient city and regional travel.', 5, 1);");

    // Seed vehicles
    $pdo->exec("INSERT OR IGNORE INTO vehicles (id, category_id, name, slug, category_name, tagline, image_url, passenger_capacity, luggage_capacity, ac_type, fuel_type, pricing_display, all_india_permit, description, features, is_featured, display_order, status) VALUES
        (1, 1, 'Dzire', 'dzire', 'Luxury Sedan', 'Premium Sedan for Executive and Family Travel', 'assets/images/vehicles/sedan_dzire.jpg', '4 + 1 Passengers', '2 Bags', 'Climate Control AC', 'Petrol / Diesel', 'Affordable Tariff on Call', 1, 'Maruti Suzuki Dzire delivers a refined sedan journey with plush upholstery, impressive legroom, and effortless highway cruising across India.', 'All India Permit, Air Conditioned, Pushback Seats, Bluetooth Audio, Professional Chauffeur, Sanitized Cabin', 1, 1, 1),
        (2, 1, 'Aura', 'aura', 'Luxury Sedan / Hatch Back', 'Modern Compact Sedan with Smooth Ride Quality', 'assets/images/vehicles/aura_sedan.jpg', '4 + 1 Passengers', '2 Bags', 'Powerful AC', 'Petrol / CNG', 'Competitive Rates on Call', 1, 'Hyundai Aura is known for its contemporary design, comfortable rear seats, and smooth suspension, making both city drives and outstation tours pleasant.', 'All India Permit, Full Air Conditioning, Modern Sound System, Ample Boot Space, Experienced Driver, Clean Interior', 1, 2, 1),
        (3, 2, 'Ertiga', 'ertiga', 'Luxury MUV', 'Versatile and Spacious 7-Seater Family MUV', 'assets/images/vehicles/ertiga_muv.jpg', '6 + 1 Passengers', '3 Bags', 'Front & Rear AC', 'Diesel / Hybrid', 'Best Group Rates on Call', 1, 'Maruti Suzuki Ertiga is the ideal choice for family vacations and corporate outings, offering flexible 3-row seating and dedicated rear air-conditioning vents.', 'All India Permit, 3-Row Air Conditioning, Reclining Seats, Large Luggage Room, Fast-Tag Enabled, Verified Driver', 1, 3, 1),
        (4, 3, 'Toyota Innova Crysta', 'toyota-innova-crysta', 'Luxury SUV / Premium MUV', 'The Gold Standard of Premium Highway Travel', 'assets/images/vehicles/innova_crysta_crop.jpg', '7 + 1 Passengers', '4 Bags', 'Dual Automatic Climate Control', 'Diesel', 'Premium Quality at Fair Rates', 1, 'Toyota Innova Crysta represents royal travel comfort with unmatched safety, captains chair seating options, and silent high-speed cruising with All India Permit.', 'All India Permit, Captain Seats, Automatic Climate Control, Highway Cruiser, Mobile Charging Points, Safety Airbags', 1, 4, 1),
        (5, 4, 'Luxury Bus', 'luxury-bus', 'Luxury Bus', 'Heavy Passenger Luxury Coach for Tours and Events', 'assets/images/vehicles/luxury_bus.jpg', '18 to 45+ Passengers', 'Dedicated Luggage Bay', 'High-Capacity Central AC', 'Diesel', 'Custom Tour Quotes on Call', 1, 'Our fleet of luxury coaches and buses is designed for group pilgrimage, destination weddings, school/college excursions, and intercity corporate tours across India.', 'All India Permit, Air Suspension, Pushback Recliners, Central Audio/Video, Overhead Storage, Professional Tour Driver', 1, 5, 1);");

    // Seed services
    $pdo->exec("INSERT OR IGNORE INTO services (id, name, slug, category_code, icon_file, short_desc, full_desc, image_url, display_order, status) VALUES
        (1, 'Luxury Sedan', 'luxury-sedan', 'sedan', 'luxury-sedan.svg', 'Executive sedan travel featuring vehicles like Dzire and Aura for corporate, airport transfers and outstation journeys.', 'Experience quiet rides and smooth journeys with our Luxury Sedan service. Equipped with professional chauffeurs and All India Permit.', 'assets/images/vehicles/sedan_dzire.jpg', 1, 1),
        (2, 'Luxury MUV', 'luxury-muv', 'muv', 'luxury-muv.svg', 'Multi-utility vehicle service including Ertiga for comfortable family trips, group tours and weekend getaways.', 'Our Luxury MUV service provides superior legroom, luggage capacity and flexibility for families and medium-sized groups traveling across India.', 'assets/images/vehicles/ertiga_muv.jpg', 2, 1),
        (3, 'Luxury SUV', 'luxury-suv', 'suv', 'luxury-suv.svg', 'High-end SUV comfort with Toyota Innova Crysta for long-distance highway travel, VIP tours and holiday tours.', 'Step up to highest comfort with our flagship Toyota Innova Crysta fleet. Unmatched ride stability and premier seating with All India Permit.', 'assets/images/vehicles/innova_crysta_crop.jpg', 3, 1),
        (4, 'Luxury Bus', 'luxury-bus', 'bus', 'luxury-bus.svg', 'Spacious luxury buses and coaches for large group tours, pilgrimage circuits, destination weddings and group travel.', 'Equipped with pushback seats, air suspension, and experienced long-haul drivers to make group transportation seamless and secure.', 'assets/images/vehicles/luxury_bus.jpg', 4, 1),
        (5, 'Luxury Hatch Back', 'luxury-hatch-back', 'hatchback', 'luxury-hatchback.svg', 'Economical and agile compact vehicle options for budget-friendly city navigation and regional travel.', 'Ideal for quick errands, station pickups, and cost-effective individual transportation without compromising on comfort and safety.', 'assets/images/vehicles/aura_sedan.jpg', 5, 1);");

    // Seed gallery
    $pdo->exec("INSERT OR IGNORE INTO gallery (id, title, category, image_url, caption, display_order, is_featured) VALUES
        (1, 'Toyota Innova Crysta Fleet', 'luxury_cars', 'assets/images/vehicles/innova_crysta_crop.jpg', 'Our flagship Toyota Innova Crysta ready for outstation journeys.', 1, 1),
        (2, 'Luxury Tour Bus for Groups', 'bus', 'assets/images/vehicles/luxury_bus.jpg', 'Air-conditioned luxury coach for group expeditions.', 2, 1),
        (3, 'Executive Dzire Sedan', 'vehicles', 'assets/images/vehicles/sedan_dzire.jpg', 'Spacious and sanitized Dzire sedan for comfortable transit.', 3, 1),
        (4, 'Lucknow Heritage Tour', 'travel', 'assets/images/gallery/lucknow_rumi.jpg', 'Local sightseeing and historical monument tours in Lucknow.', 4, 1),
        (5, 'National Highway Travel', 'road_trips', 'assets/images/gallery/road_trip_highway.jpg', 'Long distance travel across India with All India Permit.', 5, 1),
        (6, 'Premium Car Interior', 'luxury_cars', 'assets/images/gallery/luxury_car_interior.jpg', 'Clean, comfortable and well-maintained passenger cabin.', 6, 1),
        (7, 'Scenic Mountain Route', 'road_trips', 'assets/images/gallery/scenic_mountain_drive.jpg', 'Reliable and safe hill station journeys with experienced drivers.', 7, 1),
        (8, 'Group Vacation Bus', 'bus', 'assets/images/gallery/tourist_group_bus.jpg', 'Comfortable group transit for families and corporate teams.', 8, 1);");

    $initialized = true;
}
