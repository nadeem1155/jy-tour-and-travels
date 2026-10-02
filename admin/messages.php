<?php
/**
 * JY TOUR and TRAVELS - Contact Messages Management
 */
$adminTitle = 'Contact Messages | JY TOUR and TRAVELS Administration';
require_once __DIR__ . '/includes/admin_header.php';

$pdo = get_db_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (verify_csrf_token($token) && $pdo) {
        $action = $_POST['action'] ?? '';
        $msgId  = (int)($_POST['message_id'] ?? 0);

        if ($action === 'status' && $msgId > 0) {
            $newSt = sanitize($_POST['status'] ?? 'Read');
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = :st WHERE id = :id");
            $stmt->execute([':st' => $newSt, ':id' => $msgId]);
            set_flash('success', "Message status updated to {$newSt}.");
        } elseif ($action === 'delete' && $msgId > 0) {
            $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
            $stmt->execute([':id' => $msgId]);
            set_flash('success', 'Message deleted.');
        }
    }
    header('Location: ' . BASE_URL . '/admin/messages.php');
    exit;
}

$messages = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        $messages = $stmt->fetchAll();
    } catch (Exception $e) {}
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Contact Messages</h2>
        <p class="text-muted small mb-0">Inquiries and messages submitted through the website contact form.</p>
    </div>
    <span class="badge bg-primary fs-6"><?= count($messages); ?> Total Inquiries</span>
</div>

<div class="card border-0 shadow-sm rounded-3">
    <div class="table-responsive">
        <table class="table admin-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender Name</th>
                    <th>Contact Details</th>
                    <th>Subject & Message</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fs-1 mb-2 opacity-50 d-block"></i>
                        No contact messages have been received yet.
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                <tr>
                    <td class="text-nowrap small text-muted">
                        <?= format_date($msg['created_at'], 'd M Y, h:i A'); ?>
                    </td>
                    <td>
                        <strong class="text-dark"><?= e($msg['full_name']); ?></strong>
                    </td>
                    <td>
                        <div>
                            <a href="tel:<?= e($msg['phone']); ?>" class="text-success fw-bold text-decoration-none">
                                <i class="fa-solid fa-phone me-1 small"></i><?= e($msg['phone']); ?>
                            </a>
                        </div>
                        <?php if (!empty($msg['email'])): ?>
                        <div class="small">
                            <a href="mailto:<?= e($msg['email']); ?>" class="text-muted"><?= e($msg['email']); ?></a>
                        </div>
                        <?php endif; ?>
                    </td>
                    <td style="max-width: 320px;">
                        <?php if (!empty($msg['subject'])): ?>
                        <div class="fw-bold text-dark small"><?= e($msg['subject']); ?></div>
                        <?php endif; ?>
                        <div class="small text-secondary"><?= nl2br(e($msg['message'])); ?></div>
                    </td>
                    <td>
                        <form action="<?= e(BASE_URL); ?>/admin/messages.php" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="action" value="status">
                            <input type="hidden" name="message_id" value="<?= $msg['id']; ?>">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="New" <?= $msg['status'] === 'New' ? 'selected' : ''; ?>>New</option>
                                <option value="Read" <?= $msg['status'] === 'Read' ? 'selected' : ''; ?>>Read</option>
                                <option value="Replied" <?= $msg['status'] === 'Replied' ? 'selected' : ''; ?>>Replied</option>
                            </select>
                        </form>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="https://api.whatsapp.com/send?phone=91<?= preg_replace('/[^0-9]/', '', $msg['phone']); ?>&text=<?= urlencode('Hello ' . $msg['full_name'] . ', thanks for contacting JY TOUR and TRAVELS Lucknow.'); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-light border text-success" title="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                            <form action="<?= e(BASE_URL); ?>/admin/messages.php" method="POST" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="message_id" value="<?= $msg['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-light border text-danger confirm-delete" data-item="message from <?= e($msg['full_name']); ?>">
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
