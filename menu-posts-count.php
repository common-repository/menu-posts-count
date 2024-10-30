<?php

/**
 *
 * @link              http://www.sanjayojha.com
 * @since             1.0.0
 * @package           Menu Posts Count
 *
 * @wordpress-plugin
 * Plugin Name:       Menu Posts Count
 * Plugin URI:        http://www.sanjayojha.com/wp/plugins/menu-post-count
 * Description:       Display total number of posts(or custom post type) count of a category, tag or custom taxonomy just after the menu item in navigation bar 
 * Version:           1.0.0
 * Author:            Sanjay Ojha
 * Author URI:        http://www.sanjayojha.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mpc-sa
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is create admin part,
 * admin-specific hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-menu-posts-count-public.php';
/**
 * The core plugin class that is create public part,
 * public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-menu-posts-count-admin.php';

/**
 * Begins execution of the plugin. *
 * @since    1.0.0
 */
function run_menu_posts_count() {

	new Menu_Posts_Count_Admin();
    new Menu_Posts_Count_Public();

}
run_menu_posts_count();
