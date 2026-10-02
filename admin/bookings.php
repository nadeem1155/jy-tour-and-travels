<?php
/**
 * JY TOUR and TRAVELS - Booking Enquiries Management
 */
$adminTitle = 'Booking Enquiries | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

// Handle Status Update or Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        if ($action === 'update_status' && $bookingId > 0) {
            $newStatus = sanitize($_POST['status'] ?? 'New');
            $allowedStatuses = ['New', 'Contacted', 'Confirmed', 'Completed', 'Cancelled'];
            if (in_array($newStatus, $allowedStatuses)) {
                $uStmt = $pdo->prepare("UPDATE bookings SET status = :st WHERE id = :id");
                $uStmt->execute([':st' => $newStatus, ':id' => $bookingId]);
                set_flash('success', "Booking status updated to {$newStatus}.");
            }
        } elseif ($action === 'delete' && $bookingId > 0) {
            $dStmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id");
            $dStmt->execute([':id' => $bookingId]);
            set_flash('success', 'Booking enquiry removed successfully.');
        }
    }
    header('Location: ' . BASE_URL . '/admin/bookings.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
    exit;
}

// Filters
$statusFilter = sanitize($_GET['status'] ?? '');
$searchQuery  = sanitize($_GET['search'] ?? '');

$bookings = [];
if ($pdo) {
    try {
        $sql = "SELECT * FROM bookings WHERE 1=1";
        $params = [];

        if (!empty($statusFilter) && in_array($statusFilter, ['New', 'Contacted', 'Confirmed', 'Completed', 'Cancelled'])) {
            $sql .= " AND status = :st";
            $params[':st'] = $statusFilter;
        }

        if (!empty($searchQuery)) {
            $sql .= " AND (booking_number LIKE :q OR full_name LIKE :q OR phone LIKE :q OR destination LIKE :q)";
            $params[':q'] = "%{$searchQuery}%";
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $bookings = $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Bookings list query error: " . $e->getMessage());
    }
}
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Booking Enquiries</h2>
        <p class="text-muted small mb-0">Review, update, and manage vehicle reservation requests.</p>
    </div>

    <!-- Filter Buttons -->
    <div class="btn-group flex-wrap">
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="btn btn-sm <?= empty($statusFilter) ? 'btn-dark' : 'btn-outline-secondary'; ?>">All</a>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php?status=New" class="btn btn-sm <?= $statusFilter === 'New' ? 'btn-primary' : 'btn-outline-primary'; ?>">New</a>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php?status=Contacted" class="btn btn-sm <?= $statusFilter === 'Contacted' ? 'btn-warning text-dark' : 'btn-outline-warning text-dark'; ?>">Contacted</a>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php?status=Confirmed" class="btn btn-sm <?= $statusFilter === 'Confirmed' ? 'btn-success' : 'btn-outline-success'; ?>">Confirmed</a>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php?status=Completed" class="btn btn-sm <?= $statusFilter === 'Completed' ? 'btn-secondary' : 'btn-outline-secondary'; ?>">Completed</a>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php?status=Cancelled" class="btn btn-sm <?= $statusFilter === 'Cancelled' ? 'btn-danger' : 'btn-outline-danger'; ?>">Cancelled</a>
    </div>
</div>

<!-- Search Bar -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-body p-3">
        <form action="<?= e(BASE_URL); ?>/admin/bookings.php" method="GET" class="row g-2 align-items-center">
            <?php if (!empty($statusFilter)): ?>
            <input type="hidden" name="status" value="<?= e($statusFilter); ?>">
            <?php endif; ?>
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by Booking Ref, Customer Name, Phone, or Destination..." value="<?= e($searchQuery); ?>">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success flex-grow-1"><i class="fa-solid fa-search me-1"></i> Search</button>
                <?php if (!empty($searchQuery) || !empty($statusFilter)): ?>
                <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Bookings Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Ref #</th>
                    <th>Customer Name</th>
                    <th>Contact Phone</th>
                    <th>Vehicle</th>
                    <th>Trip Details</th>
                    <th>Travel Date</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fs-1 mb-2 opacity-50 d-block"></i>
                        No booking enquiries match your criteria.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td>
                        <a href="<?= e(BASE_URL); ?>/admin/booking-detail.php?id=<?= $b['id']; ?>" class="fw-bold text-dark text-decoration-none">
                            <?= e($b['booking_number']); ?>
                        </a>
                    </td>
                    <td>
                        <strong><?= e($b['full_name']); ?></strong>
                        <?php if (!empty($b['email'])): ?>
                        <div class="small text-muted"><?= e($b['email']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="tel:<?= e($b['phone']); ?>" class="fw-bold text-success text-decoration-none">
                            <i class="fa-solid fa-phone me-1 small"></i><?= e($b['phone']); ?>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=91<?= preg_replace('/[^0-9]/', '', $b['phone']); ?>&text=<?= urlencode('Hello ' . $b['full_name'] . ', regarding your booking ' . $b['booking_number'] . ' with JY TOUR and TRAVELS'); ?>" target="_blank" rel="noopener noreferrer" class="ms-1 text-success" title="Chat on WhatsApp">
                            <i class="fa-brands fa-whatsapp fs-6"></i>
                        </a>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border"><?= e($b['vehicle_name']); ?></span>
                        <div class="small text-muted"><?= e($b['trip_type']); ?></div>
                    </td>
                    <td>
                        <div class="small"><strong>From:</strong> <?= e($b['pickup_location']); ?></div>
                        <div class="small"><strong>To:</strong> <?= e($b['destination']); ?></div>
                    </td>
                    <td>
                        <div><?= format_date($b['travel_date']); ?></div>
                        <?php if (!empty($b['return_date'])): ?>
                        <div class="small text-muted">Ret: <?= format_date($b['return_date']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="<?= e(BASE_URL); ?>/admin/bookings.php" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="booking_id" value="<?= $b['id']; ?>">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width: 120px;">
                                <option value="New" <?= $b['status'] === 'New' ? 'selected' : ''; ?>>New</option>
                                <option value="Contacted" <?= $b['status'] === 'Contacted' ? 'selected' : ''; ?>>Contacted</option>
                                <option value="Confirmed" <?= $b['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="Completed" <?= $b['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="Cancelled" <?= $b['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="<?= e(BASE_URL); ?>/admin/booking-detail.php?id=<?= $b['id']; ?>" class="btn btn-sm btn-light border" title="View Detail">
                                <i class="fa-solid fa-eye text-primary"></i>
                            </a>
                            <form action="<?= e(BASE_URL); ?>/admin/bookings.php" method="POST" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="booking_id" value="<?= $b['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-light border text-danger confirm-delete" data-item="booking #<?= e($b['booking_number']); ?>" title="Delete">
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
