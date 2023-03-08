    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   
  @if(!empty($metaTitle))
    <title>Training Block - <?php echo $metaTitle; ?></title>
    @else
    <title>Training Block - where athletes find what they need for training</title>    
    <!--<title>Training Block :: @yield('title')</title>    -->
    @endif
    @if(!empty($metaDescription))
    <meta name="description" content = "<?php echo $metaDescription; ?>"/>    
    @else
    <meta name="description" content="All the training articles and videos you need, right at your fingertips, created by experts in sport." />   
    @endif
    @if(!empty($metaKeywords))       
    <meta name="keywords" content = "<?php echo $metaKeywords; ?>" />
    @else     
    <meta name="keywords" content=" "/>
    @endif
    @if(!empty($metaRobots))
    <meta name="robots" content = {{ $metaRobots }} />    
    @else
    <meta name="robots" content="index,follow" />   
    @endif
    <link rel="canonical" href="<?php echo URL::current();  ?>" />
    @if(!empty($metaPublisher))
    <meta name="publisher" content="<?php echo $metaPublisher; ?>" />    
    @else
    <meta name="publisher" content="publisher"/>   
    @endif
    @if(!empty($metaPageType))
    <meta name="page-type" content = {{ $metaPageType }}>     
    @else
    <meta name="page-type" content="Blogging" />    
    @endif
    @if(!empty($metaArticleModifiedTime))
    <meta property="article:modified_time" content ="<?php echo $metaArticleModifiedTime; ?>" />     
    @else
    <meta property="article:modified_time" content="2022-01-28T08:38:53+00:00" />    
    @endif
    @if(!empty($metaOgTitle))
    <meta property="og:title" content = "<?php echo $metaOgTitle; ?>" />
    @else
    <meta property="og:title" content="Home Page" />
    @endif
    @if(!empty($metaOgDescription))
    <meta property="og:description" content = "<?php echo $metaOgDescription; ?>" />
    @else
    <meta property="og:description" content="{description}" />
    @endif
    @if(!empty($metaOgUrl))
    <meta property="og:url" content = "<?php echo $metaOgUrl; ?>" />
    @else
    <meta property="og:url" content="https://www.trainingblockusa.com" />
    @endif
    @if(!empty($metaOgUrlSiteName))
    <meta property="og:site_name" content = {{ $metaOgUrlSiteName }} />
    @else
    <meta property="og:site_name" content="Trainingblock" />
    @endif
    @if(!empty($metaOgImage))
    <meta property="og:image" content = "{{ asset('front/images/resource/'.$metaOgImage) }}" >
    @else
    <meta property="og:image" content="http://example.com/thumbnail.jpg">
    @endif
    @if(!empty($metaOgVideo))
    <meta property="og:video" content = "<?php echo $metaOgVideo; ?>" />
    @else
    <meta property="og:video" content = " " />    
    @endif
    @if(!empty($metaTwitterCard))
    <meta name="twitter:card" content= "TrainingBlock - <?php echo $metaTitle; ?>" />
    @else
    <meta name="twitter:card" content="summary" />
    @endif
    @if(!empty($metaTwitterTitle))
    <meta name="twitter:title" content ="<?php echo $metaTwitterTitle; ?>" />
    @else
    <meta name="twitter:title" content="Title of this page, same as title tag" />
    @endif
    @if(!empty($metaTwitterDescription))
    <meta name="twitter:description" content = "<?php echo $metaTwitterDescription; ?>" />
    @else
    <meta name="twitter:description" content="Description of this page, same as meta description" />
    @endif
    @if(!empty($metaTwitterImage))
    <meta name="twitter:image" content = "{{ asset('front/images/resource/'.$metaTwitterImage) }}" />
    @else
    <meta name="twitter:image" content="http://fullurl.com/to-this/image.jpg" />
    @endif
  
  @if(!empty($metaOgVideo))

    <script type="application/ld+json" >
  
        {"@type": "VideoObject",
          "position": 1,
          "name": <?php echo '"'.$metaTitle.'"'; ?>,
          "url": <?php echo '"'.URL::current().'"'; ?>,
          "description": <?php echo '"'.$metaDescription.'"'; ?>,
          "thumbnailUrl": <?php echo '"'.$image_name.'"'; ?>,
          "uploadDate": <?php echo '"'.$created_at.'"'; ?>,        
          "contentUrl": <?php echo '"'.$format_name.'"'; ?>          
          }
  </script>

  

<script type="application/ld+json">
  
 {"@context":"https:\/\/schema.org",
"@graph":[{"@type":"BlogPosting",
"headline": <?php echo '"'.$metaTitle.'"'; ?>,
"datePublished": <?php echo '"'.$created_at.'"'; ?>,
"publisher" : <?php echo '"'.$name.'"'; ?>,
"logo":{"@type":"ImageObject",
"url": "https://www.trainingblockusa.com/public/images/logo.png"},
"mainEntityOfPage":{"@type":"WebPage",
"@id":"https://www.trainingblockusa.com/provider/<?php echo str_replace(' ', '-', strtolower($name)); ?>"},
"author":{"@type":"Person","name": <?php echo '"'.$name.'"'; ?>},
"description":<?php echo '"'.$metaDescription.'"'; ?>,
"image":{"@type":"ImageObject",
"url":<?php echo '"'.$image_name.'"'; ?>,
"Width":1170,"height":756
}
}
]
}

  </script>

    @else


<script type="application/ld+json">
  
 {"@context":"https:\/\/schema.org",
"@graph":[{"@type":"BlogPosting",
"headline": <?php echo '"'.$metaTitle.'"'; ?>,
"datePublished": <?php echo '"'.$created_at.'"'; ?>,
"publisher" : <?php echo '"'.$name.'"'; ?>,
"logo":{"@type":"ImageObject",
"url": "https://www.trainingblockusa.com/public/images/logo.png"},
"mainEntityOfPage":{"@type":"WebPage",
"@id":"https://www.trainingblockusa.com/provider/<?php echo str_replace(' ', '-', strtolower($name)); ?>"},
"author":{"@type":"Person","name": <?php echo '"'.$name.'"'; ?>},
"description":<?php echo '"'.$metaDescription.'"'; ?>,
"image":{"@type":"ImageObject",
"url":<?php echo '"'.$image_name.'"'; ?>,
"Width":1170,"height":756
}
}
]
}

  </script>

    @endif
    <link rel="icon" href="{{ asset('images/favicon.png') }}" sizes="32x32" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('../front/css/bootstrap.min.css') }}">
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

    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NZC4WTH');</script>
<!-- End Google Tag Manager -->

