<?php

add_action('admin_menu', 'ccpa_stats_add_page');

function ccpa_stats_add_page() {
	add_options_page($GLOBALS['affiliate_name'] . " Affiliate Stats", $GLOBALS['affiliate_name'] . " Affiliate Stats", 'manage_options', 'ccpa_stats', 'ccpa_statspage');
}

function ccpa_statspage() {
	?>
	<div class="wrap">
		<h2><?php echo $GLOBALS['affiliate_name']; ?> Affiliate Stats</h2>
	
<?php

$postinfo = array(
"app" => 'ecom',
"ns" => 'ccpa_stats',
"sitename" => $siteurl,
"admin_email" => $admin_email,
"plugin_version" => $GLOBALS['plugin_version'],
"language" => WPLANG
);

$ch = curl_init($GLOBALS['affiliate_url']);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt ($ch, CURLOPT_POST, true);
curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
$response = curl_exec($ch);
curl_close($ch);

print $response;

?>

</div>
	<?php	
}


