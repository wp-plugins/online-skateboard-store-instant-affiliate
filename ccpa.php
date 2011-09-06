<?php
/*
Plugin Name: Online Skateboard Store Affiliate
Plugin URI: http://www.onlineskateboardstore.com
Description: Creates a zero configuration affiliate system with onlineskateboardstore.com including product displays. Automatically creates an affiliate account at onlineskateboardstore.com for the wordpress site owner, displays the most popular items from onlineskateboardstore.com within the wordpress blog with the wordpress affiliate id auto embedded in the affiliated product links.
Version: 1.0.0
Author: Online Skateboard Store
*/

$GLOBALS['affiliate_name'] = 'Online Skateboard Store';
$GLOBALS['affiliate_url'] = 'http://www.onlineskateboardstore.com';
$GLOBALS['affiliate_email'] = 'support@onlineskateboardstore.com';

/* Do not edit below this line */

$GLOBALS['plugin_version'] = '1.0.0';

register_activation_hook( __FILE__, 'ccpa_activate' );
register_deactivation_hook( __FILE__, 'ccpa_deactivate' );

add_filter('the_content', 'ccpa_content');

/*
Commented in case we want to enable it later
include("ccpa_options.php");
include("ccpa_stats.php");
*/

####
## Function: ccpa_activate
####

function ccpa_activate() {

/* See if we are reactivating a deactivated plugin and if so, just set the active flag to yes and return. */

	$ccpa_key = get_option("ccpa_key");

		if(!empty($ccpa_key)) {
		
			update_option("ccpa_active", '1');
			return;
			
		}//End of if

/* Run the error checking on the required data for activating the plugin. */

	global $wp_version;
	
	$siteurl = get_option('siteurl');
	$admin_email = get_option('admin_email');
	
		if (empty($siteurl) or empty($admin_email)) {
		
			mail($GLOBALS['affiliate_email'],$GLOBALS['affiliate_name'] . " Affiliate Plugin Client Side Activation Error","There was an error where the wordpress site did not have a siteurl or admin email option set");
			
			$error = "Your Wordpress site is missing some info required by this plugin. Please make sure that your Wordpress options are set for BOTH siteurl and admin email. Once you have set these values then this error will go away and you can activate this plugin. Thank you.";
			
			wp_die($error);
		
		}#End of siteurl and admin_email exists check.
	
		if (version_compare($wp_version, "2.8.0", "<")) {
		
			mail($GLOBALS['affiliate_email'],$GLOBALS['affiliate_name'] . " Affiliate Plugin Client Side Activation Error","There was a WP Version number error while activating the plugin on " . $siteurl . " -- " . $admin_email . " -- " . $wp_version);
			
			$error = "Your version of Wordpress is " . $wp_version . " and this plugin requires at least version 2.8.0 -- Please use your browser's back button and then upgrade your version of Wordpress";
			
			wp_die($error);
		
		}#End of version check if.
	
	/* Create a unique key for the site. */
	
	$stamp = date("Ymdhis");
	$ip = $_SERVER['SERVER_ADDR'];
	$key = $stamp . $ip;
	$ccpa_key = str_replace(".", "", "$key");
			
	/* Place the call to the CCP server to create the affiliate account */
	
	$postinfo = array(
	"app" => 'ecom',
	"ns" => 'xmod_wp.activate',
	"key" => $ccpa_key,
	"sitename" => $siteurl,
	"email" => $admin_email,
	"plugin_version" => $GLOBALS['plugin_version'],
	"language" => WPLANG
	);
	
	$ch = curl_init($GLOBALS['affiliate_url']);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_POST, true);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
	$response = curl_exec($ch);
	curl_close($ch);
	
		if ($response == 'activated') {
		
			/* Add the plugin data/settings to the WP database */
		
			add_option("ccpa_key", "$ccpa_key", '', 'yes');
			add_option("ccpa_active", '1', '', 'yes');
			add_option("ccpa_prodsperpost", '4', '', 'yes');
			
		}else {
		
			wp_die("There was an error activating your account, please contact us at " . $GLOBALS['affiliate_url'] . " with the following error " . $response);
		
		}#End of activate notice if.
		
}#End of activation function. 

####
## Function: ccpa_deactivate
####

function ccpa_deactivate() {

	update_option("ccpa_active", '0');

}#End of deactivation function.

####
## Function: ccpa_content
####

function ccpa_content($content) {
	
		if (!is_single() or !get_option("ccpa_active")) {
		
			return $content;
		
		}#End of if.
		
		if (stristr($content, 'skateboard')) {
	
			$siteurl = get_option('siteurl');
			$admin_email = get_option('admin_email');
			$prodsperpost = get_option('ccpa_prodsperpost');
			$ccpa_key = get_option('ccpa_key');
	
			$postinfo = array(
			"app" => 'ecom',
			"ns" => 'xmod_wp.content',
			"key" => $ccpa_key,
			"sitename" => $siteurl,
			"email" => $admin_email,
			"content" => serialize($content),
			"prodsperpost" => $prodsperpost,
			"plugin_version" => $GLOBALS['plugin_version'],
			"language" => WPLANG
			);
			
			$ch = curl_init($GLOBALS['affiliate_url']);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_POST, true);
			curl_setopt ($ch, CURLOPT_POSTFIELDS, $postinfo);
			$response = curl_exec($ch);
			curl_close($ch);
			
				if (!empty($response) and $response != $content) {
			
					$content = $response;
			
				}//End of if
			
			return $content;
			
		}else {
		
			return $content;
		
		}//End of if

}#End of content filter function.

?>
