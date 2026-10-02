<?php
/**
 * JY TOUR and TRAVELS - Navbar Component
 */
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!-- Top Utility & Permit Info Bar -->
<div class="top-info-bar d-none d-md-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-5">
                <span class="permit-top-badge">
                    <i class="fa-solid fa-certificate"></i> <?= e(get_setting('all_india_permit_badge', 'WITH ALL INDIA PERMIT')); ?>
                </span>
                <span class="ms-3 text-light opacity-75">
                    <i class="fa-solid fa-location-dot text-warning me-1"></i> <?= e(get_setting('address_short', '8/273 Rajni Khand, Sharda Nagar, Lucknow')); ?>
                </span>
            </div>
            <div class="col-lg-6 col-md-7 text-end">
                <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="me-3">
                    <i class="fa-solid fa-phone text-warning me-1"></i> <?= e(get_setting('phone', '+91 9450150697')); ?>
                </a>
                <a href="mailto:<?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>" class="me-3">
                    <i class="fa-solid fa-envelope text-warning me-1"></i> <?= e(get_setting('email', 'jytourandtravels32@gmail.com')); ?>
                </a>
                <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="text-success fw-bold">
                    <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Sticky Navbar -->
<nav class="navbar navbar-expand-lg jy-navbar sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="<?= e(BASE_URL); ?>/index.php">
            <img src="<?= e(BASE_URL); ?>/assets/images/logo.svg" alt="JY TOUR and TRAVELS" class="img-fluid" width="230" height="50">
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#jyNavMenu" aria-controls="jyNavMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav Links -->
        <div class="collapse navbar-collapse" id="jyNavMenu">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'about' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'fleet' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/fleet.php">Our Fleet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'services' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/services.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'gallery' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/gallery.php">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact' ? 'active' : ''; ?>" href="<?= e(BASE_URL); ?>/contact.php">Contact Us</a>
                </li>
            </ul>

            <!-- Header Action CTAs -->
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="nav-phone-btn d-none d-xl-inline-flex">
                    <i class="fa-solid fa-phone"></i>
                    <span><?= e(get_setting('phone', '+91 9450150697')); ?></span>
                </a>
                <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-success rounded-pill px-3 py-2 fw-bold d-none d-sm-inline-flex align-items-center gap-1">
                    <i class="fa-brands fa-whatsapp fs-5"></i>
                    <span class="d-none d-md-inline">WhatsApp</span>
                </a>
                <a href="<?= e(BASE_URL); ?>/booking.php" class="nav-cta-btn">
                    <i class="fa-solid fa-calendar-check me-1"></i> Book a Vehicle
                </a>
            </div>
        </div>
    </div>
</nav>
