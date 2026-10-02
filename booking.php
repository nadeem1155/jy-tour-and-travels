<?php
/**
 * JY TOUR and TRAVELS - Booking & Enquiry Page
 */
$pageTitle = 'Book a Vehicle | Online Reservation | JY TOUR and TRAVELS';
$pageDesc  = 'Book your car or luxury bus with All India Permit. Dzire, Aura, Ertiga, Toyota Innova Crysta, and Luxury Bus available for local & outstation travel from Lucknow.';
$pageKeywords = 'book cab Lucknow, car rental booking Lucknow, Innova Crysta booking, Ertiga outstation Lucknow, luxury bus booking';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$selectedVehicle = sanitize($_GET['vehicle'] ?? '');
$flash = get_flash();
?>

<!-- Booking Hero Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-calendar-check me-1"></i> Quick Reservation</span>
        <h1 class="display-5 fw-bold text-white mb-2">Book Your Vehicle</h1>
        <p class="text-white-50 lead mb-0">Local, Intercity & Interstate Outstation Travel with All India Permit</p>
    </div>
</div>

<section class="section-py section-bg-light">
    <div class="container">
        <?php if ($flash): ?>
        <div class="alert alert-jy-<?= e($flash['type']); ?> alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <?= $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="booking-section-wrap">
            <div class="row g-0">
                <!-- Form Column -->
                <div class="col-lg-8">
                    <div class="booking-form-header">
                        <h3><i class="fa-solid fa-car-side me-2"></i> Vehicle Booking Enquiry Form</h3>
                        <p class="mb-0 text-white-50 small">Fill the travel details below to check availability and obtain an accurate quote.</p>
                    </div>

                    <div class="booking-form-body">
                        <div id="bookingAlertBox" class="d-none"></div>

                        <form id="jyBookingForm" action="<?= e(BASE_URL); ?>/process-booking.php" method="POST">
                            <?= csrf_field(); ?>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Enter your full name" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="e.g. 9450150697" pattern="[0-9]{10}" title="Please enter a 10-digit mobile number" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-muted small">(Optional)</span></label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                                </div>

                                <div class="col-md-6">
                                    <label for="vehicle_name" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="vehicle_name" name="vehicle_name" required>
                                        <option value="" disabled <?= empty($selectedVehicle) ? 'selected' : ''; ?>>-- Select Vehicle --</option>
                                        <option value="Dzire" <?= stripos($selectedVehicle, 'dzire') !== false ? 'selected' : ''; ?>>Dzire (Luxury Sedan)</option>
                                        <option value="Aura" <?= stripos($selectedVehicle, 'aura') !== false ? 'selected' : ''; ?>>Aura (Luxury Sedan / Hatch Back)</option>
                                        <option value="Ertiga" <?= stripos($selectedVehicle, 'ertiga') !== false ? 'selected' : ''; ?>>Ertiga (Luxury MUV)</option>
                                        <option value="Toyota Innova Crysta" <?= (stripos($selectedVehicle, 'innova') !== false || stripos($selectedVehicle, 'crysta') !== false) ? 'selected' : ''; ?>>Toyota Innova Crysta (Luxury SUV / MUV)</option>
                                        <option value="Luxury Bus" <?= stripos($selectedVehicle, 'bus') !== false ? 'selected' : ''; ?>>Luxury Bus (Tour Coach)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="pickup_location" class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="pickup_location" name="pickup_location" placeholder="e.g. Rajni Khand, Lucknow" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="destination" class="form-label">Destination <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="destination" name="destination" placeholder="e.g. Varanasi, Ayodhya, Delhi, Outstation" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="travel_date" class="form-label">Travel Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="travel_date" name="travel_date" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="return_date" class="form-label">Return Date <span class="text-muted small">(For Round Trip)</span></label>
                                    <input type="date" class="form-control" id="return_date" name="return_date">
                                </div>

                                <div class="col-md-6">
                                    <label for="trip_type" class="form-label">Trip Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="trip_type" name="trip_type" required>
                                        <option value="Outstation" selected>Outstation</option>
                                        <option value="One Way">One Way</option>
                                        <option value="Round Trip">Round Trip</option>
                                        <option value="Local">Local Sightseeing / City</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="passengers" class="form-label">Number of Passengers <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="passengers" name="passengers" min="1" max="60" value="1" required>
                                </div>

                                <div class="col-12">
                                    <label for="additional_requirements" class="form-label">Additional Requirements</label>
                                    <textarea class="form-control" id="additional_requirements" name="additional_requirements" rows="3" placeholder="Specific pickup timing, luggage needs, route preferences, etc."></textarea>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn-jy-gold w-100 py-3 justify-content-center">
                                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Enquiry
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Column -->
                <div class="col-lg-4 p-4 p-lg-5 booking-side-info d-flex flex-column justify-content-between">
                    <div>
                        <h4><i class="fa-solid fa-headset text-warning me-2"></i> Direct Helpline</h4>
                        <p class="text-muted small">
                            Speak directly with our transport dispatch desk for instant vehicle booking and customized outstation tariffs.
                        </p>

                        <hr class="my-4">

                        <ul class="booking-contact-list">
                            <li>
                                <i class="fa-solid fa-phone"></i>
                                <div>
                                    <strong>Call Us Directly:</strong><br>
                                    <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="text-dark fw-bold"><?= e(get_setting('phone', '+91 9450150697')); ?></a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-brands fa-whatsapp text-success"></i>
                                <div>
                                    <strong>Instant WhatsApp:</strong><br>
                                    <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="text-success fw-bold">Chat on WhatsApp</a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-solid fa-envelope"></i>
                                <div>
                                    <strong>Email:</strong><br>
                                    <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>" class="text-muted"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a>
                                </div>
                            </li>

                            <li>
                                <i class="fa-solid fa-location-dot"></i>
                                <div>
                                    <strong>Office Address:</strong><br>
                                    <span class="text-muted"><?= e(get_setting('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India')); ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-4 p-3 bg-white rounded border border-warning">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-shield text-warning"></i>
                            <strong class="text-dark small"><?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?></strong>
                        </div>
                        <p class="text-muted mb-0 small">
                            Interstate travel across India with legal documentation and commercial taxi permits.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
