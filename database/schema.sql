-- JY TOUR and TRAVELS - Database Schema
-- Production Ready SQL Setup for PHP 8+ / MySQL 8.0 / MariaDB 10.4+
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- 1. Table: admins
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(120) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'superadmin',
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 2. Table: vehicle_categories
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vehicle_categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon_svg` VARCHAR(100) DEFAULT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 3. Table: vehicles
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NULL,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `category_name` VARCHAR(100) NOT NULL,
  `tagline` VARCHAR(255) DEFAULT NULL,
  `image_url` VARCHAR(255) NOT NULL,
  `passenger_capacity` VARCHAR(50) DEFAULT 'Configurable',
  `luggage_capacity` VARCHAR(50) DEFAULT 'Configurable',
  `ac_type` VARCHAR(50) DEFAULT 'Dual Zone AC / Air Conditioned',
  `fuel_type` VARCHAR(50) DEFAULT 'Diesel / Petrol',
  `pricing_display` VARCHAR(100) DEFAULT 'Best Rates on Call',
  `all_india_permit` TINYINT(1) NOT NULL DEFAULT 1,
  `description` TEXT DEFAULT NULL,
  `features` TEXT DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `display_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `vehicle_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 4. Table: services
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `category_code` VARCHAR(50) NOT NULL,
  `icon_file` VARCHAR(100) NOT NULL,
  `short_desc` TEXT NOT NULL,
  `full_desc` TEXT DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 5. Table: bookings
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `booking_number` VARCHAR(30) NOT NULL UNIQUE,
  `full_name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `email` VARCHAR(120) DEFAULT NULL,
  `pickup_location` VARCHAR(255) NOT NULL,
  `destination` VARCHAR(255) NOT NULL,
  `travel_date` DATE NOT NULL,
  `return_date` DATE DEFAULT NULL,
  `vehicle_id` INT UNSIGNED NULL,
  `vehicle_name` VARCHAR(100) NOT NULL,
  `passengers` INT DEFAULT 1,
  `trip_type` ENUM('One Way', 'Round Trip', 'Local', 'Outstation') NOT NULL DEFAULT 'Outstation',
  `additional_requirements` TEXT DEFAULT NULL,
  `status` ENUM('New', 'Contacted', 'Confirmed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'New',
  `admin_notes` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 6. Table: contact_messages
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `email` VARCHAR(120) DEFAULT NULL,
  `subject` VARCHAR(200) DEFAULT NULL,
  `message` TEXT NOT NULL,
  `status` ENUM('New', 'Read', 'Replied') NOT NULL DEFAULT 'New',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 7. Table: gallery
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` ENUM('vehicles', 'travel', 'luxury_cars', 'bus', 'road_trips') NOT NULL DEFAULT 'vehicles',
  `image_url` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `display_order` INT NOT NULL DEFAULT 0,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- 8. Table: website_settings
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `website_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(80) NOT NULL UNIQUE,
  `setting_value` TEXT DEFAULT NULL,
  `setting_group` VARCHAR(50) NOT NULL DEFAULT 'general',
  `display_label` VARCHAR(120) NOT NULL,
  `field_type` VARCHAR(30) NOT NULL DEFAULT 'text',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- INITIAL SEED DATA
-- -------------------------------------------------------------

-- Admin User: admin / Admin@JY2026#Secure
INSERT INTO `admins` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `status`) VALUES
(1, 'admin', 'jytourandtravels32@gmail.com', '$2b$10$nQ0PEpbpiGHJob/ZAAWlZOSBSZCyefOHisl1zBDZlY4KbgZpCtsc6', 'JY Administrator', 'superadmin', 1)
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Vehicle Categories (5 matching reference ad)
INSERT INTO `vehicle_categories` (`id`, `name`, `slug`, `icon_svg`, `short_desc`, `display_order`, `status`) VALUES
(1, 'Luxury Sedan', 'luxury-sedan', 'luxury-sedan.svg', 'Comfortable, fuel-efficient and executive travel with spacious seating.', 1, 1),
(2, 'Luxury MUV', 'luxury-muv', 'luxury-muv.svg', 'Multi-utility vehicles engineered for smooth family and group journeys.', 2, 1),
(3, 'Luxury SUV', 'luxury-suv', 'luxury-suv.svg', 'Commanding road presence, superior comfort and robust outstation capability.', 3, 1),
(4, 'Luxury Bus', 'luxury-bus', 'luxury-bus.svg', 'Heavy passenger capacity luxury buses for tours, groups and events.', 4, 1),
(5, 'Luxury Hatch Back', 'luxury-hatch-back', 'luxury-hatchback.svg', 'Agile and economical rides suitable for convenient city and regional travel.', 5, 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Vehicles (Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus)
INSERT INTO `vehicles` (`id`, `category_id`, `name`, `slug`, `category_name`, `tagline`, `image_url`, `passenger_capacity`, `luggage_capacity`, `ac_type`, `fuel_type`, `pricing_display`, `all_india_permit`, `description`, `features`, `is_featured`, `display_order`, `status`) VALUES
(1, 1, 'Dzire', 'dzire', 'Luxury Sedan', 'Premium Sedan for Executive and Family Travel', 'assets/images/vehicles/sedan_dzire.jpg', '4 + 1 Passengers', '2 Bags', 'Climate Control AC', 'Petrol / Diesel', 'Affordable Tariff on Call', 1, 'Maruti Suzuki Dzire delivers a refined sedan journey with plush upholstery, impressive legroom, and effortless highway cruising across India.', 'All India Permit, Air Conditioned, Pushback Seats, Bluetooth Audio, Professional Chauffeur, Sanitized Cabin', 1, 1, 1),

(2, 1, 'Aura', 'aura', 'Luxury Sedan / Hatch Back', 'Modern Compact Sedan with Smooth Ride Quality', 'assets/images/vehicles/aura_sedan.jpg', '4 + 1 Passengers', '2 Bags', 'Powerful AC', 'Petrol / CNG', 'Competitive Rates on Call', 1, 'Hyundai Aura is known for its contemporary design, comfortable rear seats, and smooth suspension, making both city drives and outstation tours pleasant.', 'All India Permit, Full Air Conditioning, Modern Sound System, Ample Boot Space, Experienced Driver, Clean Interior', 1, 2, 1),

(3, 2, 'Ertiga', 'ertiga', 'Luxury MUV', 'Versatile and Spacious 7-Seater Family MUV', 'assets/images/vehicles/ertiga_muv.jpg', '6 + 1 Passengers', '3 Bags', 'Front & Rear AC', 'Diesel / Hybrid', 'Best Group Rates on Call', 1, 'Maruti Suzuki Ertiga is the ideal choice for family vacations and corporate outings, offering flexible 3-row seating and dedicated rear air-conditioning vents.', 'All India Permit, 3-Row Air Conditioning, Reclining Seats, Large Luggage Room, Fast-Tag Enabled, Verified Driver', 1, 3, 1),

(4, 3, 'Toyota Innova Crysta', 'toyota-innova-crysta', 'Luxury SUV / Premium MUV', 'The Gold Standard of Premium Highway Travel', 'assets/images/vehicles/innova_crysta_crop.jpg', '7 + 1 Passengers', '4 Bags', 'Dual Automatic Climate Control', 'Diesel', 'Premium Quality at Fair Rates', 1, 'Toyota Innova Crysta represents royal travel comfort with unmatched safety, captains chair seating options, and silent high-speed cruising with All India Permit.', 'All India Permit, Captain Seats, Automatic Climate Control, Highway Cruiser, Mobile Charging Points, Safety Airbags', 1, 4, 1),

(5, 4, 'Luxury Bus', 'luxury-bus', 'Luxury Bus', 'Heavy Passenger Luxury Coach for Tours and Events', 'assets/images/vehicles/luxury_bus.jpg', '18 to 45+ Passengers', 'Dedicated Luggage Bay', 'High-Capacity Central AC', 'Diesel', 'Custom Tour Quotes on Call', 1, 'Our fleet of luxury coaches and buses is designed for group pilgrimage, destination weddings, school/college excursions, and intercity corporate tours across India.', 'All India Permit, Air Suspension, Pushback Recliners, Central Audio/Video, Overhead Storage, Professional Tour Driver', 1, 5, 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Services (5 Categories matching reference ad)
INSERT INTO `services` (`id`, `name`, `slug`, `category_code`, `icon_file`, `short_desc`, `full_desc`, `image_url`, `display_order`, `status`) VALUES
(1, 'Luxury Sedan', 'luxury-sedan', 'sedan', 'luxury-sedan.svg', 'Executive sedan travel featuring vehicles like Dzire and Aura for corporate, airport transfers and outstation journeys.', 'Experience quiet rides and smooth journeys with our Luxury Sedan service. Equipped with professional chauffeurs and All India Permit.', 'assets/images/vehicles/sedan_dzire.jpg', 1, 1),

(2, 'Luxury MUV', 'luxury-muv', 'muv', 'luxury-muv.svg', 'Multi-utility vehicle service including Ertiga for comfortable family trips, group tours and weekend getaways.', 'Our Luxury MUV service provides superior legroom, luggage capacity and flexibility for families and medium-sized groups traveling across India.', 'assets/images/vehicles/ertiga_muv.jpg', 2, 1),

(3, 'Luxury SUV', 'luxury-suv', 'suv', 'luxury-suv.svg', 'High-end SUV comfort with Toyota Innova Crysta for long-distance highway travel, VIP tours and holiday tours.', 'Step up to highest comfort with our flagship Toyota Innova Crysta fleet. Unmatched ride stability and premier seating with All India Permit.', 'assets/images/vehicles/innova_crysta_crop.jpg', 3, 1),

(4, 'Luxury Bus', 'luxury-bus', 'bus', 'luxury-bus.svg', 'Spacious luxury buses and coaches for large group tours, pilgrimage circuits, destination weddings and group travel.', 'Equipped with pushback seats, air suspension, and experienced long-haul drivers to make group transportation seamless and secure.', 'assets/images/vehicles/luxury_bus.jpg', 4, 1),

(5, 'Luxury Hatch Back', 'luxury-hatch-back', 'hatchback', 'luxury-hatchback.svg', 'Economical and agile compact vehicle options for budget-friendly city navigation and regional travel.', 'Ideal for quick errands, station pickups, and cost-effective individual transportation without compromising on comfort and safety.', 'assets/images/vehicles/aura_sedan.jpg', 5, 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Gallery Items
INSERT INTO `gallery` (`id`, `title`, `category`, `image_url`, `caption`, `display_order`, `is_featured`) VALUES
(1, 'Toyota Innova Crysta Fleet', 'luxury_cars', 'assets/images/vehicles/innova_crysta_crop.jpg', 'Our flagship Toyota Innova Crysta ready for outstation journeys.', 1, 1),
(2, 'Luxury Tour Bus for Groups', 'bus', 'assets/images/vehicles/luxury_bus.jpg', 'Air-conditioned luxury coach for group expeditions.', 2, 1),
(3, 'Executive Dzire Sedan', 'vehicles', 'assets/images/vehicles/sedan_dzire.jpg', 'Spacious and sanitized Dzire sedan for comfortable transit.', 3, 1),
(4, 'Lucknow Heritage Tour', 'travel', 'assets/images/gallery/lucknow_rumi.jpg', 'Local sightseeing and historical monument tours in Lucknow.', 4, 1),
(5, 'National Highway Travel', 'road_trips', 'assets/images/gallery/road_trip_highway.jpg', 'Long distance travel across India with All India Permit.', 5, 1),
(6, 'Premium Car Interior', 'luxury_cars', 'assets/images/gallery/luxury_car_interior.jpg', 'Clean, comfortable and well-maintained passenger cabin.', 6, 1),
(7, 'Scenic Mountain Route', 'road_trips', 'assets/images/gallery/scenic_mountain_drive.jpg', 'Reliable and safe hill station journeys with experienced drivers.', 7, 1),
(8, 'Group Vacation Bus', 'bus', 'assets/images/gallery/tourist_group_bus.jpg', 'Comfortable group transit for families and corporate teams.', 8, 1),
(9, 'Agra & Golden Triangle Tour', 'travel', 'assets/images/gallery/agra_taj_trip.jpg', 'Custom outstation tour packages originating from Lucknow.', 9, 1),
(10, 'Family Vacation Journeys', 'travel', 'assets/images/gallery/family_vacation.jpg', 'Safe and reliable transportation for family vacations.', 10, 1)
ON DUPLICATE KEY UPDATE `title`=`title`;

-- Website Settings
INSERT INTO `website_settings` (`setting_key`, `setting_value`, `setting_group`, `display_label`, `field_type`) VALUES
('business_name', 'JY TOUR and TRAVELS', 'general', 'Business Name', 'text'),
('tagline', 'Reliable Travel & Car Rental Services in Lucknow', 'general', 'Tagline', 'text'),
('phone', '+91 9450150697', 'contact', 'Phone Number', 'text'),
('phone_raw', '9450150697', 'contact', 'Phone Number (Raw Digits)', 'text'),
('email', 'jytourandtravels32@gmail.com', 'contact', 'Email Address', 'email'),
('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India', 'contact', 'Office Address', 'textarea'),
('address_short', '8/273 Rajni Khand, Sharda Nagar, Lucknow', 'contact', 'Short Address', 'text'),
('city', 'Lucknow', 'contact', 'City', 'text'),
('state', 'Uttar Pradesh', 'contact', 'State', 'text'),
('country', 'India', 'contact', 'Country', 'text'),
('whatsapp_number', '+919450150697', 'social', 'WhatsApp Number (with country code)', 'text'),
('whatsapp_default_message', 'Hello JY TOUR and TRAVELS, I would like to enquire about vehicle booking.', 'social', 'WhatsApp Default Message', 'textarea'),
('all_india_permit_badge', 'WITH ALL INDIA PERMIT', 'hero', 'Permit Badge Text', 'text'),
('hero_headline', 'Reliable Travel & Car Rental Services in Lucknow', 'hero', 'Hero Section Headline', 'text'),
('hero_supporting_text', 'Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus', 'hero', 'Hero Supporting Vehicle Text', 'text'),
('hero_cta_primary', 'Book Now', 'hero', 'Hero Primary CTA Text', 'text'),
('hero_cta_secondary', 'Call +91 9450150697', 'hero', 'Hero Secondary CTA Text', 'text'),
('about_intro', 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.', 'about', 'About Section Text', 'textarea'),
('permit_banner_title', 'TRAVEL ACROSS INDIA WITH JY TOUR AND TRAVELS', 'banner', 'Permit Banner Title', 'text'),
('permit_banner_highlight', 'ALL INDIA PERMIT', 'banner', 'Permit Banner Highlighted Word', 'text'),
('permit_banner_desc', 'Book your preferred vehicle for your travel requirements with complete legal authorization and interstate permits.', 'banner', 'Permit Banner Subtitle', 'text'),
('business_hours', '24x7 Booking & Customer Support Available', 'contact', 'Business / Support Hours', 'text'),
('google_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14245.549248744577!2d80.916892!3d26.779774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x399bf9f96b99b5a7%3A0xbce962141505c8a!2sRajni%20Khand%2C%20Sharda%20Nagar%2C%20Lucknow%2C%20Uttar%20Pradesh%20226002!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin', 'contact', 'Google Maps Embed URL', 'textarea'),
('meta_title', 'JY TOUR and TRAVELS | Reliable Travel & Car Rental Services in Lucknow', 'seo', 'Default SEO Meta Title', 'text'),
('meta_description', 'JY TOUR and TRAVELS in Lucknow offers reliable car rental and tourist transportation with All India Permit. Dzire, Aura, Ertiga, Toyota Innova Crysta and Luxury Bus.', 'seo', 'Default SEO Meta Description', 'textarea'),
('meta_keywords', 'JY Tour and Travels Lucknow, car rental Lucknow, tour and travels Lucknow, luxury car rental Lucknow, Toyota Innova Crysta rental Lucknow, luxury bus rental Lucknow, outstation cab Lucknow, All India permit taxi Lucknow', 'seo', 'Default SEO Meta Keywords', 'textarea')
ON DUPLICATE KEY UPDATE `setting_value`=VALUES(`setting_value`);

SET FOREIGN_KEY_CHECKS = 1;
