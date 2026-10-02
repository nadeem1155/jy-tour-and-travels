<?php
/**
 * JY TOUR and TRAVELS - Vehicle Fleet Management
 */
$adminTitle = 'Fleet Management | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

// Handle Delete or Toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';
        $vId = (int)($_POST['vehicle_id'] ?? 0);

        if ($action === 'delete' && $vId > 0) {
            $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = :id");
            $stmt->execute([':id' => $vId]);
            set_flash('success', 'Vehicle removed from fleet.');
        } elseif ($action === 'toggle' && $vId > 0) {
            $stmt = $pdo->prepare("UPDATE vehicles SET status = 1 - status WHERE id = :id");
            $stmt->execute([':id' => $vId]);
            set_flash('success', 'Vehicle status updated.');
        }
    }
    header('Location: ' . BASE_URL . '/admin/vehicles.php');
    exit;
}

$vehicles = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM vehicles ORDER BY display_order ASC, id ASC");
        $vehicles = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Vehicles fetch error: " . $e->getMessage());
    }
}
if (empty($vehicles)) {
    $vehicles = get_fallback_vehicles();
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Fleet Vehicles</h2>
        <p class="text-muted small mb-0">Manage vehicles displayed on the website and booking forms.</p>
    </div>
    <a href="<?= e(BASE_URL); ?>/admin/vehicle-add.php" class="btn btn-warning text-dark fw-bold">
        <i class="fa-solid fa-plus me-1"></i> Add New Vehicle
    </a>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Vehicle Name</th>
                    <th>Category</th>
                    <th>Capacity</th>
                    <th>Permit</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehicles)): ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No vehicles found.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($vehicles as $v): ?>
                <tr>
                    <td>
                        <img src="<?= e(BASE_URL . '/' . $v['image_url']); ?>" alt="<?= e($v['name']); ?>" class="rounded" width="70" height="48" style="object-fit: cover;">
                    </td>
                    <td>
                        <div class="fw-bold text-dark"><?= e($v['name']); ?></div>
                        <div class="small text-muted"><?= e($v['tagline'] ?? ''); ?></div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border"><?= e($v['category_name']); ?></span>
                    </td>
                    <td>
                        <div class="small"><i class="fa-solid fa-users text-muted me-1"></i> <?= e($v['passenger_capacity']); ?></div>
                        <div class="small text-muted"><i class="fa-solid fa-suitcase text-muted me-1"></i> <?= e($v['luggage_capacity']); ?></div>
                    </td>
                    <td>
                        <span class="badge bg-success-subtle text-success">
                            <i class="fa-solid fa-check me-1"></i> All India Permit
                        </span>
                    </td>
                    <td>
                        <form action="<?= e(BASE_URL); ?>/admin/vehicles.php" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="vehicle_id" value="<?= $v['id']; ?>">
                            <button type="submit" class="badge border-0 <?= !empty($v['status']) ? 'bg-success' : 'bg-secondary'; ?>">
                                <?= !empty($v['status']) ? 'Active' : 'Inactive'; ?>
                            </button>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="<?= e(BASE_URL); ?>/admin/vehicle-edit.php?id=<?= $v['id']; ?>" class="btn btn-sm btn-light border" title="Edit">
                                <i class="fa-solid fa-pen text-primary"></i>
                            </a>
                            <form action="<?= e(BASE_URL); ?>/admin/vehicles.php" method="POST" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="vehicle_id" value="<?= $v['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-light border text-danger confirm-delete" data-item="<?= e($v['name']); ?>" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
