    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $routeName = Route::currentRouteName(); 
    $servicesValue = '';
    if(isset($request->services)){
        $servicesValue = $request->services;
    }
    if($routeName == "exploreservices" && $servicesValue != ''){ 
    //   print_r($request->services);
    // print_r($request->location);
    $categories = [
      "15"=>"Biomechanical and fitness testing",
      "1"=>"chiropractor",
      "18"=>"cycling coaching",
      "3"=>"sports coach",
      "2"=>"running coach",
      "17"=>"triathlon coach",
      "14"=>"running club",
      "7"=>"massage therapist",
      "5"=>"holistic doctor",
      "8"=>"nutrition services",
      "9"=>"gait analysis",
      "11"=>"physical therapy services",
      "16"=>"psychology services",
      "12"=>"Recovery Tools",
      "13"=>"strength training"
    ];  
    $descriptionText = "Training Block";
    
        if($request->location != ''){
            $descriptionText = "Access ".$categories[$request->services]." at TrainingblockUSA in ".$request->location." to achieve your fitness goals.";
     ?>   
            <title>Best <?=$categories[$request->services]?> in <?=$request->location?> - Training Block</title>
        <?php }else{ ?>
            <title>Training Block :: @yield('title')</title>
        <?php } ?> 
                <meta name="robots" content="index,follow"> 
                <meta name="googlebot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1"> 
                <meta name="bingbot" content="index,follow,max-snippet:-1,max-image-preview:large,max-video-preview:-1">  
       <?php if($request->services == '15'){ ?>
                <meta name="keywords" content="Best <?=$categories[$request->services]?> near me" />
                <?php }else if($request->services == '1'){ ?>
                <meta name="keywords" content="chiropractor near me,best chiropractor near me,walk in chiropractor near me,prenatal chiropractor near me,sports chiropractor near me,chiropractor near me,best chiropractor near me" />
            <?php }else if($request->services == '18'){ ?>
                <meta name="keywords" content="cycling coaching,cycling coaching services" />
            <?php }else if($request->services == '3'){ ?>
                <meta name="keywords" content="sports coach near me,life coach near me,coach near me,running coach near me,health coach near me,tennis coach near me" />
            <?php }else if($request->services == '2'){ ?>
                <meta name="keywords" content="running coach near me,running coaches near me,running coach certifications,run coaching certification,running coaches online,online running coach" />
            <?php }else if($request->services == '17'){ ?>
                <meta name="keywords" content="triathlon coach near me,triathlon coaches,triathlon coaching,triathlon coach,triathlon coaches near me,triathlon coaching near me" />
            <?php }else if($request->services == '14'){ ?>
                <meta name="keywords" content="Running club near me,running club near me,women's running club near me,find running club near me" />
            <?php }else if($request->services == '7'){ ?>
                <meta name="keywords" content="massage therapist near me,massage therapist,massage near me now,massage therapy near me,best massage therapist near me,massage therapists near me" />
            <?php }else if($request->services == '5'){ ?>
                <meta name="keywords" content="holistic doctor near me,black holistic doctor near me,find a holistic doctor near me,functional holistic doctor near me" />
            <?php }else if($request->services == '8'){ ?>
                <meta name="keywords" content="nutrition services near me,nutrition services specialist near me,nutrition coaching services near me,nutrition support services near me" />
            <?php }else if($request->services == '9'){ ?>
                <meta name="keywords" content="gait analysis near me,walking gait analysis near me,free gait analysis near me" />
            <?php }else if($request->services == '11'){ ?>
                <meta name="keywords" content="physical therapy services,in home physical therapy services near me,physical therapy services,physical therapy services near me,physical therapy billing services,home physical therapy services" />
            <?php }else if($request->services == '16'){ ?>
                <meta name="keywords" content="psychology services near me" />
            <?php }else if($request->services == '12'){ ?>

            <?php }else if($request->services == '13'){ ?>
                <meta name="keywords" content="strength training near me,strength training gyms near me,strength training classes near me,strength and agility training near me,strength and conditioning training near me,speed and strength training near me" />
            <?php } ?>

            <meta name="description" content="<?=$descriptionText?>" /> 
            <link rel="alternate" type="application/rss+xml" href=" https://www.trainingblockusa.com/sitemap.xml" /> 
            <meta property="og:url" content="https://www.trainingblockusa.com/" /> 
            <meta property="og:title" content="Best <?=$categories[$request->services]?> in <?=$request->location?> - Training Block" /> 
            <meta property="og:description" content="<?=$descriptionText?>" /> 
            <meta property="og:type" content="website" /> 
            <meta property="og:image" content="https://www.trainingblockusa.com/public/images/logo.png" /> 
            <meta property="og:image:width" content="500" /> 
            <meta property="og:image:type" content="image/png" /> 
            <meta property="og:site_name" content="Trainingblock" /> 
            <meta property="og:locale" content="en_US" /> 

            <meta property="twitter:url" content="https://www.trainingblockusa.com/" /> 
            <meta property="twitter:title" content="Best <?=$categories[$request->services]?> in <?=$request->location?> - Training Block" /> 
            <meta property="twitter:description" content="<?=$descriptionText?>" /> 
            <meta property="twitter:image" content="https://www.trainingblockusa.com/public/images/logo.png" /> 
            <meta property="twitter:domain" content="Trainingblock" /> 
            <meta property="twitter:card" content="summary_large_image" /> 
            <meta property="twitter:creator" content="@twitter" /> 
            <meta property="twitter:site" content="@twitter" /> 

    <?php }else{ ?>

    @if(!empty($metaTitle))
    <title>Training Block - {{ $metaTitle }}</title>    
    @else
    <title>Training Block - where athletes find what they need for training</title>
    @endif

    @if(!empty($metaDescription))
    <meta name="description" content = "<?php echo $metaDescription; ?>" />    
    @else
    <meta name="description" content="We give endurance athletes access to a local network of top sport performance experts, who can help them with performance goals" />
    @endif

    @if(!empty($metakeywords))
    <meta name="keywords" content = "<?php echo $metakeywords; ?>" />
    @else
    <meta name="keywords" content="Sports conditioning, athlete training, training an athlete, strength training for athletes, athlete conditioning workout"/>
    @endif   

    @if(!empty($metaRobots))
    <meta name="robots" content = "<?php echo $metaRobots; ?>" />    
    @else
    <meta name="robots" content="index,follow" />
    @endif

    @if(!empty($metaSlug))
    <link rel="canonical" href="https://www.trainingblockusa.com/blog-details/<?php echo $metaSlug; ?>" />    
    @else
    <link rel="canonical" href="<?php echo URL::current();  ?>" />
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

    @if(!empty($metaDescription))
    <meta property="og:description" content = "<?php echo $metaDescription; ?>" />
    @else
    <meta property="og:description" content="Helping athletes achieve their dreams by connecting the entire athletic community. Weâ€™ll help you get more out of each training block!" />
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
    <meta property="og:image" content = "{{ asset('front/images/resource/'.$metaOgImage) }}" />
    @else
      @if(!empty($metaOgBlogImage))
      <meta name="og:image" content = "{{ asset('sitebucket/blog/'.$metaOgBlogImage) }}"/>
      @elseif(!empty($metaOgProImage))
      <meta name="og:image" content = "{{ asset('front/profile/'.$metaOgProImage) }}"/>
      @else
      <meta property="og:image" content="https://www.trainingblockusa.com/public/images/banner.jpg" />
      @endif
    @endif

    <meta name="twitter:card" content="summary_large_image" />

    @if(!empty($metaTwitterTitle))
    <meta name="twitter:title" content = "<?php echo $metaTwitterTitle; ?>" />
    @else
    <meta name="twitter:title" content="Trainingblock - Find you fitness partner" />
    @endif

    @if(!empty($metaTwitterDescription))
    <meta name="twitter:description" content = "<?php echo $metaTwitterDescription; ?>" />
    @else
    <meta name="twitter:description" content="Helping athletes achieve their dreams by connecting the entire athletic community. Weâ€™ll help you get more out of each training block!" />
    @endif

    @if(!empty($metaTwitterImage))
    <meta name="twitter:image" content = "{{ asset('front/images/resource/'.$metaTwitterImage) }}"/>
    @else    
      @if(!empty($metaTwitterBlogImage))
      <meta name="twitter:image" content = "{{ asset('sitebucket/blog/'.$metaTwitterBlogImage) }}"/>
      @elseif(!empty($metaTwitterProImage))
      <meta name="twitter:image" content = "{{ asset('front/profile/'.$metaTwitterProImage) }}"/>
      @else
      <meta name="twitter:image" content="https://www.trainingblockusa.com/public/images/banner.jpg" />
      @endif
    @endif
    <?php } ?>

    
  
  @if(!empty($business_name))
  @if(!empty($first_name))
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "localbusiness",
      "image": <?php echo '"'.$trainerPhoto1.'"'; ?>,
      "name": <?php echo '"'.$metaTitle.'"'; ?>,
      "address": {
        "@type": "PostalAddress",
        "streetAddress": <?php echo '"'.$address.'"'; ?>,
        "addressLocality": <?php echo '"'.$address_localty.'"'; ?>,
        "addressRegion": <?php echo '"'.$region.'"'; ?>,
        "postalCode": <?php echo '"'.$postal_code.'"'; ?>,
        "addressCountry": <?php echo '"'.$region.'"'; ?>,
        "email" : <?php echo '"'.$email.'"'; ?>
      },
      "review": {
        "@type": "Review",
         "reviewRating": {
          "@type": "Rating",
          "ratingValue": <?php echo '"'.$ratings.'"'; ?>,  
          "bestRating": "5"
        },      
        "author": {
          "@type": "Person",
          "name": <?php echo '"'.$first_name.'"'; ?>
        }
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": <?php echo $map_latitude; ?>,
        "longitude": <?php echo $map_longitude; ?>
      },
      "url": <?php echo '"'.$metaOgUrl.'"'; ?>,
      "telephone": <?php echo '"'.$phone_number.'"'; ?>,
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "Monday"
          ],
          "opens": <?php echo '"'.$mondayOpen.'"'; ?>,
          "closes": <?php echo '"'.$mondayClose.'"'; ?>
        },         
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "tuesday"
          ],
          "opens": <?php echo '"'.$tuesdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$tuesdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "wednesday"
          ],
          "opens": <?php echo '"'.$wednesdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$wednesdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "thusday"
          ],
          "opens": <?php echo '"'.$thusdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$thusdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "friday"
          ],
          "opens": <?php echo '"'.$fridayOpen.'"'; ?>,
          "closes": <?php echo '"'.$fridayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "saturday"
          ],
          "opens": <?php echo '"'.$saturdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$saturdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "sunday"
          ],
          "opens": <?php echo '"'.$sundayOpen.'"'; ?>,
          "closes": <?php echo '"'.$sundayClose.'"'; ?>
        }
      ]
    }
    </script>
    @else
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "localbusiness",
      "image": <?php echo '"'.$trainerPhoto1.'"'; ?>,
      "name": <?php echo '"'.$metaTitle.'"'; ?>,
      "address": {
        "@type": "PostalAddress",
        "streetAddress": <?php echo '"'.$address.'"'; ?>,
        "addressLocality": <?php echo '"'.$address_localty.'"'; ?>,
        "addressRegion": <?php echo '"'.$region.'"'; ?>,
        "postalCode": <?php echo '"'.$postal_code.'"'; ?>,
        "addressCountry": <?php echo '"'.$region.'"'; ?>,
        "email" : <?php echo '"'.$email.'"'; ?>
      },
      "review": {
        "@type": "Review",
         "reviewRating": {
          "@type": "Rating",
          "ratingValue": <?php echo '"'.$ratings.'"'; ?>,  
          "bestRating": "5"
        }
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": <?php echo $map_latitude; ?>,
        "longitude": <?php echo $map_longitude; ?>
      },
      "url": <?php echo '"'.$metaOgUrl.'"'; ?>,
      "telephone": <?php echo '"'.$phone_number.'"'; ?>,
      "openingHoursSpecification": [
        {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "Monday"
          ],
          "opens": <?php echo '"'.$mondayOpen.'"'; ?>,
          "closes": <?php echo '"'.$mondayClose.'"'; ?>
        },         
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "tuesday"
          ],
          "opens": <?php echo '"'.$tuesdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$tuesdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "wednesday"
          ],
          "opens": <?php echo '"'.$wednesdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$wednesdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "thusday"
          ],
          "opens": <?php echo '"'.$thusdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$thusdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "friday"
          ],
          "opens": <?php echo '"'.$fridayOpen.'"'; ?>,
          "closes": <?php echo '"'.$fridayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "saturday"
          ],
          "opens": <?php echo '"'.$saturdayOpen.'"'; ?>,
          "closes": <?php echo '"'.$saturdayClose.'"'; ?>
        },
         {
          "@type": "OpeningHoursSpecification",
          "dayOfWeek": [
            "sunday"
          ],
          "opens": <?php echo '"'.$sundayOpen.'"'; ?>,
          "closes": <?php echo '"'.$sundayClose.'"'; ?>
        }
      ]
    }
    </script>
    @endif

    @else

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

    @endif
  
    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="32x32" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!--<link rel="stylesheet" href="h`ttps://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('../front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/front/trainer/css/calendar.css') }}">
    <link rel="stylesheet" href="{{ asset('../front/css/slick.min.css') }}">
    <!--<link rel="stylesheet" href="{{asset('../front/css/jquery.dataTables.min.css')}}">-->
    <link rel="stylesheet" href="{{asset('../front/css/pnotify.custom.min.css')}}">
    <link rel="stylesheet" href="{{ asset('../front/css/custom.css') }}">
    <!--<link rel="stylesheet" href="{{ asset('../front/css/developer.css') }}">-->
    <link rel="stylesheet" href="{{ asset('../front/css/responsive.css') }}">
    <link href="{{ asset('../front/aos/aos.css') }}" rel="stylesheet">
    <script src="{{ asset('../front/aos/aos.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('../front/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('../front/icons/icomoon.css') }}">
    <link href="{{ asset('../front/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NZC4WTH');</script>
