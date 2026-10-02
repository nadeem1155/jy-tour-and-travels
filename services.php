<?php
/**
 * JY TOUR and TRAVELS - Services Page
 */
$pageTitle = 'Services | Luxury Sedan, MUV, SUV, Luxury Bus & Hatch Back | JY TOUR and TRAVELS';
$pageDesc  = 'Service offerings by JY TOUR and TRAVELS: Luxury Sedan, Luxury MUV, Luxury SUV, Luxury Bus, and Luxury Hatch Back with All India Permit from Lucknow.';
$pageKeywords = 'luxury sedan Lucknow, luxury MUV Lucknow, luxury SUV Lucknow, luxury bus Lucknow, luxury hatchback hire Lucknow';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$pdo = get_db_connection();
$services = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM services WHERE status = 1 ORDER BY display_order ASC, id ASC");
        $services = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Services query error: " . $e->getMessage());
    }
}
if (empty($services)) {
    $services = get_fallback_services();
}
?>

<!-- Services Hero Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-list-check me-1"></i> What We Offer</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Service Categories</h1>
        <p class="text-white-50 lead mb-0">Specialized Passenger Transport Solutions for Every Travel Need</p>
    </div>
</div>

<section class="section-py">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Service Offered</span>
            <h2 class="section-title">Five Core Vehicle Categories</h2>
            <p class="section-subtitle">
                As showcased in our official service directory, we offer 5 distinguished passenger travel categories with authorized All India Permit.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($services as $srv): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden service-page-card">
                    <?php if (!empty($srv['image_url'])): ?>
                    <div style="height: 180px; overflow: hidden; background: #0c1811;">
                        <img src="<?= e(BASE_URL . '/' . $srv['image_url']); ?>" alt="<?= e($srv['name']); ?>" class="w-100 h-100" style="object-fit: cover;" loading="lazy">
                    </div>
                    <?php endif; ?>

                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="service-icon-box m-0">
                                <img src="<?= e(BASE_URL . '/assets/images/icons/' . ($srv['icon_file'] ?? 'luxury-sedan.svg')); ?>" alt="<?= e($srv['name']); ?> Icon" width="60" height="35">
                            </div>
                            <h3 class="fs-5 fw-bold mb-0"><?= e($srv['name']); ?></h3>
                        </div>

                        <p class="text-muted small mb-3 flex-grow-1">
                            <?= e($srv['full_desc'] ?? $srv['short_desc']); ?>
                        </p>

                        <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                            <span class="badge bg-light text-success border">
                                <i class="fa-solid fa-shield me-1"></i> All India Permit
                            </span>
                            <a href="<?= e(BASE_URL); ?>/booking.php?vehicle=<?= urlencode($srv['name']); ?>" class="btn btn-outline-success btn-sm rounded-pill fw-bold">
                                Enquire Now <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Travel Types Section -->
<section class="section-py section-bg-light">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Trip Types</span>
            <h2 class="section-title">Flexible Travel Formats</h2>
            <p class="section-subtitle">Choose the trip format that best matches your schedule and itinerary.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
                    <div class="fs-1 text-success mb-3"><i class="fa-solid fa-arrow-right"></i></div>
                    <h5 class="fw-bold">One Way Drops</h5>
                    <p class="small text-muted mb-0">Point-to-point drop services between Lucknow and surrounding cities or airports.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
                    <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-arrows-rotate"></i></div>
                    <h5 class="fw-bold">Round Trips</h5>
                    <p class="small text-muted mb-0">Complete round-trip outstation itineraries with driver allowance and vehicle on disposal.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
                    <div class="fs-1 text-success mb-3"><i class="fa-solid fa-city"></i></div>
                    <h5 class="fw-bold">Local Sightseeing</h5>
                    <p class="small text-muted mb-0">City tours in Lucknow covering major cultural, culinary, and historic landmarks.</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="p-4 bg-white rounded-4 shadow-sm text-center h-100">
                    <div class="fs-1 text-warning mb-3"><i class="fa-solid fa-road"></i></div>
                    <h5 class="fw-bold">Interstate Outstation</h5>
                    <p class="small text-muted mb-0">Long-distance highway travel across any state in India with authorized All India Permit.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
