# JY TOUR and TRAVELS - Official Dynamic Web Platform

A complete, modern, responsive, and professional **Tour & Travels / Car Rental** website and content management system developed in **PHP 8+ & MySQL**, custom-designed strictly around the official advertisement visual branding of **JY TOUR and TRAVELS (Lucknow)**.

---

## 🏢 Business Identity & Specifications

- **Business Name:** JY TOUR and TRAVELS
- **Location:** 8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India
- **Phone:** +91 9450150697
- **Email:** jytourandtravels32@gmail.com
- **Primary Message & Hallmark:** **"WITH ALL INDIA PERMIT"**
- **Available Vehicles:**
  1. **Dzire** (Luxury Sedan)
  2. **Aura** (Luxury Sedan / Hatch Back)
  3. **Ertiga** (Luxury MUV)
  4. **Toyota Innova Crysta** (Luxury SUV / Premium MUV)
  5. **Luxury Bus** (Luxury Tour Coach)
- **Services Offered:**
  1. Luxury Sedan
  2. Luxury MUV
  3. Luxury SUV
  4. Luxury Bus
  5. Luxury Hatch Back

---

## 🎨 Visual Identity & Color Palette

Faithfully crafted after the reference advertisement banner:
- **Dark Forest Green** (`#074e28`, `#04361c`) — Dominant brand primary background and authority color.
- **Bright Gold / Yellow** (`#ffd700`, `#f5a623`) — Accent lines, CTA buttons, permit badge highlights, and airplane swoosh.
- **Pure White** (`#ffffff`) — Typography and card elevations.
- **Deep Slate / Black** (`#111827`) — High contrast typography.
- **Light Sky-Blue** (`#f0f7fd`) — Soft road and travel accents.
- **Official Brand Mark:** Vector SVG logo featuring the iconic circular airplane swoosh looping around "JY" with bold "TOUR and TRAVELS".

---

## 🚀 Key Features

### 1. Frontend Customer Experience
- **Sticky Responsive Navigation:** Top bar with Permit badge, location, phone, WhatsApp and quick book button.
- **Hero Section:** High-impact banner featuring the Toyota Innova Crysta showcase, "WITH ALL INDIA PERMIT" highlight badge, direct calling CTA, and instant booking button.
- **About Us Section:** Clear explanation of passenger transit services for local and outstation travel without unsupported claims, incorporating the framed reference ad.
- **Vehicle Fleet Section:** 5 dedicated cards for **Dzire, Aura, Ertiga, Toyota Innova Crysta, and Luxury Bus** with passenger capacity, luggage capacity, AC status, and fuel type.
- **Service Categories Section:** Visual cards with bespoke vector silhouette icons for Sedan, MUV, SUV, Bus, and Hatchback.
- **Why Choose Us Section:** 6 service-oriented advantages (Wide Range of Vehicles, All India Permit, Convenient Booking, Individual & Group Travel, Multiple Categories, Customer-Focused).
- **All India Permit Promotional Banner:** Full-width high-contrast gold & green promotional ribbon.
- **Interactive Booking / Enquiry Form:** Full Name, Phone, Email, Pickup, Destination, Dates, Vehicle selection, Passengers, Trip Type, and Notes. Submits with real-time feedback via AJAX and stores securely in MySQL.
- **Quick Contact Section:** Large direct action buttons for **Call Now (+91 9450150697)**, **Email Us (jytourandtravels32@gmail.com)**, and **Instant WhatsApp**.
- **Responsive Photo Gallery:** Filterable by Vehicles, Luxury Cars, Luxury Bus, Travel, and Road Trips, with modal lightbox preview.
- **Contact Page:** Includes official address, phone, email, Google Maps iframe embed, configurable business hours, and interactive contact message form.
- **Mobile Floating Action Bar:** Fixed bottom bar on mobile screens with **Call | WhatsApp | Book Now**.

