<?php
/**
 * JY TOUR and TRAVELS - Edit Vehicle
 */
$adminTitle = 'Edit Vehicle | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$id = (int)($_GET['id'] ?? 0);

if (!$pdo || $id <= 0) {
    set_flash('danger', 'Invalid vehicle ID.');
    header('Location: ' . BASE_URL . '/admin/vehicles.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$v = $stmt->fetch();

if (!$v) {
    set_flash('danger', 'Vehicle not found.');
    header('Location: ' . BASE_URL . '/admin/vehicles.php');
    exit;
}

// Fetch categories
$categories = [];
try {
    $cStmt = $pdo->query("SELECT * FROM vehicle_categories ORDER BY display_order ASC");
    $categories = $cStmt->fetchAll();
} catch (Exception $e) {}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errors[] = 'Security validation failed.';
    } else {
        $name        = sanitize($_POST['name'] ?? '');
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $tagline     = sanitize($_POST['tagline'] ?? '');
        $passengers  = sanitize($_POST['passenger_capacity'] ?? '');
        $luggage     = sanitize($_POST['luggage_capacity'] ?? '');
        $acType      = sanitize($_POST['ac_type'] ?? '');
        $fuelType    = sanitize($_POST['fuel_type'] ?? '');
        $pricing     = sanitize($_POST['pricing_display'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $features    = sanitize($_POST['features'] ?? '');
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        $status      = isset($_POST['status']) ? 1 : 0;

        if (empty($name)) {
            $errors[] = 'Vehicle name is required.';
        }

        // Category Name
        $categoryName = $v['category_name'];
        foreach ($categories as $cat) {
            if ($cat['id'] == $categoryId) {
                $categoryName = $cat['name'];
                break;
            }
        }

        $imageUrl = $v['image_url'];
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
                    $errors[] = 'Failed to upload image.';
                }
            }
        }

        if (empty($errors)) {
            try {
                $uStmt = $pdo->prepare("UPDATE vehicles SET 
                                            category_id = :cid,
                                            name = :name,
                                            category_name = :cname,
                                            tagline = :tagline,
                                            image_url = :img,
                                            passenger_capacity = :pass,
                                            luggage_capacity = :lug,
                                            ac_type = :ac,
                                            fuel_type = :fuel,
                                            pricing_display = :price,
                                            description = :desc,
                                            features = :feat,
                                            display_order = :disp,
                                            status = :st
                                        WHERE id = :id");
                $uStmt->execute([
                    ':cid'     => $categoryId ?: null,
                    ':name'    => $name,
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
                    ':st'      => $status,
                    ':id'      => $id
                ]);

                set_flash('success', "Vehicle '{$name}' updated successfully.");
                header('Location: ' . BASE_URL . '/admin/vehicles.php');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'Database update error: ' . $e->getMessage();
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
        <h2 class="fw-bold mb-0">Edit Vehicle: <?= e($v['name']); ?></h2>
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
        <form action="<?= e(BASE_URL); ?>/admin/vehicle-edit.php?id=<?= $v['id']; ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field(); ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Vehicle Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= e($v['name']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id']; ?>" <?= $v['category_id'] == $cat['id'] ? 'selected' : ''; ?>><?= e($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Tagline</label>
                    <input type="text" name="tagline" class="form-control" value="<?= e($v['tagline']); ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-bold">Change Vehicle Image</label>
                    <input type="file" name="vehicle_image" class="form-control" accept="image/*">
                    <small class="text-muted">Leave empty to keep existing image</small>
                </div>

                <div class="col-md-4 text-center">
                    <label class="form-label fw-bold d-block">Current Image</label>
                    <img src="<?= e(BASE_URL . '/' . $v['image_url']); ?>" alt="<?= e($v['name']); ?>" class="rounded border" width="100" height="60" style="object-fit: cover;">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Passenger Capacity</label>
                    <input type="text" name="passenger_capacity" class="form-control" value="<?= e($v['passenger_capacity']); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Luggage Capacity</label>
                    <input type="text" name="luggage_capacity" class="form-control" value="<?= e($v['luggage_capacity']); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">AC System</label>
                    <input type="text" name="ac_type" class="form-control" value="<?= e($v['ac_type']); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Fuel Type</label>
                    <input type="text" name="fuel_type" class="form-control" value="<?= e($v['fuel_type']); ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Pricing Label</label>
                    <input type="text" name="pricing_display" class="form-control" value="<?= e($v['pricing_display']); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Display Order</label>
                    <input type="number" name="display_order" class="form-control" value="<?= (int)$v['display_order']; ?>">
                </div>

                <div class="col-md-3 d-flex align-items-center pt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?= !empty($v['status']) ? 'checked' : ''; ?>>
                        <label class="form-check-label fw-bold" for="status">Active in Fleet</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" rows="3" class="form-control"><?= e($v['description']); ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Features & Highlights</label>
                    <input type="text" name="features" class="form-control" value="<?= e($v['features']); ?>">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success fw-bold px-4 py-2">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Vehicle
                    </button>
                    <a href="<?= e(BASE_URL); ?>/admin/vehicles.php" class="btn btn-outline-secondary px-4 py-2 ms-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
