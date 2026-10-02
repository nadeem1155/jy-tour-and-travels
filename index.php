<?php
/**
 * JY TOUR and TRAVELS - Home Page
 * Complete, modern, responsive website based strictly on the provided advertisement
 */
$pageTitle = get_setting('meta_title', 'JY TOUR and TRAVELS | Reliable Travel & Car Rental Services in Lucknow');
$pageDesc  = get_setting('meta_description');
$pageKeywords = get_setting('meta_keywords');

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

// Fetch dynamic data with graceful fallback
$pdo = get_db_connection();
$vehicles = [];
$services = [];
$gallery = [];

if ($pdo) {
    try {
        $vStmt = $pdo->query("SELECT * FROM vehicles WHERE status = 1 ORDER BY display_order ASC, id ASC");
        $vehicles = $vStmt->fetchAll();

        $sStmt = $pdo->query("SELECT * FROM services WHERE status = 1 ORDER BY display_order ASC, id ASC");
        $services = $sStmt->fetchAll();

        $gStmt = $pdo->query("SELECT * FROM gallery WHERE is_featured = 1 ORDER BY display_order ASC, id ASC LIMIT 8");
        $gallery = $gStmt->fetchAll();
    } catch (Exception $e) {
        error_log("Home query error: " . $e->getMessage());
    }
}

if (empty($vehicles)) {
    $vehicles = get_fallback_vehicles();
}
if (empty($services)) {
    $services = get_fallback_services();
}
if (empty($gallery)) {
    $gallery = get_fallback_gallery();
}

$flash = get_flash();
?>

