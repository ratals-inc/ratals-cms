<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/site-not-found.php'))
{
	include($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/site-not-found.php');
}
else
{
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Site Not Found</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
</head>

<body>
<h3>Site not found. This domain is pointed to your Ratals installation, but it hasn't been added yet. Please add the domain in your admin area to get started.</h3>
</body>
</html>
<?php
}
?>