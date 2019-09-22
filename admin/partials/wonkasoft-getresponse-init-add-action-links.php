<?php
/**
 * Provide action links for the plugin
 *
 * This file is used to markup the plugin-facing aspects of the plugin.
 *
 * @link       https://wonkasoft.com
 * @since      1.0.0
 *
 * @package    Wonkasoft_Getresponse_Init
 * @subpackage Wonkasoft_Getresponse_Init/admin/partials
 */

/**
 * This sets the action links on the plugins screen on the plugin side.
 *
 * @param  array $links contains the default links.
 * @return array        returns a modified array of the links.
 */
function wonkasoft_getresponse_init_add_settings_link_filter( $links ) {
	global $wonkasoft_getresponse_init_page;
	$links_addon = '<a href="' . menu_page_url( $wonkasoft_getresponse_init_page, 0 ) . '" target="_self">Settings</a>';
	array_unshift( $links, $links_addon );
	$links[] = '<a href="https://paypal.me/Wonkasoft" target="blank"><img src="' . plugins_url( '../img/wonka-logo.svg', __FILE__ ) . '" style="width: 20px; height: 20px; display: inline-block;
    vertical-align: text-top; float: none;" /></a>';
	return $links;
}


/**
 * This sets the action links on the plugins screen on the description side.
 *
 * @param  array  $links contains the default links.
 * @param  string $file  contains the main file name for this plugin.
 * @return array        returns a modified array of the links.
 */
function wonkasoft_getresponse_init_add_description_link_filter( $links, $file ) {
	global $wonkasoft_getresponse_init_page;
	if ( strpos( $file, 'wonkasoft-getresponse-init.php' ) !== false ) {
		$links[] = '<a href="' . menu_page_url( $wonkasoft_getresponse_init_page, 0 ) . '" target="_self">Settings</a>';
		$links[] = '<a href="https://paypal.me/Wonkasoft" target="blank">Donate <img src="' . plugins_url( '../img/wonka-logo.svg', __FILE__ ) . '" style="width: 20px; height: 20px; display: inline-block;
    vertical-align: text-top;" /></a>';
	}
	return $links;
}
