<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

header('Content-Type: text/xml'); 
header("Content-Security-Policy: style-src 'self' 'unsafe-inline';");
//  START CAUTION!
//  IF YOU MODIFY THE "xml version="1.0" encoding="UTF-8" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED.
echo '<?xml version="1.0" encoding="UTF-8"?>'; //Has to be in an "echo" or the server will try to execute "?xml" on the server side. It just needs to displying in html. 
//  END CAUTION!?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php 
    if(!empty($data_array))
    {
		foreach($data_array['xml_data'] as $sitemap_data)
		{
    ?>
    <url>
        <loc><?php echo $sitemap_data['url'];?></loc>
        <lastmod><?php echo $sitemap_data['updated_date'];?></lastmod>
    </url>
	<?php
        }
    }
    ?>
</urlset>