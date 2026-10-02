<?php
/**
 * JY TOUR and TRAVELS - Booking Details View & Management
 */
$adminTitle = 'Booking Details | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();
$id = (int)($_GET['id'] ?? 0);

if (!$pdo || $id <= 0) {
    set_flash('danger', 'Invalid booking request.');
    header('Location: ' . BASE_URL . '/admin/bookings.php');
    exit;
}

// Handle Status & Notes Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token)) {
        $status = sanitize($_POST['status'] ?? 'New');
        $notes  = sanitize($_POST['admin_notes'] ?? '');

        $uStmt = $pdo->prepare("UPDATE bookings SET status = :st, admin_notes = :nt WHERE id = :id");
        $uStmt->execute([':st' => $status, ':nt' => $notes, ':id' => $id]);
        set_flash('success', 'Booking details and notes updated successfully.');
    }
    header('Location: ' . BASE_URL . '/admin/booking-detail.php?id=' . $id);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$b = $stmt->fetch();

if (!$b) {
    set_flash('danger', 'Booking enquiry not found.');
    header('Location: ' . BASE_URL . '/admin/bookings.php');
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= e(BASE_URL); ?>/admin/bookings.php" class="text-muted small text-decoration-none mb-1 d-inline-block">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to All Bookings
        </a>
        <h2 class="fw-bold mb-0">Booking #<?= e($b['booking_number']); ?></h2>
    </div>
    <span class="badge badge-status-<?= strtolower($b['status']); ?> fs-6 px-3 py-2">
        Status: <?= e($b['status']); ?>
    </span>
</div>

<div class="row g-4">
    <!-- Left Column: Booking Details -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-car-side text-success me-2"></i> Itinerary & Vehicle Information</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="text-muted small">Requested Vehicle</label>
                        <div class="fw-bold fs-5 text-dark"><?= e($b['vehicle_name']); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Trip Type</label>
                        <div class="fw-bold fs-5 text-primary"><?= e($b['trip_type']); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Pickup Location</label>
                        <div class="fw-bold text-dark"><i class="fa-solid fa-location-dot text-danger me-1"></i> <?= e($b['pickup_location']); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Destination</label>
                        <div class="fw-bold text-dark"><i class="fa-solid fa-flag-checkered text-success me-1"></i> <?= e($b['destination']); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Travel Date</label>
                        <div class="fw-bold text-dark"><i class="fa-solid fa-calendar me-1 text-muted"></i> <?= format_date($b['travel_date'], 'l, d F Y'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Return Date</label>
                        <div class="fw-bold text-dark"><i class="fa-solid fa-calendar-check me-1 text-muted"></i> <?= !empty($b['return_date']) ? format_date($b['return_date'], 'l, d F Y') : 'One Way / Same Day'; ?></div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Number of Passengers</label>
                        <div class="fw-bold text-dark"><?= (int)$b['passengers']; ?> Person(s)</div>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Permit Requirement</label>
                        <div class="fw-bold text-success"><i class="fa-solid fa-shield-halved me-1"></i> All India Permit Included</div>
                    </div>
                    <div class="col-12 mt-3">
                        <label class="text-muted small">Additional Customer Requirements</label>
                        <div class="p-3 bg-light rounded text-dark">
                            <?= !empty($b['additional_requirements']) ? nl2br(e($b['additional_requirements'])) : '<em>No specific instructions provided by customer.</em>'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Update Status & Notes Form -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-pen-to-square text-warning me-2"></i> Update Status & Internal Admin Notes</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= e(BASE_URL); ?>/admin/booking-detail.php?id=<?= $b['id']; ?>" method="POST">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Booking Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="New" <?= $b['status'] === 'New' ? 'selected' : ''; ?>>New - Enquiry Received</option>
                            <option value="Contacted" <?= $b['status'] === 'Contacted' ? 'selected' : ''; ?>>Contacted - Spoke to Customer</option>
                            <option value="Confirmed" <?= $b['status'] === 'Confirmed' ? 'selected' : ''; ?>>Confirmed - Trip Scheduled</option>
                            <option value="Completed" <?= $b['status'] === 'Completed' ? 'selected' : ''; ?>>Completed - Journey Finished</option>
                            <option value="Cancelled" <?= $b['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled - Customer or Agency Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="admin_notes" class="form-label fw-bold">Internal Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control" placeholder="Write internal notes, quoted tariff, assigned driver name, vehicle number, etc."><?= e($b['admin_notes'] ?? ''); ?></textarea>
                        <small class="text-muted">These notes are visible to administrators only.</small>
                    </div>

                    <button type="submit" class="btn btn-success fw-bold px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Customer Info & Direct Actions -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-user text-primary me-2"></i> Customer Profile</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small">Full Name</label>
                    <div class="fw-bold fs-5"><?= e($b['full_name']); ?></div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Mobile Phone</label>
                    <div>
                        <a href="tel:<?= e($b['phone']); ?>" class="fw-bold text-success fs-5 text-decoration-none">
                            <i class="fa-solid fa-phone me-1"></i><?= e($b['phone']); ?>
                        </a>
                    </div>
                </div>

                <?php if (!empty($b['email'])): ?>
                <div class="mb-3">
                    <label class="text-muted small">Email Address</label>
                    <div>
                        <a href="mailto:<?= e($b['email']); ?>" class="text-dark">
                            <?= e($b['email']); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="text-muted small">Enquiry Submitted</label>
                    <div class="text-muted small"><?= format_date($b['created_at'], 'd M Y, h:i A'); ?></div>
                </div>

                <hr class="my-4">

                <!-- Fast Actions -->
                <div class="d-grid gap-2">
                    <a href="tel:<?= e($b['phone']); ?>" class="btn btn-outline-dark">
                        <i class="fa-solid fa-phone me-1"></i> Call Customer
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=91<?= preg_replace('/[^0-9]/', '', $b['phone']); ?>&text=<?= urlencode('Hello ' . $b['full_name'] . ', regarding your vehicle booking enquiry ' . $b['booking_number'] . ' with JY TOUR and TRAVELS Lucknow.'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
