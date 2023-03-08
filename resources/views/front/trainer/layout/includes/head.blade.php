<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Block :: @yield('title')</title>
    <link rel="icon" href="{{asset('front/trainer/images/favicon.png')}}" sizes="32x32" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.css">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/jquery.dataTables.min.css') }}">
    <link href="{{ asset('/theme/vendors/general/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{asset('../front/css/pnotify.custom.min.css')}}">
    <link rel="stylesheet" href="{{ asset('../front/bundleCss/bundleTrainer.css') }}">    
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/developer.css') }}">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
    <link href="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/css/bootstrap-timepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    @if(!empty($metaTitle))
    <title>{{ $metaTitle }}</title>    
    @else
    <title>Trainingblock - Find you fitness partner</title>
    @endif

    @if(!empty($metaDescription))
    <meta name="description" content = "<?php echo $metaDescription; ?>" />    
    @else
    <meta name="description" content="Helping athletes achieve their dreams by connecting the entire athletic community. We’ll help you get more out of each training block!" />
    @endif

    @if(!empty($metakeywords))
    <meta name="keywords" content = "<?php echo $metakeywords; ?>" />
    @else
    <meta name="keywords" content="sports conditioning, athlete training, training an athlete, strength training for athletes, athlete conditioning workout"/>
    @endif   

    @if(!empty($metaRobots))
    <meta name="robots" content = "<?php echo $metaRobots; ?>" />    
    @else
    <meta name="robots" content="index,follow" />
    @endif

    @if(!empty($metaCanonical))
    <link rel="canonical" href = "<?php echo $metaCanonical; ?>" />
    @else
    <link rel="canonical" href="https://www.trainingblockusa.com/" />
    @endif

    @if(!empty($metaOgLocale))
    <meta property="og:locale" content = "<?php echo $metaOgLocale; ?>" />
    @else
    <meta property="og:locale" content="en_US" />
    @endif
    
    @if(!empty($metaOgType))
    <meta property="og:type" content = "<?php echo $metaOgType; ?>" />
    @else
    <meta property="og:type" content="https://www.trainingblockusa.com/" />
    @endif

    @if(!empty($metaOgTitle))
    <meta property="og:title" content = "<?php echo $metaOgTitle; ?>" />
    @else
    <meta property="og:title" content="Training block" />
    @endif

    @if(!empty($metaOgDescription))
    <meta property="og:description" content = "<?php echo $metaDescription; ?>" />
    @else
    <meta property="og:description" content="Helping athletes achieve their dreams by connecting the entire athletic community. We’ll help you get more out of each training block!" />
    @endif

    @if(!empty($metaOgUrl))
    <meta property="og:url" content = "<?php echo $metaOgUrl; ?>" />
    @else
    <meta property="og:url" content="https://www.trainingblockusa.com" />
    @endif

    @if(!empty($metaOgUrlSiteName))
    <meta property="og:site_name" content = "<?php echo $metaOgUrlSiteName; ?>" />
    @else
    <meta property="og:site_name" content="Trainingblock" />
    @endif

    @if(!empty($metaOgImage))
    <meta property="og:image" content = "<?php echo $metaOgImage; ?>" />
    @else
    <meta property="og:image" content="https://www.trainingblockusa.com/public/images/julie2.jpg" />
    @endif

    @if(!empty($metaTwitterCard))
    <meta name="twitter:card" content= "<?php echo $metaTwitterCard; ?>" />
    @else
    <meta name="twitter:card" content="summary_large_image" />
    @endif

    @if(!empty($metaTwitterTitle))
    <meta name="twitter:title" content = "<?php echo $metaTwitterTitle; ?>" />
    @else
    <meta name="twitter:title" content="Trainingblock - Find you fitness partner" />
    @endif

    @if(!empty($metaTwitterDescription))
    <meta name="twitter:description" content = "<?php echo $metaTwitterDescription; ?>" />
    @else
    <meta name="twitter:description" content="Helping athletes achieve their dreams by connecting the entire athletic community. We’ll help you get more out of each training block!" />
    @endif

    @if(!empty($metaTwitterImage))
    <meta name="twitter:image" content = "<?php echo $metaDescription; ?>"/>
    @else
    <meta name="twitter:image" content="https://www.trainingblockusa.com/public/images/julie2.jpg" />
    @endif
  
<script type="application/ld+json">
{"@context": "https://schema.org",
"@type": "Organization","name": "Trainingblockusa","url": "https://www.trainingblockusa.com/",
"logo": "https://www.trainingblockusa.com/public/images/logo.png",
"contactPoint": [{ "@type": "ContactPoint",
"telephone": "(407) 864-2606",
"contactType": "customer service" }],
"sameAs": ["https://www.facebook.com/trainingblockusa",
"https://www.linkedin.com/company/training-block-usa/", "https://www.instagram.com/training.block/"]}
</script>
    
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NZC4WTH');</script>
<!-- End Google Tag Manager -->

