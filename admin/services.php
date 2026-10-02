<?php
/**
 * JY TOUR and TRAVELS - Services Management
 */
$adminTitle = 'Manage Services | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $name     = sanitize($_POST['name'] ?? '');
            $code     = sanitize($_POST['category_code'] ?? 'sedan');
            $icon     = sanitize($_POST['icon_file'] ?? 'luxury-sedan.svg');
            $short    = sanitize($_POST['short_desc'] ?? '');
            $full     = sanitize($_POST['full_desc'] ?? '');
            $order    = (int)($_POST['display_order'] ?? 0);

            if (!empty($name)) {
                $slug = slugify($name);
                $stmt = $pdo->prepare("INSERT INTO services (name, slug, category_code, icon_file, short_desc, full_desc, display_order, status)
                                        VALUES (:n, :s, :c, :i, :sd, :fd, :o, 1)");
                $stmt->execute([':n' => $name, ':s' => $slug, ':c' => $code, ':i' => $icon, ':sd' => $short, ':fd' => $full, ':o' => $order]);
                set_flash('success', "Service '{$name}' created successfully.");
            }
        } elseif ($action === 'delete') {
            $sId = (int)($_POST['service_id'] ?? 0);
            if ($sId > 0) {
                $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
                $stmt->execute([':id' => $sId]);
                set_flash('success', 'Service removed.');
            }
        }
    }
    header('Location: ' . BASE_URL . '/admin/services.php');
    exit;
}

$services = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM services ORDER BY display_order ASC, id ASC");
        $services = $stmt->fetchAll();
    } catch (Exception $e) {}
}
if (empty($services)) {
    $services = get_fallback_services();
}
?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Service Offerings</h2>
            <span class="badge bg-success"><?= count($services); ?> Active</span>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Icon</th>
                            <th>Service Name</th>
                            <th>Summary</th>
                            <th>Order</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $srv): ?>
                        <tr>
                            <td>
                                <img src="<?= e(BASE_URL . '/assets/images/icons/' . ($srv['icon_file'] ?? 'luxury-sedan.svg')); ?>" alt="icon" width="45" height="25">
                            </td>
                            <td><strong class="text-dark"><?= e($srv['name']); ?></strong></td>
                            <td class="small text-muted"><?= e($srv['short_desc']); ?></td>
                            <td><?= (int)($srv['display_order'] ?? 0); ?></td>
                            <td class="text-end">
                                <form action="<?= e(BASE_URL); ?>/admin/services.php" method="POST" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="service_id" value="<?= $srv['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-light border text-danger confirm-delete" data-item="<?= e($srv['name']); ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Service Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-plus text-warning me-2"></i> Add New Service</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= e(BASE_URL); ?>/admin/services.php" method="POST">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Service Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Luxury SUV" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Code</label>
                        <select name="category_code" class="form-select">
                            <option value="sedan">Sedan</option>
                            <option value="muv">MUV</option>
                            <option value="suv">SUV</option>
                            <option value="bus">Bus</option>
                            <option value="hatchback">Hatch Back</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon SVG</label>
                        <select name="icon_file" class="form-select">
                            <option value="luxury-sedan.svg">Luxury Sedan Icon</option>
                            <option value="luxury-muv.svg">Luxury MUV Icon</option>
                            <option value="luxury-suv.svg">Luxury SUV Icon</option>
                            <option value="luxury-bus.svg">Luxury Bus Icon</option>
                            <option value="luxury-hatchback.svg">Luxury Hatch Back Icon</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Short Description</label>
                        <textarea name="short_desc" class="form-control" rows="2" placeholder="One sentence service summary..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Description</label>
                        <textarea name="full_desc" class="form-control" rows="3" placeholder="Detailed service features..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                        <i class="fa-solid fa-plus me-1"></i> Save Service
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