<?php if ($flash): ?>
<div class="container mt-3">
    <div class="alert alert-jy-<?= e($flash['type']); ?> alert-dismissible fade show shadow-sm" role="alert">
        <?= $flash['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php endif; ?>

<!-- ==========================================================================
     1. HERO SECTION (Inspired strictly by the reference advertisement)
     ========================================================================== -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Hero Left Content -->
            <div class="col-lg-6">
                <div class="hero-badge-wrap">
                    <span class="hero-permit-pill">
                        <i class="fa-solid fa-route text-warning"></i>
                        <span><?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?></span>
                    </span>
                </div>

                <h1 class="hero-headline">
                    <?= e(get_setting('hero_headline', 'Reliable Travel & Car Rental Services in Lucknow')); ?>
                </h1>

                <p class="hero-supporting-text">
                    <i class="fa-solid fa-car-side text-warning me-2"></i>
                    <?= e(get_setting('hero_supporting_text', 'Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus')); ?>
                </p>

                <div class="hero-permit-banner-highlight">
                    <i class="fa-solid fa-shield-check me-2"></i>
                    <?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?>
                </div>

                <p class="text-secondary mb-4 fs-6">
                    Providing dependable passenger vehicles for local sightseeing, outstation tours, family trips, and corporate travel from Lucknow across India.
                </p>

                <div class="hero-cta-group">
                    <a href="#booking" class="btn-jy-gold">
                        <i class="fa-solid fa-calendar-check"></i>
                        <span><?= e(get_setting('hero_cta_primary', 'Book Now')); ?></span>
                    </a>
                    <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn-jy-outline">
                        <i class="fa-solid fa-phone"></i>
                        <span><?= e(get_setting('hero_cta_secondary', 'Call +91 9450150697')); ?></span>
                    </a>
                </div>

                <div class="d-flex align-items-center gap-4 mt-4 pt-2 text-muted small">
                    <span class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-check text-success fs-6"></i> All India Permit
                    </span>
                    <span class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-check text-success fs-6"></i> Sanitized Fleet
                    </span>
                    <span class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-check text-success fs-6"></i> Verified Chauffeurs
                    </span>
                </div>
            </div>

            <!-- Hero Right Visual (Innova Crysta Featured Display) -->
            <div class="col-lg-6">
                <div class="hero-image-wrap">
                    <div class="hero-card-display">
                        <img src="<?= e(BASE_URL); ?>/assets/images/vehicles/innova_crysta_crop.jpg" alt="Toyota Innova Crysta - JY Tour and Travels" class="img-fluid" width="600" height="350">
                        <div class="hero-floating-badge d-none d-sm-block">
                            <div class="title"><i class="fa-solid fa-medal text-warning me-1"></i> Toyota Innova Crysta</div>
                            <div class="sub">Luxury SUV / Premium MUV • With All India Permit</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     QUICK CONTACT STRIP
     ========================================================================== -->
<section class="quick-contact-ribbon">
    <div class="container">
        <div class="row g-3 justify-content-between align-items-center">
            <div class="col-md-4 col-12">
                <div class="ribbon-item">
                    <div class="ribbon-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="ribbon-content">
                        <div class="label">Location</div>
                        <div class="value"><?= e(get_setting('address_short', '8/273 Rajni Khand, Sharda Nagar, Lucknow')); ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="ribbon-item">
                    <div class="ribbon-icon"><i class="fa-solid fa-phone"></i></div>
                    <div class="ribbon-content">
                        <div class="label">Call Now</div>
                        <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="value"><?= e(get_setting('phone', '+91 9450150697')); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <div class="ribbon-item">
                    <div class="ribbon-icon"><i class="fa-solid fa-envelope"></i></div>
                    <div class="ribbon-content">
                        <div class="label">Email Us</div>
                        <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>" class="value"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     2. ABOUT SECTION
     ========================================================================== -->
<section class="section-py" id="about">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="section-eyebrow">About JY TOUR and TRAVELS</div>
                <h2 class="section-title">
                    Comfortable & Convenient Passenger Transportation
                </h2>
                
                <p class="lead text-secondary mt-3">
                    <?= e(get_setting('about_intro', 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.')); ?>
                </p>

                <p class="text-muted">
                    Headquartered at <strong>8/273 Rajni Khand, Sharda Nagar, Lucknow</strong>, our transportation services are structured to cater to families, corporate teams, individual travelers, and pilgrimage groups with authorized <strong>All India Permit</strong> vehicles.
                </p>

                <div class="mt-4">
                    <div class="about-feature-box">
                        <i class="fa-solid fa-car-tunnel"></i>
                        <div>
                            <strong class="d-block text-dark">Multiple Vehicle Options</strong>
                            <span class="text-muted small">Sedans, MUVs, SUVs, and high-capacity luxury buses ready for every group size.</span>
                        </div>
                    </div>

                    <div class="about-feature-box">
                        <i class="fa-solid fa-couch"></i>
                        <div>
                            <strong class="d-block text-dark">Comfortable Travel</strong>
                            <span class="text-muted small">Well-maintained air-conditioned vehicles with comfortable seating and ample luggage space.</span>
                        </div>
                    </div>

                    <div class="about-feature-box">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <div>
                            <strong class="d-block text-dark">All India Permit</strong>
                            <span class="text-muted small">Authorized documentation and interstate permits for smooth cross-border travel anywhere across India.</span>
                        </div>
                    </div>

                    <div class="about-feature-box">
                        <i class="fa-solid fa-headset"></i>
                        <div>
                            <strong class="d-block text-dark">Easy Booking & Contact</strong>
                            <span class="text-muted small">Instant phone support, direct WhatsApp assistance, and transparent online enquiry.</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-3">
                    <a href="#fleet" class="btn-jy-primary">
                        <i class="fa-solid fa-car"></i> Explore Fleet
                    </a>
                    <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success px-4 py-3 fw-bold rounded-3">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Us
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Visual Advertisement Reference Card -->
                <div class="about-ad-preview">
                    <img src="<?= e(BASE_URL); ?>/assets/images/ad_reference.jpg" alt="JY TOUR and TRAVELS Official Advertisement" class="img-fluid">
                </div>
                <div class="text-center mt-3 text-muted small">
                    <i class="fa-solid fa-certificate text-warning me-1"></i> Official Advertisement Reference • JY TOUR and TRAVELS, Lucknow
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     3. VEHICLE / FLEET SECTION (Dzire, Aura, Ertiga, Innova Crysta, Luxury Bus)
     ========================================================================== -->
<section class="section-py section-bg-light" id="fleet">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Our Vehicles</span>
            <h2 class="section-title">Vehicles Available for Hire</h2>
            <p class="section-subtitle">
                Choose from our well-maintained fleet featuring top sedans, family MUVs, luxury SUVs, and spacious buses—all backed by All India Permit.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($vehicles as $v): ?>
            <div class="col-lg-4 col-md-6">
                <div class="vehicle-card">
                    <div class="vehicle-img-wrap">
                        <img src="<?= e(BASE_URL . '/' . $v['image_url']); ?>" alt="<?= e($v['name']); ?> - JY Tour and Travels" loading="lazy">
                        <span class="vehicle-permit-tag">
                            <i class="fa-solid fa-shield"></i> All India Permit
                        </span>
                        <span class="vehicle-category-badge">
                            <?= e($v['category_name']); ?>
                        </span>
                    </div>

                    <div class="vehicle-body">
                        <h3 class="vehicle-title"><?= e($v['name']); ?></h3>
                        <div class="vehicle-tagline"><?= e($v['tagline'] ?? $v['category_name']); ?></div>
                        <p class="vehicle-desc"><?= e($v['description']); ?></p>

                        <div class="vehicle-specs">
                            <div class="spec-item">
                                <i class="fa-solid fa-users"></i>
                                <span><?= e($v['passenger_capacity']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fa-solid fa-suitcase"></i>
                                <span><?= e($v['luggage_capacity']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fa-solid fa-snowflake"></i>
                                <span><?= e($v['ac_type']); ?></span>
                            </div>
                            <div class="spec-item">
                                <i class="fa-solid fa-gas-pump"></i>
                                <span><?= e($v['fuel_type']); ?></span>
                            </div>
                        </div>

                        <div class="vehicle-actions">
                            <a href="#booking" class="btn-card-book select-vehicle-btn" data-vehicle="<?= e($v['name']); ?>">
                                <i class="fa-solid fa-calendar-check me-1"></i> Book Now
                            </a>
                            <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn-card-call">
                                <i class="fa-solid fa-phone me-1"></i> Call Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==========================================================================
     4. SERVICES SECTION (5 Categories with Icons)
     ========================================================================== -->
<section class="section-py" id="services">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Service Offered</span>
            <h2 class="section-title">Our Service Categories</h2>
            <p class="section-subtitle">
                Comprehensive passenger mobility solutions tailored to individuals, corporate travelers, family vacations, and tour groups.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($services as $srv): ?>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="service-card">
                    <div class="service-icon-box">
                        <img src="<?= e(BASE_URL . '/assets/images/icons/' . ($srv['icon_file'] ?? 'luxury-sedan.svg')); ?>" alt="<?= e($srv['name']); ?> Icon" width="70" height="40">
                    </div>
                    <h3 class="service-name"><?= e($srv['name']); ?></h3>
                    <p class="service-desc"><?= e($srv['short_desc']); ?></p>
                    <a href="#booking" class="btn-service-enquire select-vehicle-btn" data-vehicle="<?= e($srv['name']); ?>">
                        <span>Enquire for <?= e($srv['name']); ?></span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==========================================================================
     5. WHY CHOOSE US SECTION
     ========================================================================== -->
<section class="section-py section-bg-light" id="why-us">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Why Choose Us</span>
            <h2 class="section-title">Committed to Quality Travel Services</h2>
            <p class="section-subtitle">
                General service-oriented advantages delivering peace of mind on every journey.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-car-side"></i></div>
                    <h4 class="pillar-title">Wide Range of Vehicles</h4>
                    <p class="pillar-text">From economical sedans and versatile MUVs to luxury SUVs and heavy-passenger tour coaches, our fleet fulfills every travel scenario.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-id-card"></i></div>
                    <h4 class="pillar-title">All India Permit</h4>
                    <p class="pillar-text">All vehicles operate with authorized nationwide interstate permits, facilitating smooth transit across Indian states without boundary hassle.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-calendar-check"></i></div>
                    <h4 class="pillar-title">Convenient Booking</h4>
                    <p class="pillar-text">Quick reservations through our online enquiry form, direct phone call, or instant WhatsApp communication with prompt confirmation.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-people-group"></i></div>
                    <h4 class="pillar-title">Travel for Individuals & Groups</h4>
                    <p class="pillar-text">Whether you need an individual airport pickup, family vacation transportation, or coach bus for wedding guests, we accommodate your party.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-layer-group"></i></div>
                    <h4 class="pillar-title">Multiple Vehicle Categories</h4>
                    <p class="pillar-text">Choose from Luxury Sedan, Luxury MUV, Luxury SUV, Luxury Bus, and Luxury Hatch Back according to your comfort and group requirements.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-pillar-card">
                    <div class="pillar-icon-wrap"><i class="fa-solid fa-user-shield"></i></div>
                    <h4 class="pillar-title">Customer-Focused Service</h4>
                    <p class="pillar-text">Dedicated to providing clean, sanitized vehicles, polite experienced drivers, and dependable customer assistance throughout your journey.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     6. ALL INDIA PERMIT BANNER
     ========================================================================== -->
<section class="all-india-permit-banner text-center text-md-start">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="permit-banner-badge">
                    <i class="fa-solid fa-award me-1"></i> <?= e(get_setting('permit_banner_highlight', 'ALL INDIA PERMIT')); ?>
                </span>
                <h2 class="permit-banner-title">
                    <?= e(get_setting('permit_banner_title', 'TRAVEL ACROSS INDIA WITH JY TOUR AND TRAVELS')); ?>
                </h2>
                <p class="permit-banner-desc">
                    <?= e(get_setting('permit_banner_desc', 'Book your preferred vehicle for your travel requirements with authorized All India Permit.')); ?>
                </p>
            </div>
            <div class="col-lg-4 text-lg-end text-center">
                <a href="#booking" class="btn-jy-gold btn-lg px-4 py-3">
                    <i class="fa-solid fa-calendar-check me-2"></i> Book Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     7. BOOKING / ENQUIRY FORM SECTION
     ========================================================================== -->
<section class="section-py" id="booking">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Online Reservation</span>
            <h2 class="section-title">Book a Vehicle for Your Journey</h2>
            <p class="section-subtitle">
                Fill in your travel details below to submit your booking enquiry. Our team will verify availability and contact you promptly.
            </p>
        </div>

        <div class="booking-section-wrap">
            <div class="row g-0">
                <!-- Left Form Column -->
                <div class="col-lg-8">
                    <div class="booking-form-header">
                        <h3><i class="fa-solid fa-clipboard-list me-2"></i> Vehicle Booking Enquiry Form</h3>
                        <p class="mb-0 text-white-50 small">All India Permit • Local, Outstation & Intercity Rides</p>
                    </div>

                    <div class="booking-form-body">
                        <!-- AJAX Alert Box -->
                        <div id="bookingAlertBox" class="d-none"></div>

                        <form id="jyBookingForm" action="<?= e(BASE_URL); ?>/process-booking.php" method="POST">
                            <?= csrf_field(); ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter your full name" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 9450150697" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-muted small">(Optional)</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                </div>

                                <div class="col-md-6">
                                    <label for="vehicle_name" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="vehicle_name" name="vehicle_name" required>
                                        <option value="" disabled selected>-- Select Vehicle --</option>
                                        <option value="Dzire">Dzire (Luxury Sedan)</option>
                                        <option value="Aura">Aura (Luxury Sedan / Hatch Back)</option>
                                        <option value="Ertiga">Ertiga (Luxury MUV)</option>
                                        <option value="Toyota Innova Crysta">Toyota Innova Crysta (Luxury SUV / MUV)</option>
                                        <option value="Luxury Bus">Luxury Bus (Tour Coach)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="pickup_location" class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="e.g. Rajni Khand, Lucknow" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="destination" name="destination" placeholder="e.g. Varanasi, Ayodhya, Delhi, Outstation" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="travel_date" class="form-label">Travel Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="travel_date" name="travel_date" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="return_date" class="form-label">Return Date <span class="text-muted small">(For Round Trip)</span></label>
                                    <input type="date" class="form-control" id="return_date" name="return_date">
                                </div>

                                <div class="col-md-6">
                                    <label for="trip_type" class="form-label">Trip Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="trip_type" name="trip_type" required>
                                        <option value="Outstation" selected>Outstation</option>
                                        <option value="One Way">One Way</option>
                                        <option value="Round Trip">Round Trip</option>
                                        <option value="Local">Local Sightseeing / City</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="passengers" class="form-label">Number of Passengers <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="passengers" name="passengers" min="1" max="60" value="1" required>
                                </div>

                                <div class="col-12">
                                    <label for="additional_requirements" class="form-label">Additional Requirements</label>
                                    <textarea class="form-control" id="additional_requirements" name="additional_requirements" rows="3" placeholder="Specific pickup timing, luggage needs, route preferences, etc."></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-jy-gold w-100 py-3 justify-content-center">
                                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Enquiry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Side Information Column -->
                <div class="col-lg-4 p-4 p-lg-5 booking-side-info d-flex flex-column justify-content-between">
                    <div>
                        <h4><i class="fa-solid fa-circle-info text-warning me-2"></i> Booking Assistance</h4>
                        <p class="text-muted small">
                            Have immediate travel plans? You can reach our booking desk directly via phone or WhatsApp for instant quote and vehicle reservation.
                        </p>

                        <hr class="my-4">

                        <ul class="booking-contact-list">
                            <li>
                                <i class="fa-solid fa-phone"></i>
                                <div>
                                    <strong>Call Us Directly:</strong><br>
                                    <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="text-dark fw-bold"><?= e(get_setting('phone', '+91 9450150697')); ?></a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-brands fa-whatsapp text-success"></i>
                                <div>
                                    <strong>Instant WhatsApp:</strong><br>
                                    <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="text-success fw-bold">Chat with Booking Desk</a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-solid fa-envelope"></i>
                                <div>
                                    <strong>Email Inquiries:</strong><br>
                                    <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>" class="text-muted"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <strong>Office Location:</strong><br>
                                    <span class="text-muted"><?= e(get_setting('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India')); ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-4 p-3 bg-white rounded border border-warning">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-shield text-warning"></i>
                            <strong class="text-dark small"><?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?></strong>
                        </div>
                        <p class="text-muted mb-0 small">
                            All interstate journeys handled with valid toll, insurance, and nationwide passenger permits.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     8. QUICK CONTACT SECTION (Large CTAs)
     ========================================================================== -->
<section class="section-py section-bg-light" id="quick-contact">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Get in Touch</span>
            <h2 class="section-title">Quick Contact Options</h2>
            <p class="section-subtitle">Connect with JY TOUR and TRAVELS through your preferred communication channel.</p>
        </div>

        <div class="row g-4">
            <!-- Call Now Button Card -->
            <div class="col-md-4">
                <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="card text-center p-4 border-0 shadow-sm h-100 text-decoration-none text-dark hover-scale">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(7, 78, 40, 0.1);">
                        <i class="fa-solid fa-phone fs-2 text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-1">CALL NOW</h4>
                    <p class="text-muted mb-3">Speak directly with our transport desk</p>
                    <span class="btn btn-outline-success fw-bold rounded-pill"><?= e(get_setting('phone', '+91 9450150697')); ?></span>
                </a>
            </div>

            <!-- Email Us Card -->
            <div class="col-md-4">
                <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>" class="card text-center p-4 border-0 shadow-sm h-100 text-decoration-none text-dark hover-scale">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(255, 215, 0, 0.2);">
                        <i class="fa-solid fa-envelope fs-2 text-warning"></i>
                    </div>
                    <h4 class="fw-bold mb-1">EMAIL US</h4>
                    <p class="text-muted mb-3">Send tour packages or custom queries</p>
                    <span class="btn btn-outline-dark fw-bold rounded-pill text-truncate w-100"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></span>
                </a>
            </div>

            <!-- WhatsApp Card -->
            <div class="col-md-4">
                <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="card text-center p-4 border-0 shadow-sm h-100 text-decoration-none text-dark hover-scale">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px; background: rgba(37, 211, 102, 0.15);">
                        <i class="fa-brands fa-whatsapp fs-2 text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-1">WHATSAPP</h4>
                    <p class="text-muted mb-3">Instant booking assistance & route queries</p>
                    <span class="btn btn-success fw-bold rounded-pill">Start WhatsApp Chat</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================================================
     9. GALLERY SECTION
     ========================================================================== -->
<section class="section-py" id="gallery">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Photo Gallery</span>
            <h2 class="section-title">Vehicles & Travel Journeys</h2>
            <p class="section-subtitle">
                A glimpse of our well-maintained fleet, comfortable passenger interiors, and scenic highway travel moments.
            </p>
        </div>

        <!-- Filter Buttons -->
        <div class="gallery-nav">
            <button class="gallery-btn active" data-filter="all">All Photos</button>
            <button class="gallery-btn" data-filter="vehicles">Vehicles</button>
            <button class="gallery-btn" data-filter="luxury_cars">Luxury Cars</button>
            <button class="gallery-btn" data-filter="bus">Luxury Bus</button>
            <button class="gallery-btn" data-filter="travel">Travel</button>
            <button class="gallery-btn" data-filter="road_trips">Road Trips</button>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-3">
            <?php foreach ($gallery as $g): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 gallery-col" data-category="<?= e($g['category']); ?>">
                <div class="gallery-item" data-title="<?= e($g['title']); ?>" data-full="<?= e(BASE_URL . '/' . $g['image_url']); ?>">
                    <img src="<?= e(BASE_URL . '/' . $g['image_url']); ?>" alt="<?= e($g['title']); ?>" loading="lazy">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-badge"><?= e(str_replace('_', ' ', $g['category'])); ?></div>
                        <div class="gallery-overlay-title"><?= e($g['title']); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ==========================================================================
     10. CONTACT INFO & MAP SECTION
     ========================================================================== -->
<section class="section-py section-bg-light" id="contact">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Reach Out</span>
            <h2 class="section-title">Contact JY TOUR and TRAVELS</h2>
            <p class="section-subtitle">We are based in Lucknow and provide travel transportation with All India Permit.</p>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <h3 class="mb-4 text-dark font-weight-bold">Office Information</h3>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="contact-channel-details">
                            <h5>Business Location</h5>
                            <p><?= e(get_setting('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India')); ?></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-channel-details">
                            <h5>Phone Support</h5>
                            <p><a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>"><?= e(get_setting('phone', '+91 9450150697')); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-channel-details">
                            <h5>Email Inquiries</h5>
                            <p><a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-clock"></i></div>
                        <div class="contact-channel-details">
                            <h5>Business Hours</h5>
                            <p><?= e(get_setting('business_hours', '24x7 Booking & Customer Support Available')); ?></p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-2">
                        <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 py-2 flex-grow-1">
                            <i class="fa-solid fa-phone me-1"></i> Call Now
                        </a>
                        <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm rounded-pill px-3 py-2 flex-grow-1">
                            <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="map-container">
                    <iframe src="<?= e(get_setting('google_map_embed')); ?>" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="JY Tour and Travels Location"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
