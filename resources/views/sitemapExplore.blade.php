<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

@foreach ($categories as $category)
    <?php

    $originalDate = $category->created_at;
    // $newDate = date("Y-m-dTH:i:sP", strtotime($originalDate));
    $newDate = date_format(new DateTime($originalDate), 'c');
    // ?virtual_in_both=Both&location=&services=15&btnSearch=Search
    foreach($location as $loc){
        $locationValue = ucwords(strtolower($loc->city)).', '.$loc->state_code;
        if($loc->city != ''){
    ?>
  <url>
    <loc>https://www.trainingblockusa.com/explore?virtual_in_both=Both&amp;location={{ urlencode($locationValue) }}&amp;services={{ $category->id }}&amp;btnSearch=Search</loc>
    <lastmod>{{ $newDate }}</lastmod>
    <priority>0.8</priority>
  </url>
  <?php }}?>
@endforeach

</urlset>