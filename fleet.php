<?php
/**
 * JY TOUR and TRAVELS - Fleet / Vehicles Page
 */
$pageTitle = 'Our Fleet | Dzire, Aura, Ertiga, Toyota Innova Crysta, Luxury Bus | JY TOUR and TRAVELS';
$pageDesc  = 'Explore our passenger vehicle fleet in Lucknow: Dzire, Aura, Ertiga, Toyota Innova Crysta, and Luxury Bus. Available with All India Permit for local & outstation journeys.';
$pageKeywords = 'Dzire rental Lucknow, Aura rental Lucknow, Ertiga hire Lucknow, Toyota Innova Crysta Lucknow, Luxury bus rental Lucknow, car rental fleet';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$pdo = get_db_connection();
$vehicles = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vehicles WHERE status = 1 ORDER BY display_order ASC, id ASC");
        $vehicles = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Fleet query error: " . $e->getMessage());
    }
}
if (empty($vehicles)) {
    $vehicles = get_fallback_vehicles();
}
?>

<!-- Fleet Hero Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-car-side me-1"></i> Available Fleet</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Vehicle Fleet</h1>
        <p class="text-white-50 lead mb-0">Dzire, Aura, Ertiga, Toyota Innova Crysta & Luxury Bus with All India Permit</p>
    </div>
</div>

<section class="section-py section-bg-light">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Vehicles Available</span>
            <h2 class="section-title">Choose Your Vehicle for Local & Outstation</h2>
            <p class="section-subtitle">
                Every vehicle in our fleet is well-maintained, cleaned, sanitized, and operated by professional chauffeurs with authorized All India Permit.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($vehicles as $v): ?>
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0 h-100">
                        <div class="col-md-5 position-relative bg-dark" style="min-height: 240px;">
                            <img src="<?= e(BASE_URL . '/' . $v['image_url']); ?>" alt="<?= e($v['name']); ?>" class="img-fluid w-100 h-100" style="object-fit: cover;" loading="lazy">
                            <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark fw-bold">
                                <i class="fa-solid fa-shield"></i> All India Permit
                            </span>
                            <span class="position-absolute bottom-0 start-0 m-2 badge bg-success text-white">
                                <?= e($v['category_name']); ?>
                            </span>
                        </div>
                        <div class="col-md-7 p-4 d-flex flex-column justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-1 fs-4"><?= e($v['name']); ?></h3>
                                <div class="text-success small fw-bold mb-2"><?= e($v['tagline'] ?? $v['category_name']); ?></div>
                                <p class="text-muted small mb-3"><?= e($v['description']); ?></p>

                                <div class="row g-2 small bg-light p-2 rounded mb-3">
                                    <div class="col-6">
                                        <i class="fa-solid fa-users text-success me-1"></i>
                                        <span><?= e($v['passenger_capacity']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <i class="fa-solid fa-suitcase text-success me-1"></i>
                                        <span><?= e($v['luggage_capacity']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <i class="fa-solid fa-snowflake text-success me-1"></i>
                                        <span><?= e($v['ac_type']); ?></span>
                                    </div>
                                    <div class="col-6">
                                        <i class="fa-solid fa-gas-pump text-success me-1"></i>
                                        <span><?= e($v['fuel_type']); ?></span>
                                    </div>
                                </div>

                                <?php if (!empty($v['features'])): ?>
                                <div class="small text-muted mb-3">
                                    <strong>Key Features:</strong> <?= e($v['features']); ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="<?= e(BASE_URL); ?>/booking.php?vehicle=<?= urlencode($v['name']); ?>" class="btn-jy-gold flex-grow-1 text-center py-2 text-decoration-none">
                                    <i class="fa-solid fa-calendar-check me-1"></i> Book Now
                                </a>
                                <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn btn-outline-dark px-3 py-2">
                                    <i class="fa-solid fa-phone"></i>
                                </a>
                                <a href="<?= e(get_whatsapp_url('Hello JY Tour & Travels, I am interested in hiring ' . $v['name'])); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success px-3 py-2">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Promotional Banner -->
<section class="all-india-permit-banner text-center text-md-start">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="permit-banner-badge">
                    <i class="fa-solid fa-route me-1"></i> <?= e(get_setting('permit_banner_highlight', 'ALL INDIA PERMIT')); ?>
                </span>
                <h2 class="permit-banner-title">
                    Ready to Book Your Next Journey?
                </h2>
                <p class="permit-banner-desc">
                    Contact our booking helpline or submit an online enquiry for instant confirmation.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end text-center">
                <a href="<?= e(BASE_URL); ?>/booking.php" class="btn-jy-gold btn-lg px-4 py-3">
                    <i class="fa-solid fa-calendar-check me-2"></i> Book Online Now
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
