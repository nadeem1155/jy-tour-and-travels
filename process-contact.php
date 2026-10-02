<?php
/**
 * JY TOUR and TRAVELS - Contact Form Processor
 */
require_once __DIR__ . '/config/config.php';

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        exit;
    }
    header('Location: ' . BASE_URL . '/contact.php');
    exit;
}

// 1. Verify CSRF Token
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    $errorMsg = 'Security token expired. Please refresh the page and try again.';
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $errorMsg]);
        exit;
    }
    set_flash('danger', $errorMsg);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/contact.php')));
    exit;
}

// 2. Sanitize and Validate
$fullName = sanitize($_POST['full_name'] ?? '');
$phone    = sanitize($_POST['phone'] ?? '');
$email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL) ? trim($_POST['email']) : '';
$subject  = sanitize($_POST['subject'] ?? 'General Enquiry');
$message  = sanitize($_POST['message'] ?? '');

$errors = [];
if (empty($fullName) || mb_strlen($fullName) < 3) {
    $errors[] = 'Please enter your full name (minimum 3 characters).';
}

$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (empty($phone) || strlen($cleanPhone) < 10) {
    $errors[] = 'Please provide a valid 10-digit mobile contact number.';
}

if (empty($message) || mb_strlen($message) < 5) {
    $errors[] = 'Please enter your message or question.';
}

if (!empty($errors)) {
    $errMsg = implode(' ', $errors);
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $errMsg]);
        exit;
    }
    set_flash('danger', $errMsg);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/contact.php')));
    exit;
}

// 3. Save into contact_messages Table
$pdo = get_db_connection();
if ($pdo) {
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (full_name, phone, email, subject, message, status, ip_address, created_at)
                                VALUES (:full_name, :phone, :email, :subject, :message, 'New', :ip_address, NOW())");
        $stmt->execute([
            ':full_name'  => $fullName,
            ':phone'      => $phone,
            ':email'      => $email ?: null,
            ':subject'    => $subject,
            ':message'    => $message,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? ''
        ]);
    } catch (PDOException $e) {
        error_log("Contact DB save error: " . $e->getMessage());
    }
}

// 4. Send Email Notification
$adminEmail = get_setting('email', 'jytourandtravels32@gmail.com');
$emailSubject = "New Website Contact Message from {$fullName}";
$emailBody = "
<html>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='background: #074e28; color: #fff; padding: 15px; border-bottom: 4px solid #ffd700;'>
        <h2 style='margin: 0;'>JY TOUR and TRAVELS - Contact Message</h2>
    </div>
    <div style='padding: 20px; background: #fafafa; border: 1px solid #ddd;'>
        <p><strong>Name:</strong> {$fullName}</p>
        <p><strong>Phone:</strong> <a href='tel:{$phone}'>{$phone}</a></p>
        <p><strong>Email:</strong> " . ($email ?: 'Not provided') . "</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
    </div>
</body>
</html>
";

send_notification_email($adminEmail, $emailSubject, $emailBody);

$successMsg = "Thank you {$fullName}! Your message has been sent successfully. We will get back to you shortly.";

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => $successMsg]);
    exit;
}

set_flash('success', $successMsg);
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/contact.php')));
exit;
