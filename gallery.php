<?php
/**
 * JY TOUR and TRAVELS - Gallery Page
 */
$pageTitle = 'Photo Gallery | Vehicles, Road Trips & Travel | JY TOUR and TRAVELS';
$pageDesc  = 'Browse photographs of our fleet, luxury cars, tourist buses, and road trips across India. JY TOUR and TRAVELS, Lucknow.';
$pageKeywords = 'JY tour and travels gallery, luxury cars photo, travel photos, tourist bus photos, road trips India';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$pdo = get_db_connection();
$gallery = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, id ASC");
        $gallery = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Gallery query error: " . $e->getMessage());
    }
}
if (empty($gallery)) {
    $gallery = get_fallback_gallery();
}
?>

<!-- Gallery Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-images me-1"></i> Visual Showcase</span>
        <h1 class="display-5 fw-bold text-white mb-2">Our Travel & Fleet Gallery</h1>
        <p class="text-white-50 lead mb-0">Moments on the Road with JY TOUR and TRAVELS</p>
    </div>
</div>

<section class="section-py">
    <div class="container">
        <div class="section-title-wrap">
            <span class="section-eyebrow">Moments & Fleet</span>
            <h2 class="section-title">Explore by Category</h2>
            <p class="section-subtitle">Click on any photo to open high-resolution preview.</p>
        </div>

        <!-- Filter Buttons -->
        <div class="gallery-nav">
            <button class="gallery-btn active" data-filter="all">All Photos</button>
            <button class="gallery-btn" data-filter="vehicles">Vehicles</button>
            <button class="gallery-btn" data-filter="luxury_cars">Luxury Cars</button>
            <button class="gallery-btn" data-filter="bus">Luxury Bus</button>
            <button class="gallery-btn" data-filter="travel">Travel & Tours</button>
            <button class="gallery-btn" data-filter="road_trips">Road Trips</button>
        </div>

        <!-- Gallery Grid -->
        <div class="row g-4">
            <?php foreach ($gallery as $g): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 gallery-col" data-category="<?= e($g['category']); ?>">
                <div class="gallery-item" data-title="<?= e($g['title']); ?>" data-full="<?= e(BASE_URL . '/' . $g['image_url']); ?>">
                    <img src="<?= e(BASE_URL . '/' . $g['image_url']); ?>" alt="<?= e($g['title']); ?>" loading="lazy">
                    <div class="gallery-overlay">
                        <div class="gallery-overlay-badge"><?= e(str_replace('_', ' ', $g['category'])); ?></div>
                        <div class="gallery-overlay-title"><?= e($g['title']); ?></div>
                        <?php if (!empty($g['caption'])): ?>
                        <div class="small text-white-50 text-truncate"><?= e($g['caption']); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
