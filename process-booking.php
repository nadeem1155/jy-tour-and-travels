<?php
/**
 * JY TOUR and TRAVELS - Booking Enquiry Processor
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
    header('Location: ' . BASE_URL . '/index.php#booking');
    exit;
}

// 1. Verify CSRF Token
$token = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($token)) {
    $errorMsg = 'Security validation failed. Please refresh the page and try again.';
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $errorMsg]);
        exit;
    }
    set_flash('danger', $errorMsg);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/index.php#booking')));
    exit;
}

// 2. Collect and Sanitize Inputs
$fullName    = sanitize($_POST['full_name'] ?? '');
$phone       = sanitize($_POST['phone'] ?? '');
$email       = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL) ? trim($_POST['email']) : '';
$pickup      = sanitize($_POST['pickup_location'] ?? '');
$destination = sanitize($_POST['destination'] ?? '');
$travelDate  = sanitize($_POST['travel_date'] ?? '');
$returnDate  = !empty($_POST['return_date']) ? sanitize($_POST['return_date']) : null;
$vehicleName = sanitize($_POST['vehicle_name'] ?? '');
$passengers  = (int)($_POST['passengers'] ?? 1);
$tripType    = sanitize($_POST['trip_type'] ?? 'Outstation');
$reqs        = sanitize($_POST['additional_requirements'] ?? '');

$allowedVehicles = ['Dzire', 'Aura', 'Ertiga', 'Toyota Innova Crysta', 'Luxury Bus'];
$allowedTripTypes = ['One Way', 'Round Trip', 'Local', 'Outstation'];

// 3. Validation
$errors = [];
if (empty($fullName) || mb_strlen($fullName) < 3) {
    $errors[] = 'Please provide your full name (minimum 3 characters).';
}

$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (empty($phone) || strlen($cleanPhone) < 10) {
    $errors[] = 'Please provide a valid 10-digit mobile contact number.';
}

if (empty($pickup)) {
    $errors[] = 'Please enter your pickup location.';
}

if (empty($destination)) {
    $errors[] = 'Please enter your travel destination.';
}

if (empty($travelDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $travelDate)) {
    $errors[] = 'Please select a valid travel date.';
}

if (!empty($returnDate) && $returnDate < $travelDate) {
    $errors[] = 'Return date cannot be earlier than the travel date.';
}

if (empty($vehicleName) || !in_array($vehicleName, $allowedVehicles)) {
    $errors[] = 'Please choose a valid vehicle type from the list.';
}

if (!in_array($tripType, $allowedTripTypes)) {
    $tripType = 'Outstation';
}

if ($passengers < 1 || $passengers > 60) {
    $passengers = 1;
}

if (!empty($errors)) {
    $errMsg = implode(' ', $errors);
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $errMsg]);
        exit;
    }
    set_flash('danger', $errMsg);
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/index.php#booking')));
    exit;
}

// 4. Generate Booking Reference
$bookingNumber = generate_booking_number();
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

// 5. Save to MySQL Database
$saved = false;
$pdo = get_db_connection();
if ($pdo) {
    try {
        // Resolve vehicle ID if available
        $vId = null;
        $vStmt = $pdo->prepare("SELECT id FROM vehicles WHERE name = :name LIMIT 1");
        $vStmt->execute([':name' => $vehicleName]);
        $vRow = $vStmt->fetch();
        if ($vRow) {
            $vId = (int)$vRow['id'];
        }

        $sql = "INSERT INTO bookings (
                    booking_number, full_name, phone, email, pickup_location,
                    destination, travel_date, return_date, vehicle_id, vehicle_name,
                    passengers, trip_type, additional_requirements, status, ip_address, created_at
                ) VALUES (
                    :booking_number, :full_name, :phone, :email, :pickup_location,
                    :destination, :travel_date, :return_date, :vehicle_id, :vehicle_name,
                    :passengers, :trip_type, :additional_requirements, 'New', :ip_address, NOW()
                )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':booking_number'           => $bookingNumber,
            ':full_name'                => $fullName,
            ':phone'                    => $phone,
            ':email'                    => $email ?: null,
            ':pickup_location'          => $pickup,
            ':destination'              => $destination,
            ':travel_date'              => $travelDate,
            ':return_date'              => $returnDate ?: null,
            ':vehicle_id'               => $vId,
            ':vehicle_name'             => $vehicleName,
            ':passengers'               => $passengers,
            ':trip_type'                => $tripType,
            ':additional_requirements'  => $reqs ?: null,
            ':ip_address'               => $ipAddress
        ]);
        $saved = true;
    } catch (PDOException $e) {
        error_log("Booking DB save error: " . $e->getMessage());
    }
}

// 6. Send Notification Email to Business Email
$adminEmail = get_setting('email', 'jytourandtravels32@gmail.com');
$emailSubject = "New Vehicle Enquiry [{$bookingNumber}] - {$fullName} ({$vehicleName})";
$emailBody = "
<html>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='background: #074e28; color: #fff; padding: 15px; border-bottom: 4px solid #ffd700;'>
        <h2 style='margin: 0;'>JY TOUR and TRAVELS - New Vehicle Booking Enquiry</h2>
    </div>
    <div style='padding: 20px; background: #fafafa; border: 1px solid #ddd;'>
        <p><strong>Booking Reference:</strong> {$bookingNumber}</p>
        <p><strong>Customer Name:</strong> {$fullName}</p>
        <p><strong>Contact Phone:</strong> <a href='tel:{$phone}'>{$phone}</a></p>
        <p><strong>Email Address:</strong> " . ($email ?: 'Not provided') . "</p>
        <hr style='border: none; border-top: 1px solid #ccc;'>
        <p><strong>Selected Vehicle:</strong> {$vehicleName}</p>
        <p><strong>Trip Type:</strong> {$tripType}</p>
        <p><strong>Pickup Location:</strong> {$pickup}</p>
        <p><strong>Destination:</strong> {$destination}</p>
        <p><strong>Travel Date:</strong> {$travelDate}</p>
        <p><strong>Return Date:</strong> " . ($returnDate ?: 'One-way trip') . "</p>
        <p><strong>Number of Passengers:</strong> {$passengers}</p>
        <p><strong>Additional Notes:</strong> " . nl2br(htmlspecialchars($reqs ?: 'None')) . "</p>
        <p><strong>All India Permit:</strong> Included</p>
    </div>
    <div style='padding: 10px; font-size: 12px; color: #777;'>
        Sent automatically from JY TOUR and TRAVELS Website Booking System.
    </div>
</body>
</html>
";

send_notification_email($adminEmail, $emailSubject, $emailBody);

$successMsg = "Thank you {$fullName}! Your booking enquiry has been registered with reference {$bookingNumber}. Our team will contact you shortly on {$phone} to finalize the arrangements.";

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode([
        'success'        => true,
        'message'        => $successMsg,
        'booking_number' => $bookingNumber
    ]);
    exit;
}

set_flash('success', $successMsg);
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? (BASE_URL . '/index.php#booking')));
exit;
