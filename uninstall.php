<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://www.sanjayojha.com
 * @since      1.0.0
 *
 * @package    Menu_Posts_Count
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
//define a vairbale and store an option name as the value.
$option_name = 'mpc_sa_options';
//call delete option and use the vairable inside the quotations
delete_option($option_name);
// for site options in Multisite
delete_site_option($option_name);
