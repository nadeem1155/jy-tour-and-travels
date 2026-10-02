<?php
/**
 * JY TOUR and TRAVELS - Mobile Floating Action Bar
 * Fixed at bottom of mobile and tablet screens
 */
?>
<div class="mobile-sticky-cta d-lg-none" role="navigation" aria-label="Quick Mobile Actions">
    <a href="tel:<?= e(get_setting('phone_raw', '9450150697')); ?>" class="mobile-cta-btn call-btn">
        <i class="fa-solid fa-phone"></i>
        <span>Call Now</span>
    </a>
    <a href="<?= e(get_whatsapp_url()); ?>" target="_blank" rel="noopener noreferrer" class="mobile-cta-btn whatsapp-btn">
        <i class="fa-brands fa-whatsapp"></i>
        <span>WhatsApp</span>
    </a>
    <a href="<?= e(BASE_URL); ?>/booking.php" class="mobile-cta-btn book-btn">
        <i class="fa-solid fa-car-side"></i>
        <span>Book Now</span>
    </a>
</div>
