<?php
//  Google SiteMap XML

if ( !defined('XCART_SESSION_START') ) { header("Location: ../../"); die("Access denied"); }

if ($config["xcartmod_sitemap"] != "Y") {
	db_query("INSERT INTO xcart_config (name, comment, value, category, orderby, type) VALUES ('sitemap', ' Google SiteMap XML', 'Y', '', '0', 'checkbox')");
	db_query("INSERT INTO xcart_config (name, comment, value, category, orderby, type) VALUES ('sitemap_time', ' Google SiteMap XML', '0', '', '0', 'text')");
}

$xm_today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
if ($xm_today == $config["xcartmod_sitemap_time"])
	return;

db_query("UPDATE $sql_tbl[config] SET value='$xm_today' WHERE name='xcartmod_sitemap_time'");

$filename = '../sitemap.xml';

if(!$fp = fopen($filename, 'w')) {
	print "Cannot open file ($filename)";
	exit;
}

$today = date("Y-m-d");
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
<loc>'.$http_location.'/</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>1.0</priority>
</url>';

$cats = func_query("SELECT * FROM $sql_tbl[categories] WHERE avail='Y'");

if (is_array($cats[0]))
	foreach($cats as $ca) {
		$xml .= '<url>
<loc>'.$http_location.'/home.php?cat='.$ca["categoryid"].'</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.8</priority>
</url>';
	}

$prods = func_query("SELECT * FROM $sql_tbl[products] WHERE forsale='Y'");
if (is_array($prods[0]))
	foreach($prods as $pr) {
		$xml .= '<url>
<loc>'.$http_location.'/product.php?productid='.$pr["productid"].'</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>';
	}

$pages = func_query("SELECT * FROM $sql_tbl[pages] WHERE active='Y'");
if (is_array($pages[0]))
	foreach($pages as $pa) {
		$xml .= '<url>
<loc>'.$http_location.'/pages.php?pageid='.$pa["pageid"].'</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.6</priority>
</url>';
	}

$xml .= '<url>
<loc>'.$http_location.'/help.php?section=business</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.5</priority>
</url>
<url>
<loc>'.$http_location.'/help.php?section=conditions</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.5</priority>
</url>
<url>
<loc>'.$http_location.'/register.php</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.5</priority>
</url>
<url>
<loc>'.$http_location.'/help.php?section=Password_Recovery</loc>
<lastmod>'.$today.'</lastmod>
<changefreq>monthly</changefreq>
<priority>0.5</priority>
</url>
</urlset>';


if (!fwrite($fp, $xml)) {
	print "Cannot write to file ($filename)";
	exit;
}

fclose($fp);

//  Google SiteMap XML
?>