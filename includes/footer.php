<?php
/**
 * JY TOUR and TRAVELS - Footer Component
 */
$currentYear = date('Y');
?>
<!-- Footer -->
<footer class="jy-footer">
    <div class="container">
        <div class="row g-4 justify-content-between">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <img src="<?= e(BASE_URL); ?>/assets/images/logo-white.svg" alt="JY TOUR and TRAVELS" class="img-fluid" width="230" height="50">
                </div>
                <p class="footer-desc mt-3">
                    "Travel with comfort and convenience."
                </p>
                <p class="text-white-50 small mb-4">
                    <?= e(get_setting('about_intro', 'JY TOUR and TRAVELS is a Lucknow-based travel and transportation service providing a range of passenger vehicles for local, intercity and travel requirements.')); ?>
                </p>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 bg-dark rounded-pill border border-warning">
                    <i class="fa-solid fa-shield-halved text-warning"></i>
                    <span class="text-warning fw-bold small"><?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?></span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="<?= e(BASE_URL); ?>/index.php"><i class="fa-solid fa-angle-right"></i> Home</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/about.php"><i class="fa-solid fa-angle-right"></i> About Us</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php"><i class="fa-solid fa-angle-right"></i> Our Fleet</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/services.php"><i class="fa-solid fa-angle-right"></i> Services</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/gallery.php"><i class="fa-solid fa-angle-right"></i> Gallery</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/contact.php"><i class="fa-solid fa-angle-right"></i> Contact Us</a></li>
                </ul>
            </div>

            <!-- Vehicles Fleet -->
            <div class="col-lg-3 col-md-6 col-6">
                <h5 class="footer-heading">Vehicles Available</h5>
                <ul class="footer-links">
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php?vehicle=dzire"><i class="fa-solid fa-car"></i> Dzire (Sedan)</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php?vehicle=aura"><i class="fa-solid fa-car"></i> Aura (Sedan)</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php?vehicle=ertiga"><i class="fa-solid fa-van-shuttle"></i> Ertiga (Luxury MUV)</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php?vehicle=toyota-innova-crysta"><i class="fa-solid fa-car-side"></i> Toyota Innova Crysta</a></li>
                    <li><a href="<?= e(BASE_URL); ?>/fleet.php?vehicle=luxury-bus"><i class="fa-solid fa-bus"></i> Luxury Bus</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">Contact Details</h5>
                
                <div class="footer-contact-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <div>
                        <strong class="d-block text-white">Address:</strong>
                        <span><?= e(get_setting('address', '8/273 Rajni Khand, Sharda Nagar, Lucknow, Uttar Pradesh, India')); ?></span>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <div>
                        <strong class="d-block text-white">Phone:</strong>
                        <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>"><?= e(get_setting('phone', '+91 9450150697')); ?></a>
                    </div>
                </div>

                <div class="footer-contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <div>
                        <strong class="d-block text-white">Email:</strong>
                        <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>"><?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?></a>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-warning btn-sm rounded-pill w-100 py-2 fw-bold">
                        <i class="fa-brands fa-whatsapp me-1 text-success"></i> WhatsApp Us Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Sub-footer -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-start mb-2 mb-md-0">
                    &copy; <?= e($currentYear); ?> <strong>JY TOUR and TRAVELS</strong>. All Rights Reserved.
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <a href="<?= e(BASE_URL); ?>/admin/login.php" class="text-white-50 text-decoration-none small">
                        <i class="fa-solid fa-lock me-1"></i> Admin Portal
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Lightbox Modal for Gallery Images -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalCaption" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark border-0">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title text-white" id="galleryModalCaption">Vehicle View</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="galleryModalImg" class="img-fluid rounded modal-gallery-img" alt="Enlarged view">
            </div>
        </div>
    </div>
</div>

<!-- Mobile Sticky Action Bar -->
<?php require_once __DIR__ . '/mobile_cta.php'; ?>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JavaScript -->
<script src="<?= e(BASE_URL); ?>/assets/js/main.js"></script>

</body>
</html>
