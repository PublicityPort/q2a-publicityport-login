<?php

/*
	Plugin Name: PublicityPort Login
	Plugin URI: https://github.com/PublicityPort/q2a-publicityport-login
	Plugin Description: Allows users to log in via PublicityPort's credentials.
	Plugin Version: 1.0.0
	Plugin Date: 2014-09-09
	Plugin Author: Publicity Port
	Plugin Author URI: https://publicityport.com
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.6.3
	Plugin Minimum PHP Version: 5
	Plugin Update Check URI: https://raw.githubusercontent.com/PublicityPort/q2a-publicityport-login/master/qa-plugin.php
*/

/*
	Based on PublicityPort Login plugin
*/

if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
	header('Location: ../../');
	exit;
}


if (!QA_FINAL_EXTERNAL_USERS) { // login modules don't work with external user integration

	qa_register_plugin_phrases('qa-pplogin-lang-*.php', 'plugin_open');
	qa_register_plugin_overrides('qa-pplogin-overrides.php');
	qa_register_plugin_layer('qa-pplogin-layer.php', 'OAuth/OpenID Layer');
	qa_register_plugin_module('page', 'qa-pplogin-page-logins.php', 'qa_publicityport_logins_page', 'PublicityPort Login Configuration');
	qa_register_plugin_module('widget', 'qa-pplogin-widget.php', 'qa_publicityport_logins_widget', 'PublicityPort Login Providers');
	
	// sice we're not allowed to access the database at this step, take the information from a local file
	// note: the file providers.php will be automatically generated when the configuration of the plugin
	// is updated on the Administration page
	$providers = @include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'providers.php';
	if ($providers) {
		// loop through all active providers and register them
		$providerList = explode(',', $providers);
		foreach($providerList as $provider) {
			qa_register_plugin_module('login', 'qa-pplogin-login.php', 'qa_publicityport_login', $provider);
		}
	}
	
}

/*
	Omit PHP closing tag to help avoid accidental output
*/
