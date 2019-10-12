<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wonkasoft.com
 * @since      1.0.0
 *
 * @package    Wonkasoft_Getresponse_Init
 * @subpackage Wonkasoft_Getresponse_Init/admin/partials
 */

defined( 'ABSPATH' ) || exit;

if ( is_admin() ) {
	?>
	<div class="setting-page-wrap">
		<div class="settings-page-content">

			<h3><?php echo esc_html( get_admin_page_title() ); ?></h3>

			<div class="message">
				<p>
					Thank you for using Wonkasoft's <?php echo esc_html( get_admin_page_title() ); ?>, we will show you how to get started.
				</p>
			</div>
			<div class="table-responsive w-75">
				<table class="table table-striped table-collapsed">
					<thead>
						<tr>
							<th colspan="2" valign="bottom">
								We have made a tools options area that will allow you to add any custom options you need to store on your WordPress site.
							</th>
						</tr>
						<tr>
							<th>
								Instruction
							</th>
							<th>
								Image
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th>
								<span>Go here. <i class="fa fa-right-arrow"></i></span>
							</th>
							<td>
								<img src="<?php echo esc_url( WONKASOFT_PLUGIN_IMG_URL . '/tools-options.jpg' ); ?>" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
}