<!-- End Google Tag Manager -->
<?php 
$keyCount = 0;
$is_data_array = 0;
$data_array = [];
$JSON_Array = array();
if($routeName == "exploreservices"){
// print_r(is_array($TrainerServicesdata));exit;
if(isset($TrainerServicesdata)){
    $is_data_array = 1;
    $data_array = $TrainerServicesdata;
    $total_count = count($TrainerServicesdata);
    foreach($TrainerServicesdata as $key=>$value){
        // print_r($key);exit;
        $keyCount = $key + 1;
        if(isset($value->business_name)){
            $delimeter = ',';
            if($keyCount == $total_count){
                $delimeter = '';
            }
        if(isset($value->photo) && $value->photo != ''){
            $photoURL = 'front/profile/'.$value->photo;
        }else{
            $photoURL = 'images/logo.png';
        }
        $keyCount = $key+1;
        $JSON_Array[] = '{ 
            "@context": "http://schema.org", 
            "@type": "BreadcrumbList", 
            "itemListElement": [{ 
            "@type": "ListItem", 
            "position": "'.$keyCount.'", 
            "item": { 
            "@id": "URL", 
            "name": "'.$value->business_name.'"}}]}, 
              { 
                "@context": "http://schema.org", 
                "@type": "LocalBusiness", 
                "address": { 
                    "@type": "PostalAddress", 
                    "addressLocality": "'.$value->address_1.'", 
                    "addressRegion": "'.$value->state_code.'", 
                    "postalCode": "'.$value->zip_code.'" 
                }, 
                "name": "'.$value->business_name.'" 
                 
                ,"aggregateRating": { 
                    "@type": "AggregateRating", 
                    "ratingValue": "'.$value->ratting.'", 
                    "reviewCount": "'.$value->ratting_count.'" 
                } 
                 
                 
                ,"image": "https://www.trainingblockusa.com/public/'.$photoURL.'" 
            }'.$delimeter;
        }
    }
    // print_r($JSON_Array);exit;
}else{
    $is_data_array = 0;
    $data_array = [];
}
?>
  
  <script type="application/ld+json">
  {"@context":"https://schema.org",
    "@type":"Organization",
    "name":"Trainingblock",
    "url":"https://www.trainingblockusa.com",
    "sameAs":["https://www.facebook.com/trainingblockusa","https://twitter.com","https://www.linkedin.com/company/training-block-usa/","https://www.instagram.com/training.block/"]}
  </script>  
<script type="application/ld+json">
    <?php 
        echo '[';
     foreach($JSON_Array as $jsonARR){
        echo $jsonARR;
     }
        echo ']';
    ?>
</script>  
<?php } ?>
<!-- Mailchimp Script -->
<?php  if( url()->current() == 'https://www.trainingblockusa.com/provider-register' ||  url()->current() == 'https://www.trainingblockusa.com/register'){ ?>
    <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/4e0396f0955b0a84ce3e1c4d3/c8d5811558a745e7b599e5755.js");</script>
    <?php } ?>

<!-- end Mailchimp Script -->

