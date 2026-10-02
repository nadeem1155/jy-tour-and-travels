<?php
/**
 * JY TOUR and TRAVELS - Contact Us Page
 */
$pageTitle = 'Contact Us | JY TOUR and TRAVELS - Rajni Khand, Sharda Nagar, Lucknow';
$pageDesc  = 'Contact JY TOUR and TRAVELS in Lucknow. Phone: +91 9450150697, Email: jytourandtravels32@gmail.com, Address: 8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh.';
$pageKeywords = 'contact JY tour and travels, Lucknow travel agency contact, car rental contact Lucknow, phone number JY travels';

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$flash = get_flash();
?>

<!-- Contact Header -->
<div class="py-5" style="background: linear-gradient(135deg, var(--jy-primary-deep) 0%, var(--jy-primary-dark) 100%); color: #fff; border-bottom: 4px solid var(--jy-gold);">
    <div class="container text-center">
        <span class="permit-banner-badge mb-2"><i class="fa-solid fa-headset me-1"></i> Get In Touch</span>
        <h1 class="display-5 fw-bold text-white mb-2">Contact Us</h1>
        <p class="text-white-50 lead mb-0">We Are Ready to Assist You with Your Travel Plans</p>
    </div>
</div>

<section class="section-py">
    <div class="container">
        <?php if ($flash): ?>
        <div class="alert alert-jy-<?= e($flash['type']); ?> alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <?= $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row g-5">
            <!-- Left Contact Info -->
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <span class="section-eyebrow mb-3">Head Office</span>
                    <h3 class="fw-bold mb-4 text-dark"><?= e(get_setting('business_name', 'JY TOUR and TRAVELS')); ?></h3>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <div class="contact-channel-details">
                            <h5>Office Address</h5>
                            <p><?= e(get_setting('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India')); ?></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="contact-channel-details">
                            <h5>Helpline Phone</h5>
                            <p><a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="fw-bold text-dark"><?= e(get_setting('phone', '+91 9450150697')); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-envelope"></i></div>
                        <div class="contact-channel-details">
                            <h5>Official Email</h5>
                            <p><a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-brands fa-whatsapp text-success"></i></div>
                        <div class="contact-channel-details">
                            <h5>WhatsApp Chat</h5>
                            <p><a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="text-success fw-bold">Message on WhatsApp</a></p>
                        </div>
                    </div>

                    <div class="contact-channel-item">
                        <div class="contact-channel-icon"><i class="fa-solid fa-clock"></i></div>
                        <div class="contact-channel-details">
                            <h5>Booking & Operations Hours</h5>
                            <p><?= e(get_setting('business_hours', '24x7 Booking & Customer Support Available')); ?></p>
                            <small class="text-muted">(Configurable via admin panel)</small>
                        </div>
                    </div>

                    <div class="mt-4 pt-2 d-flex gap-2">
                        <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="btn-jy-primary flex-grow-1 text-center justify-content-center">
                            <i class="fa-solid fa-phone"></i> Call Now
                        </a>
                        <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success px-4 py-3 fw-bold rounded-3">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h3 class="fw-bold mb-2">Send Us a Message</h3>
                    <p class="text-muted small mb-4">Have questions about tariffs, route options, or special vehicle requests? Leave your message below.</p>

                    <form action="<?= e(BASE_URL); ?>/process-contact.php" method="POST">
                        <?= csrf_field(); ?>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="c_full_name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_full_name" name="full_name" placeholder="Full name" required>
                            </div>

                            <div class="col-md-6">
                                <label for="c_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="c_phone" name="phone" placeholder="10-digit mobile number" pattern="[0-9]{10}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="c_email" class="form-label">Email Address <span class="text-muted small">(Optional)</span></label>
                                <input type="email" class="form-control" id="c_email" name="email" placeholder="name@example.com">
                            </div>

                            <div class="col-md-6">
                                <label for="c_subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="c_subject" name="subject" placeholder="e.g. Outstation enquiry, Tour Package">
                            </div>

                            <div class="col-12">
                                <label for="c_message" class="form-label">Your Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="c_message" name="message" rows="5" placeholder="Please write your questions or travel requirements..." required></textarea>
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn-jy-gold w-100 py-3 justify-content-center">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Map Embed Section -->
        <div class="mt-5">
            <div class="section-title-wrap mb-4">
                <span class="section-eyebrow">Location Map</span>
                <h3 class="fw-bold">Find Us in Lucknow</h3>
            </div>
            <div class="map-container">
                <iframe src="<?= e(get_setting('google_map_embed')); ?>" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="JY Tour and Travels Location"></iframe>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
