<?php
/**
 * JY TOUR and TRAVELS - Admin Dashboard Overview
 */
$adminTitle = 'Dashboard | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

$stats = [
    'total_bookings' => 0,
    'new_bookings'   => 0,
    'total_vehicles' => 5,
    'total_messages' => 0
];

$recentBookings = [];
$recentMessages = [];

if ($pdo) {
    try {
        $stats['total_bookings'] = (int)$pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
        $stats['new_bookings']   = (int)$pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'New'")->fetchColumn();
        $stats['total_vehicles'] = (int)$pdo->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
        $stats['total_messages'] = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

        $bStmt = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 6");
        $recentBookings = $bStmt->fetchAll();

        $mStmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
        $recentMessages = $mStmt->fetchAll();
    } catch (Exception $e) {
        error_log("Dashboard query error: " . $e->getMessage());
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Operational Overview</h2>
        <p class="text-muted small mb-0">Welcome back, <?= e($adminUser['name']); ?>. Monitor inquiries and fleet status.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="btn btn-success btn-sm">
            <i class="fa-solid fa-list-check me-1"></i> All Bookings
        </a>
        <a href="<?= e(BASE_URL); ?>/admin/vehicle-add.php" class="btn btn-warning btn-sm text-dark fw-bold">
            <i class="fa-solid fa-plus me-1"></i> Add Vehicle
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted small fw-bold text-uppercase">New Enquiries</div>
                <div class="stat-number text-danger"><?= $stats['new_bookings']; ?></div>
                <div class="small text-muted">Awaiting customer contact</div>
            </div>
            <div class="stat-icon bg-danger-subtle text-danger">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted small fw-bold text-uppercase">Total Bookings</div>
                <div class="stat-number text-dark"><?= $stats['total_bookings']; ?></div>
                <div class="small text-muted">All-time enquiries recorded</div>
            </div>
            <div class="stat-icon bg-primary-subtle text-primary">
                <i class="fa-solid fa-clipboard-list"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted small fw-bold text-uppercase">Active Fleet</div>
                <div class="stat-number text-success"><?= $stats['total_vehicles']; ?></div>
                <div class="small text-muted">Vehicles with All India Permit</div>
            </div>
            <div class="stat-icon bg-success-subtle text-success">
                <i class="fa-solid fa-car-side"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card d-flex align-items-center justify-content-between">
            <div>
                <div class="text-muted small fw-bold text-uppercase">Contact Messages</div>
                <div class="stat-number text-warning text-dark"><?= $stats['total_messages']; ?></div>
                <div class="small text-muted">General website inquiries</div>
            </div>
            <div class="stat-icon bg-warning-subtle text-warning">
                <i class="fa-solid fa-envelope"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Booking Enquiries -->
<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i> Recent Booking Enquiries</h5>
                <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table admin-table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Ref #</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Vehicle</th>
                            <th>Travel Date</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentBookings)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block opacity-50"></i>
                                No booking enquiries received yet. Try placing a test booking from the frontend.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recentBookings as $b): ?>
                        <tr>
                            <td><strong class="text-dark"><?= e($b['booking_number']); ?></strong></td>
                            <td><?= e($b['full_name']); ?></td>
                            <td>
                                <a href="tel:<?= e($b['phone']); ?>" class="text-decoration-none">
                                    <i class="fa-solid fa-phone me-1 small text-success"></i><?= e($b['phone']); ?>
                                </a>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= e($b['vehicle_name']); ?></span></td>
                            <td><?= format_date($b['travel_date']); ?></td>
                            <td>
                                <span class="badge badge-status-<?= strtolower($b['status']); ?> px-2 py-1">
                                    <?= e($b['status']); ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="<?= e(BASE_URL); ?>/admin/booking-detail.php?id=<?= $b['id']; ?>" class="btn btn-sm btn-light border" title="View details">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Side: Quick Contact Messages & Fast Actions -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-envelope me-2 text-warning"></i> Recent Messages</h5>
                <a href="<?= e(BASE_URL); ?>/admin/messages.php" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentMessages)): ?>
                <div class="p-4 text-center text-muted small">No contact messages received yet.</div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($recentMessages as $msg): ?>
                    <li class="list-group-item p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong class="small text-dark"><?= e($msg['full_name']); ?></strong>
                            <span class="badge bg-light text-muted small"><?= format_date($msg['created_at'], 'd M, H:i'); ?></span>
                        </div>
                        <div class="small text-muted text-truncate mb-1"><?= e($msg['message']); ?></div>
                        <div class="d-flex justify-content-between align-items-center small">
                            <a href="tel:<?= e($msg['phone']); ?>" class="text-success text-decoration-none">
                                <i class="fa-solid fa-phone me-1"></i><?= e($msg['phone']); ?>
                            </a>
                            <a href="<?= e(BASE_URL); ?>/admin/messages.php" class="text-primary text-decoration-none">Open &rarr;</a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Business Configuration Snapshot -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-building text-success me-2"></i> Business Quick Info</h6>
                <p class="small text-muted mb-2"><strong>Phone:</strong> <?= e(get_setting('phone')); ?></p>
                <p class="small text-muted mb-2"><strong>Email:</strong> <?= e(get_setting('email')); ?></p>
                <p class="small text-muted mb-3"><strong>Permit:</strong> <?= e(get_setting('all_india_permit_badge')); ?></p>
                <a href="<?= e(BASE_URL); ?>/admin/settings.php" class="btn btn-outline-success btn-sm w-100">
                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Business Details
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
