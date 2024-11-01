<?php
/*
Plugin Name: Website Advisor
Plugin URI: http://MyWebsiteAdvisor.com
Description: Website Advisor Plugin features Sitemap Validator and Website Checkup.
Version: 1.1
Author: MyWebsiteAdvisor
Author URI: http://MyWebsiteAdvisor.com
*/

register_activation_hook(__FILE__, 'website_advisor_activate');

// display error message to users
if ($_GET['action'] == 'error_scrape') {                                                                                                   
    die("Sorry,  Plugin requires PHP 5.0 or higher. Please deactivate Plugin.");                                 
}

function website_advisor_activate() {
	if ( version_compare( phpversion(), '5.0', '<' ) ) {
		trigger_error('', E_USER_ERROR);
	}
}

// require  Plugin if PHP 5 installed
if ( version_compare( phpversion(), '5.0', '>=') ) {
	define('WA_LOADER', __FILE__);

	require_once(dirname(__FILE__) . '/website-advisor.php');
	require_once(dirname(__FILE__) . '/plugin-admin.php');

}
?>