<?php
/**
 * JY TOUR and TRAVELS - Gallery Management
 */
$adminTitle = 'Manage Gallery | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $title    = sanitize($_POST['title'] ?? '');
            $category = sanitize($_POST['category'] ?? 'vehicles');
            $caption  = sanitize($_POST['caption'] ?? '');
            $order    = (int)($_POST['display_order'] ?? 0);

            $allowedCats = ['vehicles', 'travel', 'luxury_cars', 'bus', 'road_trips'];
            if (!in_array($category, $allowedCats)) {
                $category = 'vehicles';
            }

            $imageUrl = 'assets/images/gallery/road_trip_highway.jpg';

            if (!empty($_FILES['gallery_file']['name'])) {
                $file = $_FILES['gallery_file'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowedExts) && $file['size'] <= 5 * 1024 * 1024) {
                    $uploadDir = ROOT_PATH . '/uploads/gallery/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $filename = 'gallery_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                        $imageUrl = 'uploads/gallery/' . $filename;
                    }
                }
            } elseif (!empty($_POST['preset_image'])) {
                $imageUrl = sanitize($_POST['preset_image']);
            }

            if (!empty($title)) {
                $stmt = $pdo->prepare("INSERT INTO gallery (title, category, image_url, caption, display_order, is_featured, created_at)
                                        VALUES (:t, :c, :i, :cap, :o, 1, NOW())");
                $stmt->execute([':t' => $title, ':c' => $category, ':i' => $imageUrl, ':cap' => $caption, ':o' => $order]);
                set_flash('success', "Image '{$title}' added to gallery.");
            }
        } elseif ($action === 'delete') {
            $gId = (int)($_POST['gallery_id'] ?? 0);
            if ($gId > 0) {
                $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
                $stmt->execute([':id' => $gId]);
                set_flash('success', 'Image removed from gallery.');
            }
        }
    }
    header('Location: ' . BASE_URL . '/admin/gallery.php');
    exit;
}

$gallery = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, id ASC");
        $gallery = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($gallery)) {
    $gallery = get_fallback_gallery();
}
?>

<div class="row g-4">
    <!-- Gallery Grid -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Gallery Items</h2>
            <span class="badge bg-success"><?= count($gallery); ?> Images</span>
        </div>

        <div class="row g-3">
            <?php foreach ($gallery as $g): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                    <img src="<?= e(BASE_URL . '/' . $g['image_url']); ?>" alt="<?= e($g['title']); ?>" style="height: 140px; object-fit: cover;">
                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                            <span class="badge bg-light text-dark border small mb-1"><?= e(str_replace('_', ' ', $g['category'])); ?></span>
                            <div class="fw-bold text-dark small text-truncate"><?= e($g['title']); ?></div>
                        </div>
                        <div class="text-end mt-2 pt-2 border-top">
                            <form action="<?= e(BASE_URL); ?>/admin/gallery.php" method="POST" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="gallery_id" value="<?= $g['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete" data-item="image <?= e($g['title']); ?>">
                                    <i class="fa-solid fa-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Upload/Add Image Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-upload text-warning me-2"></i> Add Gallery Image</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= e(BASE_URL); ?>/admin/gallery.php" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Title / Caption <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Highway Outstation Journey" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Tag</label>
                        <select name="category" class="form-select">
                            <option value="vehicles">Vehicles</option>
                            <option value="luxury_cars">Luxury Cars</option>
                            <option value="bus">Luxury Bus</option>
                            <option value="travel">Travel</option>
                            <option value="road_trips">Road Trips</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Image File</label>
                        <input type="file" name="gallery_file" class="form-control" accept="image/*">
                        <small class="text-muted">JPG, PNG, or WebP</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Or Select Stock Image</label>
                        <select name="preset_image" class="form-select">
                            <option value="">-- Choose Preset Stock Photo --</option>
                            <option value="assets/images/gallery/lucknow_rumi.jpg">Lucknow Rumi Darwaza Heritage</option>
                            <option value="assets/images/gallery/road_trip_highway.jpg">Highway Road Trip</option>
                            <option value="assets/images/gallery/luxury_car_interior.jpg">Luxury Car Cabin Interior</option>
                            <option value="assets/images/gallery/scenic_mountain_drive.jpg">Scenic Mountain Drive</option>
                            <option value="assets/images/gallery/tourist_group_bus.jpg">Tour Coach Bus</option>
                            <option value="assets/images/gallery/agra_taj_trip.jpg">Agra / Golden Triangle Trip</option>
                            <option value="assets/images/gallery/family_vacation.jpg">Family Vacation Travel</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="0">
                    </div>

                    <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Add to Gallery
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
