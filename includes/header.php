<?php
/**
 * JY TOUR and TRAVELS - Header Component
 */
require_once __DIR__ . '/../config/config.php';

$pageTitle = $pageTitle ?? get_setting('meta_title');
$pageDesc  = $pageDesc ?? get_setting('meta_description');
$pageKeywords = $pageKeywords ?? get_setting('meta_keywords');
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?></title>
    <meta name="description" content="<?= e($pageDesc); ?>">
    <meta name="keywords" content="<?= e($pageKeywords); ?>">
    <meta name="author" content="JY TOUR and TRAVELS">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e(BASE_URL . '/' . ($currentPage === 'index' ? '' : $currentPage . '.php')); ?>">
    <meta property="og:title" content="<?= e($pageTitle); ?>">
    <meta property="og:description" content="<?= e($pageDesc); ?>">
    <meta property="og:image" content="<?= e(BASE_URL . '/assets/images/ad_reference.jpg'); ?>">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle); ?>">
    <meta name="twitter:description" content="<?= e($pageDesc); ?>">
    <meta name="twitter:image" content="<?= e(BASE_URL . '/assets/images/ad_reference.jpg'); ?>">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800;900&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= e(BASE_URL); ?>/assets/css/style.css">

    <!-- Schema.org JSON-LD Structured Data for LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "TravelAgency",
      "name": "JY TOUR and TRAVELS",
      "image": "<?= e(BASE_URL); ?>/assets/images/ad_reference.jpg",
      "telephone": "+919450150697",
      "email": "jytourandtravels32@gmail.com",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "8/273 Rajni Khand, Sharda Nagar",
        "addressLocality": "Lucknow",
        "addressRegion": "Uttar Pradesh",
        "postalCode": "226002",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 26.779774,
        "longitude": 80.916892
      },
      "url": "<?= e(BASE_URL); ?>",
      "priceRange": "$$",
      "description": "<?= e(get_setting('about_intro')); ?>",
      "makesOffer": [
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Luxury Sedan Rental (Dzire, Aura)"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Luxury MUV Rental (Ertiga)"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Luxury SUV Rental (Toyota Innova Crysta)"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Luxury Bus & Coach Rental"}},
        {"@type": "Offer", "itemOffered": {"@type": "Service", "name": "All India Permit Tourist Transportation"}}
      ]
    }
    </script>
</head>
<body>