### 2. Administrator Management Panel (`/admin`)
- **Secure Authentication:** Password hashing using `password_hash()` and verified with `password_verify()`. CSRF protection and session regeneration.
- **Live Statistics Overview:** Total Bookings, New Inquiries, Active Fleet, and Contact Inquiries.
- **Booking Management:**
  - View full enquiry details (trip type, pickup, destination, dates, passengers, requirements, IP).
  - Status lifecycle management (**New, Contacted, Confirmed, Completed, Cancelled**).
  - Add internal administrative notes and driver assignment details.
  - Direct 1-click customer call and WhatsApp messaging buttons.
- **Fleet Management:**
  - Add, edit, delete vehicles.
  - Toggle vehicle visibility.
  - Image upload with validation (JPG, PNG, WebP) or select from existing vehicle assets.
- **Category Management:** Manage the 5 vehicle tiers.
- **Service Management:** Update short/full descriptions and service icons.
- **Gallery Management:** Upload new travel photos and assign category tags.
- **Website Settings & CMS:**
  - Edit Phone number, Email, Office address.
  - Update Hero headline, subtext, and Permit badge.
  - Configure Business / Support hours placeholder.
  - Set default WhatsApp message text.
  - Update Google Maps embed iframe URL.
  - Configure SEO Meta Title, Meta Description, and Search Keywords.
- **Password Management:** Secure password change utility for admin accounts.

---

## 🛠️ Technology Requirements

- **PHP:** 8.0 or newer (tested with PHP 8.1, 8.2, 8.3)
- **Database:** MySQL 5.7+ / 8.0+ or MariaDB 10.4+
- **PHP Extensions:** `pdo_mysql`, `session`, `mbstring`, `fileinfo`, `gd` (optional, for image processing)
- **Web Server:** Apache (with `mod_rewrite` and `mod_headers` enabled) or Nginx
- **Frontend Frameworks:** Bootstrap 5.3.3, Font Awesome 6.5.1, Google Fonts (Montserrat & Outfit)

---

## 📂 Project Structure

```
JY TOURS TRAVELS/
├── .htaccess                     # Apache clean URL rewrites, security headers, gzip & caching
├── robots.txt                    # Search crawler directives
├── sitemap.xml                   # XML sitemap for SEO
├── index.php                     # Dynamic Home page (all 12 required sections)
├── about.php                     # Dedicated About Us page
├── fleet.php                     # Dedicated Fleet listing with vehicle specs
├── services.php                  # Dedicated Services page (Sedan, MUV, SUV, Bus, Hatchback)
├── gallery.php                   # Dedicated Gallery with filter tabs & lightbox
├── booking.php                   # Dedicated Online Reservation page
├── contact.php                   # Dedicated Contact page with Google Map & form
├── process-booking.php           # Booking submission processor (Validation, PDO, Email)
├── process-contact.php           # Contact form processor (Validation, PDO, Email)
├── install.php                   # 1-Click Browser-based Database Installer & Setup Wizard
├── admin_credentials.php         # Initial setup credentials reference file
├── README.md                     # Complete project documentation
├── config/
│   ├── config.php                # App constants, BASE_URL auto-detection, settings cache
│   ├── database.php              # PDO MySQL connection handler
│   ├── functions.php             # CSRF token, sanitization, flash alerts, fallback data
│   └── auth.php                  # Admin session authentication guard
├── includes/
│   ├── header.php                # HTML5 head, SEO metadata, OpenGraph, Schema.org JSON-LD
│   ├── navbar.php                # Sticky header, logo, nav links, Call & WhatsApp CTAs
│   ├── footer.php                # Footer component with links, contacts, modal lightbox
│   └── mobile_cta.php            # Fixed bottom mobile action bar (Call, WhatsApp, Book)
├── database/
│   ├── schema.sql                # Complete SQL schema with seed data for all 8 tables
│   └── seed_data.php             # Standalone CLI database seeder
├── assets/
│   ├── css/
│   │   ├── style.css             # Main styling (Dark green & gold theme, responsive layouts)
│   │   └── admin.css             # Admin dashboard styling
│   ├── js/
│   │   ├── main.js               # Frontend interactions, AJAX booking, lightbox, filters
│   │   └── admin.js              # Admin interactions, delete confirmations, sidebar toggle
│   └── images/
│       ├── logo.svg              # Brand vector logo (yellow swoosh & plane)
│       ├── logo-white.svg        # White/gold logo variant for dark backgrounds
│       ├── ad_reference.jpg      # The original client advertisement
│       ├── icons/                # Category outline SVGs (Sedan, MUV, SUV, Bus, Hatchback)
│       ├── vehicles/             # Vehicle photography (Dzire, Aura, Ertiga, Innova, Bus)
│       └── gallery/              # Travel and scenic road trip photography
└── uploads/
    ├── vehicles/                 # Dynamic uploaded vehicle pictures
    └── gallery/                  # Dynamic uploaded gallery images
```

