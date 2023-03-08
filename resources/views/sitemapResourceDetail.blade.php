<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

@foreach ($resources as $resource)   
<?php

    $originalDate = $resource->created_at;
    // $newDate = date("Y-m-dTH:i:sP", strtotime($originalDate));
    $newDate = date_format(new DateTime($originalDate), 'c');
    ?>
  <url>
    <loc>https://www.trainingblockusa.com/resource-details/{{ base64_encode($resource->id) }}</loc>
    <lastmod>{{ $newDate }}</lastmod>
    <priority>0.8</priority>
  </url>
@endforeach

</urlset>
