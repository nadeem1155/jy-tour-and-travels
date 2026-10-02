<?php
/**
 * JY TOUR and TRAVELS - Vehicle Categories Management
 */
$adminTitle = 'Vehicle Categories | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            $name = sanitize($_POST['name'] ?? '');
            $icon = sanitize($_POST['icon_svg'] ?? 'luxury-sedan.svg');
            $desc = sanitize($_POST['short_desc'] ?? '');
            $order = (int)($_POST['display_order'] ?? 0);

            if (!empty($name)) {
                $slug = slugify($name);
                $stmt = $pdo->prepare("INSERT INTO vehicle_categories (name, slug, icon_svg, short_desc, display_order, status) VALUES (:n, :s, :i, :d, :o, 1)");
                $stmt->execute([':n' => $name, ':s' => $slug, ':i' => $icon, ':d' => $desc, ':o' => $order]);
                set_flash('success', "Category '{$name}' created.");
            }
        } elseif ($action === 'delete') {
            $catId = (int)($_POST['category_id'] ?? 0);
            if ($catId > 0) {
                $stmt = $pdo->prepare("DELETE FROM vehicle_categories WHERE id = :id");
                $stmt->execute([':id' => $catId]);
                set_flash('success', 'Category deleted.');
            }
        }
    }
    header('Location: ' . BASE_URL . '/admin/categories.php');
    exit;
}

$categories = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vehicle_categories ORDER BY display_order ASC, id ASC");
        $categories = $stmt->fetchAll();
    } catch (Exception $e) {}
}
?>

<div class="row g-4">
    <!-- Categories List -->
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Vehicle Categories</h2>
            <span class="badge bg-success"><?= count($categories); ?> Categories</span>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Icon</th>
                            <th>Category Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Order</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No categories defined.</td></tr>
                        <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td>
                                <img src="<?= e(BASE_URL . '/assets/images/icons/' . ($cat['icon_svg'] ?? 'luxury-sedan.svg')); ?>" alt="icon" width="40" height="24">
                            </td>
                            <td><strong class="text-dark"><?= e($cat['name']); ?></strong></td>
                            <td><code><?= e($cat['slug']); ?></code></td>
                            <td class="small text-muted"><?= e($cat['short_desc']); ?></td>
                            <td><?= (int)$cat['display_order']; ?></td>
                            <td class="text-end">
                                <form action="<?= e(BASE_URL); ?>/admin/categories.php" method="POST" class="d-inline">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="category_id" value="<?= $cat['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-light border text-danger confirm-delete" data-item="category <?= e($cat['name']); ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Category Form -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-plus text-warning me-2"></i> Add New Category</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= e(BASE_URL); ?>/admin/categories.php" method="POST">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="action" value="add">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Luxury Sedan" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon File</label>
                        <select name="icon_svg" class="form-select">
                            <option value="luxury-sedan.svg">Luxury Sedan (Sedan SVG)</option>
                            <option value="luxury-muv.svg">Luxury MUV (MUV SVG)</option>
                            <option value="luxury-suv.svg">Luxury SUV (SUV SVG)</option>
                            <option value="luxury-bus.svg">Luxury Bus (Bus SVG)</option>
                            <option value="luxury-hatchback.svg">Luxury Hatch Back (Hatchback SVG)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Display Order</label>
                        <input type="number" name="display_order" class="form-control" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Short Description</label>
                        <textarea name="short_desc" class="form-control" rows="3" placeholder="Brief summary of this vehicle tier..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                        <i class="fa-solid fa-plus me-1"></i> Create Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
