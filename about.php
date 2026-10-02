<?php
/**
 * JY TOUR and TRAVELS - About Us Page
 */
$pageTitle = 'About Us | JY TOUR and TRAVELS - Lucknow Car Rental & Tourist Transport';
$pageDesc  = 'Learn about JY TOUR and TRAVELS in Lucknow. Providing reliable passenger vehicles including Dzire, Aura, Ertiga, Toyota Innova Crysta, and Luxury Bus with All India Permit.';
$pageKeywords = 'about JY tour and travels, car rental Lucknow, tourist transport Lucknow, all india permit travels Lucknow';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Page Header Banner -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-route me-1"></i> <?= e(get_setting('all_india_permit_badge')); ?></span>
        <h1 class="display-5 fw-bold text-white mb-2">About JY TOUR and TRAVELS</h1>
        <p class="text-white-50 lead mb-0">Reliable Travel & Car Rental Services in Lucknow, Uttar Pradesh</p>
    </div>
</div>

<section class="section-py">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="section-eyebrow">Who We Are</span>
                <h2 class="section-title">Delivering Safe, Reliable & Comfortable Travel</h2>

                <p class="lead text-secondary mt-3">
                    <?= e(get_setting('about_intro', 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.')); ?>
                </p>

                <p class="text-muted">
                    Based at <strong>8/273 Rajni Khand, Sharda Nagar, Lucknow</strong>, we specialize in offering dependable passenger vehicles with valid <strong>All India Permit</strong> authorization. Whether you are scheduling a business trip, a family vacation, airport transit, or an interstate pilgrimage tour, our fleet is ready to serve.
                </p>

                <div class="row g-3 mt-3">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-success">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-car text-success me-1"></i> Multiple Fleet</h5>
                            <p class="small text-muted mb-0">Dzire, Aura, Ertiga, Innova Crysta, and Luxury Bus.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-warning">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-shield-halved text-warning me-1"></i> All India Permit</h5>
                            <p class="small text-muted mb-0">Legally authorized interstate passenger permits.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-warning">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-user-tie text-warning me-1"></i> Professional Drivers</h5>
                            <p class="small text-muted mb-0">Experienced chauffeurs for smooth highway driving.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-success">
                            <h5 class="fw-bold mb-1"><i class="fa-solid fa-headset text-success me-1"></i> Fast Support</h5>
                            <p class="small text-muted mb-0">Instant phone and WhatsApp assistance anytime.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 d-flex gap-3">
                    <a href="<?= e(BASE_URL); ?>/booking.php" class="btn-jy-gold">
                        <i class="fa-solid fa-calendar-check"></i> Book a Vehicle
                    </a>
                    <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn btn-outline-dark px-4 py-3 fw-bold rounded-3">
                        <i class="fa-solid fa-phone me-1"></i> Call +91 9450150697
                    </a>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-ad-preview">
                    <img src="<?= e(BASE_URL); ?>/assets/images/ad_reference.jpg" alt="JY TOUR and TRAVELS Advertisement" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values -->
<section class="section-py section-bg-light">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Our Focus</span>
            <h2 class="section-title">Passenger Transportation Highlights</h2>
            <p class="section-subtitle">Dedicated to delivering punctual and hassle-free vehicle rental in Lucknow and beyond.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="mb-3 text-success fs-1"><i class="fa-solid fa-map-location-dot"></i></div>
                    <h4 class="fw-bold">Intercity & Local Routes</h4>
                    <p class="text-muted">Providing vehicles for local errands in Lucknow as well as long-distance outstation travel to Varanasi, Ayodhya, Prayagraj, Delhi, Agra, and across India.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="mb-3 text-warning fs-1"><i class="fa-solid fa-people-roof"></i></div>
                    <h4 class="fw-bold">Individual & Group Travel</h4>
                    <p class="text-muted">Flexible mobility options accommodating single executive passengers, nuclear families, joint families, wedding guest groups, and corporate delegations.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                    <div class="mb-3 text-success fs-1"><i class="fa-solid fa-certificate"></i></div>
                    <h4 class="fw-bold">Authorized All India Permit</h4>
                    <p class="text-muted">Travel with total compliance and peace of mind. Every vehicle in our commercial fleet possesses authorized nationwide permits and current safety fitness.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
