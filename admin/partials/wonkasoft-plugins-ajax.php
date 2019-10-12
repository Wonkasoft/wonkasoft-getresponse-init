<?php
/**
 * This file contains all the wonkasoft tools ajax requests.
 *
 * @link       https://wonkasoft.com
 * @since      1.0.0
 *
 * @package    Wonkasoft_Bbb_Integration
 * @subpackage Wonkasoft_Bbb_Integration/admin/partials
 */

if ( isset( $_POST['wonkasoft_tools_options'] ) ) {
	$nonce = ( isset( $_REQUEST['security'] ) ) ? wp_kses_post( wp_unslash( $_REQUEST['security'] ) ) : null;
	// Check if nonce is valid.
	if ( ! wp_verify_nonce( $nonce, 'wonkasoft_tools_options_ajax_post' ) ) {
		die( esc_html__( 'nonce failed', 'aperabags' ) );
	}

	$data = ( isset( $_POST['data'] ) ) ? wp_kses_post( wp_unslash( $_POST['data'] ) ) : null;
	if ( empty( $data ) ) :
		return false;
	endif;

	// Pattern for option name sanitize.
	$pattern = '/([ -])/';

	// Checking for passed in data.
	$data = json_decode( $data );
	unset( $data->action );
	$current_options = ( ! empty( get_option( 'custom_options_added' ) ) ) ? get_option( 'custom_options_added' ) : array();
	if ( $data->remove ) :
		foreach ( $current_options as $key => $current_option ) :
			if ( $data->option_id === $current_option['id'] ) :
				unset( $current_options[ $key ] );
			endif;
		endforeach;
		delete_option( $data->option_id );
		unregister_setting( 'wonkasoft-tools-options-group', $data->option_id );
		$data->current_options = $current_options;
		update_option( 'custom_options_added', $current_options );
		$data->msg = $data->option_id . ' option was deleted, unregistered as a setting, and the database has been updated.';
		wp_send_json_success( $data );
		else :
			$data->option_label = $data->option_name;
			$data->option_name  = preg_replace( $pattern, '_', strtolower( $data->option_name ) );

			if ( ! in_array( $data->option_name, $current_options ) ) :
				array_push(
					$current_options,
					array(
						'id'          => $data->option_name,
						'label'       => $data->option_label,
						'description' => $data->option_description,
						'api'         => $data->option_api,
					)
				);
				$set_args = array(
					'type'              => 'string',
					'description'       => $data->option_description,
					'sanitize_callback' => array( 'Wonkasoft_Bbb_Integration_Admin', 'wonkasoft_tools_options_sanitize' ),
					'show_in_rest'      => false,
				);
				register_setting( 'wonkasoft-tools-options-group', $data->option_name, $set_args );
				update_option( 'custom_options_added', $current_options );
				$data->current_options = $current_options;

				ob_start();
				wonkasoft_theme_option_parse(
					array(
						'id'            => $data->option_name,
						'label'         => $data->option_label,
						'value'         => '',
						'desc_tip'      => true,
						'description'   => $data->option_description,
						'wrapper_class' => 'form-row form-row-full form-group',
						'class'         => 'form-control',
						'api'           => $data->option_api,
					)
				);

				$data->new_elements = ob_get_clean();

				$data->msg = 'Current options have been updated';
				wp_send_json_success( $data );
			else :
				$data->current_options = $current_options;
				$data->msg             = $data->option_name . ' is already a current option.';
				wp_send_json_success( $data );
			endif;
	endif;
}
