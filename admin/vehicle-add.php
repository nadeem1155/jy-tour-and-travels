<?php
/**
 * JY TOUR and TRAVELS - Add Vehicle
 */
$adminTitle = 'Add Vehicle | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$errors = [];

// Fetch categories
$categories = [];
if ($pdo) {
    try {
        $cStmt = $pdo->query("SELECT * FROM vehicle_categories ORDER BY display_order ASC");
        $categories = $cStmt->fetchAll();
    } catch (Exception $e) {}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Security token invalid. Please refresh the page.';
    } else {
        $name        = sanitize($_POST['name'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $tagline     = sanitize($_POST['tagline'] ?? '');
        $passengers  = sanitize($_POST['passenger_capacity'] ?? '4 + 1 Passengers');
        $luggage     = sanitize($_POST['luggage_capacity'] ?? '2 Bags');
        $acType      = sanitize($_POST['ac_type'] ?? 'Air Conditioned');
        $fuelType    = sanitize($_POST['fuel_type'] ?? 'Diesel');
        $pricing     = sanitize($_POST['pricing_display'] ?? 'Best Tariff on Call');
        $description = sanitize($_POST['description'] ?? '');
        $features    = sanitize($_POST['features'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $status      = isset($_POST['status']) ? 1 : 0;

        if (empty($name)) {
            $errors[] = 'Vehicle name is required.';
        }

        // Resolve category name
        $categoryName = 'Luxury Vehicle';
        foreach ($categories as $cat) {
            if ($cat['id'] == $categoryId) {
                $categoryName = $cat['name'];
                break;
            }
        }

        // Image handling: Upload or Default
        $imageUrl = 'assets/images/vehicles/sedan_dzire.jpg';
        if (!empty($_FILES['vehicle_image']['name'])) {
            $file = $_FILES['vehicle_image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($ext, $allowedExts)) {
                $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
            } elseif ($file['size'] > 5 * 1024 * 1024) {
                $errors[] = 'Image size cannot exceed 5MB.';
            } else {
                $uploadDir = ROOT_PATH . '/uploads/vehicles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = 'vehicle_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $imageUrl = 'uploads/vehicles/' . $filename;
                } else {
                    $errors[] = 'Failed to upload vehicle image.';
                }
            }
        } elseif (!empty($_POST['preset_image'])) {
            $imageUrl = sanitize($_POST['preset_image']);
        }

        if (empty($errors) && $pdo) {
            try {
                $slug = slugify($name);
                // Check slug uniqueness
                $ch = $pdo->prepare("SELECT COUNT(*) FROM vehicles WHERE slug = :s");
                $ch->execute([':s' => $slug]);
                if ($ch->fetchColumn() > 0) {
                    $slug .= '-' . rand(100, 999);
                }

                $stmt = $pdo->prepare("INSERT INTO vehicles (
                                            category_id, name, slug, category_name, tagline,
                                            image_url, passenger_capacity, luggage_capacity,
                                            ac_type, fuel_type, pricing_display, all_india_permit,
                                            description, features, display_order, status, created_at
                                        ) VALUES (
                                            :cid, :name, :slug, :cname, :tagline,
                                            :img, :pass, :lug, :ac, :fuel, :price, 1,
                                            :desc, :feat, :disp, :st, NOW()
                                        )");
                $stmt->execute([
                    ':cid'     => $categoryId ?: null,
                    ':name'    => $name,
                    ':slug'    => $slug,
                    ':cname'   => $categoryName,
                    ':tagline' => $tagline,
                    ':img'     => $imageUrl,
                    ':pass'    => $passengers,
                    ':lug'     => $luggage,
                    ':ac'      => $acType,
                    ':fuel'    => $fuelType,
                    ':price'   => $pricing,
                    ':desc'    => $description,
                    ':feat'    => $features,
                    ':disp'    => $displayOrder,
                    ':st'      => $status
                ]);

                set_flash('success', "Vehicle '{$name}' added successfully.");
                header('Location: ' . BASE_URL . '/admin/vehicles.php');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Database error: ' . $e->getMessage();
            }
        }
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= e(BASE_URL); ?>/admin/vehicles.php" class="text-muted small text-decoration-none mb-1 d-inline-block">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Fleet List
        </a>
        <h2 class="fw-bold mb-0">Add New Vehicle</h2>
    </div>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($errors as $err): ?>
        <li><?= e($err); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <form action="<?= e(BASE_URL); ?>/admin/vehicle-add.php" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Vehicle Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Toyota Innova Crysta, Dzire" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="" disabled selected>-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id']; ?>"><?= e($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Tagline / Catchphrase</label>
                    <input type="text" name="tagline" class="form-control" placeholder="e.g. Premium Sedan for Executive and Family Travel">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Upload Vehicle Image</label>
                    <input type="file" name="vehicle_image" class="form-control" accept="image/*">
                    <small class="text-muted">Supported formats: JPG, PNG, WebP (Max 5MB)</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Or Select Preset Image</label>
                    <select name="preset_image" class="form-select">
                        <option value="">-- Choose from standard assets --</option>
                        <option value="assets/images/vehicles/innova_crysta_crop.jpg">Toyota Innova Crysta (Advertisement Match)</option>
                        <option value="assets/images/vehicles/sedan_dzire.jpg">Maruti Dzire Sedan</option>
                        <option value="assets/images/vehicles/aura_sedan.jpg">Hyundai Aura Sedan</option>
                        <option value="assets/images/vehicles/ertiga_muv.jpg">Maruti Ertiga MUV</option>
                        <option value="assets/images/vehicles/luxury_bus.jpg">Luxury Tour Coach Bus</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Passenger Capacity</label>
                    <input type="text" name="passenger_capacity" class="form-control" value="4 + 1 Passengers">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Luggage Capacity</label>
                    <input type="text" name="luggage_capacity" class="form-control" value="2 Bags">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">AC System</label>
                    <input type="text" name="ac_type" class="form-control" value="Air Conditioned">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Fuel Type</label>
                    <input type="text" name="fuel_type" class="form-control" value="Diesel / Petrol">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Pricing Label</label>
                    <input type="text" name="pricing_display" class="form-control" value="Best Rates on Call">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="0">
                </div>

                <div class="col-md-3 d-flex align-items-center pt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                        <label class="form-check-label fw-bold" for="status">Active in Fleet</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Detailed vehicle overview..."></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Features & Highlights (Comma separated)</label>
                    <input type="text" name="features" class="form-control" value="All India Permit, Air Conditioned, Pushback Seats, Sanitized Cabin, Professional Chauffeur">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4 py-2">
                        <i class="fa-solid fa-plus me-1"></i> Save Vehicle
                    </button>
                    <a href="<?= e(BASE_URL); ?>/admin/vehicles.php" class="btn btn-outline-secondary px-4 py-2 ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
