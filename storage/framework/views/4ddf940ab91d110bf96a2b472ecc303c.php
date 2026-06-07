<?php
    $seoSettings = $settingModel ?? $setting ?? null;
    $siteName = data_get($seoSettings, 'company_name')
        ?: data_get($seoSettings, 'site_name')
        ?: config('app.name', 'DoctorsProfile');
    $defaultTitle = data_get($seoSettings, 'meta_title') ?: $siteName;
    $title = trim($__env->yieldContent('meta_title', $__env->yieldContent('title', $defaultTitle)));
    $description = trim($__env->yieldContent(
        'meta_description',
        data_get($seoSettings, 'meta_description')
            ?: 'Find doctors, specialists, clinics, and appointment options.'
    ));
    $keywords = trim($__env->yieldContent('meta_keywords', data_get($seoSettings, 'keywords', '')));
    $robots = trim($__env->yieldContent('robots', data_get($seoSettings, 'robots') ?: 'index, follow'));
    $canonical = trim($__env->yieldContent('canonical', url()->current()));
    $ogTitle = trim($__env->yieldContent('ogtitle', $title));
    $ogDescription = trim($__env->yieldContent('ogdescription', $description));
    $ogType = trim($__env->yieldContent('ogtype', data_get($seoSettings, 'ogtype') ?: 'website'));
    $ogUrl = trim($__env->yieldContent('ogurl', $canonical));
    $explicitOgImage = trim($__env->yieldContent('ogimage', ''));
    $contextOgImage = data_get($post ?? null, 'cover_image')
        ?: data_get($doctor ?? null, 'photo')
        ?: data_get($seoSettings, 'ogimage')
        ?: data_get($seoSettings, 'logo')
        ?: 'images/og-default.jpg';
    $rawOgImage = $explicitOgImage !== '' ? $explicitOgImage : $contextOgImage;
    $ogImage = preg_match('/^https?:\/\//i', $rawOgImage) || str_starts_with($rawOgImage, '//')
        ? $rawOgImage
        : url(ltrim($rawOgImage, '/'));
    $twitterCard = trim($__env->yieldContent('twitter_card', 'summary_large_image'));
    $schemaOrganization = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteName,
        'url' => url('/'),
    ];
    $schemaWebsite = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => url('/'),
    ];
?>

<title><?php echo e($title); ?></title>
<meta name="description" content="<?php echo e($description); ?>">
<?php if($keywords !== ''): ?>
    <meta name="keywords" content="<?php echo e($keywords); ?>">
<?php endif; ?>
<meta name="robots" content="<?php echo e($robots); ?>">
<link rel="canonical" href="<?php echo e($canonical); ?>">
<meta property="og:title" content="<?php echo e($ogTitle); ?>">
<meta property="og:description" content="<?php echo e($ogDescription); ?>">
<meta property="og:type" content="<?php echo e($ogType); ?>">
<meta property="og:url" content="<?php echo e($ogUrl); ?>">
<meta property="og:image" content="<?php echo e($ogImage); ?>">
<meta name="twitter:card" content="<?php echo e($twitterCard); ?>">
<meta name="twitter:title" content="<?php echo e($ogTitle); ?>">
<meta name="twitter:description" content="<?php echo e($ogDescription); ?>">
<meta name="twitter:image" content="<?php echo e($ogImage); ?>">
<?php echo $__env->make('partials.analytics-head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script type="application/ld+json"><?php echo json_encode($schemaOrganization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 512) ?></script>
<script type="application/ld+json"><?php echo json_encode($schemaWebsite, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 512) ?></script>
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        event: 'page_metadata',
        page_title: <?php echo json_encode($title, 15, 512) ?>,
        page_description: <?php echo json_encode($description, 15, 512) ?>,
        page_canonical: <?php echo json_encode($canonical, 15, 512) ?>,
        page_type: <?php echo json_encode($ogType, 15, 512) ?>,
        site_name: <?php echo json_encode($siteName, 15, 512) ?>,
    });
</script>
<?php echo $__env->yieldContent('meta'); ?>
<?php echo $__env->yieldContent('data_layer'); ?>
<?php echo $__env->yieldContent('structured_data'); ?>
<?php /**PATH D:\doctorprofiles\resources\views/partials/seo.blade.php ENDPATH**/ ?>