---

## ⚡ Installation & Setup Instructions

### Method 1: 1-Click Web Setup Wizard (Recommended)
1. Copy or extract this project folder to your web server document root:
   - For **XAMPP**: `C:\xampp\htdocs\jy-tours-travels`
   - For **WAMP**: `C:\wamp64\www\jy-tours-travels`
   - For **cPanel / Live Server**: Upload files into `public_html` (or subfolder).
2. Start your Apache and MySQL servers.
3. Open your browser and navigate to:
   ```
   http://localhost/jy-tours-travels/install.php
   ```
4. Enter your MySQL host (`127.0.0.1`), username (`root`), and password (leave blank for default XAMPP).
5. Click **"Install Database & Run Setup"**.
6. The installer will create the database `jy_tours_travels`, import all 8 tables, insert initial records, and configure `config/database.php`.

---

### Method 2: Manual Setup via phpMyAdmin / MySQL CLI
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Create a new database named:
   ```sql
   CREATE DATABASE jy_tours_travels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Click on the newly created database, go to the **Import** tab, choose the file `database/schema.sql`, and click **Go**.
4. If your MySQL credentials differ from the defaults (`root` with no password), update `config/database.php`:
   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_NAME', 'jy_tours_travels');
   define('DB_USER', 'your_mysql_username');
   define('DB_PASS', 'your_mysql_password');
   ```

---

## 🔐 Administrative Credentials

- **Admin Login URL:** `http://localhost/jy-tours-travels/admin/login.php`
- **Username:** `admin` *(or `jytourandtravels32@gmail.com`)*
- **Password:** `Admin@JY2026#Secure`

*(You can update this password at any time from **Change Password** in the admin sidebar).*

---

## 🛡️ Security Best Practices Implemented

- **Prepared SQL Statements:** Every database query utilizes PDO prepared statements with strict parameter binding to guard against SQL injection.
- **CSRF Defense:** All forms generate and verify cryptographic CSRF tokens using `hash_equals()`.
- **Input Sanitization & Output Escaping:** User inputs are sanitized on ingress and escaped via `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` before rendering to prevent Cross-Site Scripting (XSS).
- **Secure Authentication:** Admin passwords are encrypted using `password_hash()` (Bcrypt). Sessions are regenerated upon sign-in (`session_regenerate_id(true)`).
- **Upload Validation:** File uploads check file extensions, MIME types, and size limits (max 5MB).
- **Path Protection:** `.htaccess` blocks public web access to `.env`, `database/`, and `config/` files.

---

## 📈 SEO & Search Phrases Included

Pre-configured with meta tags and Schema.org structured data for target search terms:
- `JY Tour and Travels Lucknow`
- `car rental Lucknow`
- `tour and travels Lucknow`
- `luxury car rental Lucknow`
- `Toyota Innova Crysta rental Lucknow`
- `luxury bus rental Lucknow`
- `outstation cab Lucknow`
- `All India permit taxi Lucknow`

---

## 📞 Support & Contacts

For any modifications, custom route tariffs, or fleet inquiries:
- **Phone:** +91 9450150697
- **Email:** jytourandtravels32@gmail.com
- **Address:** 8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India
