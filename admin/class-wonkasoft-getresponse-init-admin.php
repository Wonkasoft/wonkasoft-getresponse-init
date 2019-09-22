<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wonkasoft.com
 * @since      1.0.0
 *
 * @package    Wonkasoft_Getresponse_Init
 * @subpackage Wonkasoft_Getresponse_Init/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wonkasoft_Getresponse_Init
 * @subpackage Wonkasoft_Getresponse_Init/admin
 * @author     Wonkasoft, LLC <support@wonkasoft.com>
 */
class Wonkasoft_Getresponse_Init_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wonkasoft_Getresponse_Init_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wonkasoft_Getresponse_Init_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wonkasoft-getresponse-init-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wonkasoft_Getresponse_Init_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wonkasoft_Getresponse_Init_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wonkasoft-getresponse-init-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * This sets up the admin menu for Wonkasoft Refersion Integration.
	 */
	public function wonkasoft_getresponse_init_admin_menu() {
		/**
		* This will check for Wonkasoft Tools Menu, if not found it will make it.
		*/
		global $wonkasoft_getresponse_init_page;
		if ( empty( $GLOBALS['admin_page_hooks']['wonkasoft_menu'] ) ) {
			$wonkasoft_getresponse_init_page = 'wonkasoft_menu';
			add_menu_page(
				'Wonkasoft',
				'Wonkasoft Tools',
				'manage_options',
				'wonkasoft_menu',
				array( $this, 'wonkasoft_getresponse_init_settings_display' ),
				WONKASOFT_GETRESPONSE_INIT_IMG_PATH . '/wonka-logo-2.svg',
				100
			);

			add_submenu_page(
				'wonkasoft_menu',
				WONKASOFT_GETRESPONSE_INIT_NAME,
				WONKASOFT_GETRESPONSE_INIT_NAME,
				'manage_options',
				$wonkasoft_getresponse_init_page,
				array( $this, 'wonkasoft_getresponse_init_settings_display' )
			);

			$this->wonkasoft_tools_add_options();
			add_action( 'admin_enqueue_scripts', array( $this, 'wonkasoft_tools_options_js' ), 10, 1 );

		} else {

			/**
			* This creates option page in the settings tab of admin menu
			*/
			$wonkasoft_getresponse_init_page = 'wonkasoft_getresponse_init_settings_display';
			add_submenu_page(
				'wonkasoft_menu',
				WONKASOFT_GETRESPONSE_INIT_NAME,
				WONKASOFT_GETRESPONSE_INIT_NAME,
				'manage_options',
				$wonkasoft_getresponse_init_page,
				array( $this, 'wonkasoft_getresponse_init_settings_display' )
			);
		}

	}

	/**
	 * This function is the callback for the admin screen for this plugin.
	 */
	public function wonkasoft_getresponse_init_settings_display() {
		include_once plugin_dir_path( __FILE__ ) . 'partials/wonkasoft-getresponse-init-admin-display.php';
	}

	/**
	 * Addition of apera-bags theme options.
	 */
	public function wonkasoft_tools_add_options() {
		add_submenu_page(
			'wonkasoft_menu',
			'Tools Options',
			'Tools Options',
			'manage_options',
			'wonkasoft-tools-options',
			array( $this, 'wonkasoft_tools_options_page' )
		);

		$registered_options = ( ! empty( get_option( 'custom_options_added' ) ) ) ? get_option( 'custom_options_added' ) : '';

		if ( ! empty( $registered_options ) ) {

			foreach ( $registered_options as $register_option ) {
				$set_args = array(
					'type'              => 'string',
					'description'       => $register_option['description'],
					'sanitize_callback' => 'wonkasoft_tools_options_sanitize',
					'show_in_rest'      => false,
				);

				register_setting( 'wonkasoft-tools-options-group', $register_option['id'], $set_args );
			}
		}
	}

			/**
			 * Used to sanitize the options
			 *
			 * @param  string $option contains the value within the option.
			 * @return string         returns the sanitized option value.
			 */
	public function wonkasoft_tools_options_sanitize( $option ) {
		$option = esc_html( $option );
		return $option;
	}

			/**
			 * This builds the display of the options page.
			 */
	public function wonkasoft_tools_options_page() {   ?>
					<div class="container">
						<div class="row">
							<div class="col-12 title-column">
								<?php
								$title_text = get_admin_page_title();
								?>
								<h3 class="title-header"><?php echo wp_kses_post( $title_text ); ?></h3>
							</div>
						</div>
						<div class="row">
							<div class="col-12 options column">
								<div class="card w-100">
									<div class="card-title">
										<h3><?php esc_html_e( 'Add an option', 'Wonkasoft_Refersion_Init' ); ?></h3>
										<button type="button" id="theme_option_add" class="wonka-btn" data-toggle="modal" data-target="#add_option_modal">OPTION <i class="fa fa-plus"></i></button>
									</div>
									<div class="card-body">
								<form id="custom-options-form" method="post" action="options.php">

							  <?php settings_fields( 'wonkasoft-tools-options-group' ); ?>

							  <?php do_settings_sections( 'wonkasoft-tools-options-group' ); ?>

								<?php
									$registered_options = ( ! empty( get_option( 'custom_options_added' ) ) ) ? get_option( 'custom_options_added' ) : '';

								if ( ! empty( $registered_options ) ) :
									foreach ( $registered_options as $register_option ) {
										$current_option = ( ! empty( get_option( $register_option['id'] ) ) ) ? get_option( $register_option['id'] ) : '';

										wonkasoft_tool_option_parse(
											array(
												'id'       => $register_option['id'],
												'label'    => $register_option['label'],
												'value'    => $current_option,
												'desc_tip' => true,
												'description' => $register_option['description'],
												'wrapper_class' => 'form-row form-row-full form-group',
												'class'    => 'form-control',
												'api'      => $register_option['api'],
											)
										);
									}
									endif;
								?>
							<div class="submitter">

									  <?php submit_button( 'Save Settings' ); ?>

							</div>
							</form>
									  </div>
								</div><!-- card w-100 -->
							</div>
							<!-- Modal -->
							<div id="add_option_modal" class="modal fade" role="dialog">
							  <div class="modal-dialog">

								<!-- Modal content-->
								<div class="modal-content">
								  <div class="modal-header">
									<h4 class="modal-title">Add Option</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								  </div>
								  <div class="modal-body">
										<div class="input-group mb-3">
											<input class="form-control" type="text" id="new_option_name" name="new_option_name" placeholder="enter option name..." value="" />
										</div>
										<div class="input-group mb-3">
											<input class="form-control" type="text" id="new_option_description" name="new_option_description" placeholder="enter option description..." value="" />
										</div>
										<div class="input-group mb-3">
											<input class="form-control" type="text" id="new_option_api" name="new_option_api" placeholder="whos api..." value="" />
										</div>
									<?php
									wp_nonce_field(
										'theme_options_ajax_post',
										'new_option_nonce',
										true,
										true
									);
									?>
								  </div>
								  <div class="modal-footer">
										<button type="button" class="btn wonka-btn btn-success" data-dismiss="modal" id="add_option_name">Add option <i class="fa fa-plus"></i></button>
								  </div>
								</div>

							  </div>
							</div>
						</div>
					</div>
				<?php
	}

		/**
		 * For the parsing of option fields.
		 *
		 * @param  array $field array of the fields.
		 */
	public function wonkasoft_tool_option_parse( $field ) {

		$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
		$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
		$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
		$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
		$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
		$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
		$styles_set             = ( ! empty( $field['style'] ) ) ? ' style="' . esc_attr( $field['style'] ) . '" ' : '';

		// Custom attribute handling.
		$custom_attributes = array();
		$output            = '';

		if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
			foreach ( $field['custom_attributes'] as $attribute => $value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
			}
		}

		$output .= '<div class="' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">
				<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

		if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
			$output .= '<span class="woocommerce-help-tip" data-toggle="tooltip" data-placement="top" title="' . esc_attr( $field['description'] ) . '"></span>';
		}

		if ( 'ga' === $field['api'] ) :
			$place_holder = ' placeholder="UA-XXXXXX-X"';
		else :
			$place_holder = ' placeholder="Paste api key..."';
		endif;
		$output .= '<div class="input-group">';
		$output .= '<input type="password" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '" class="' . esc_attr( $field['class'] ) . '" ' . $styles_set . implode( ' ', $custom_attributes ) . ' value="' . esc_attr( $field['value'] ) . '"' . $place_holder . ' /> ';
		$output .= '<div class="input-group-append">';
		$output .= '<button class="btn wonka-btn btn-danger" type="button" id="remove-' . esc_attr( $field['id'] ) . '"><i class="fa fa-minus"></i></button>';
		$output .= '</div>';
		$output .= '</div>';
		if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
			$output .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
		}

		$output .= '</div>';

		echo wp_kses(
			$output,
			array(
				'label'  => array(
					'for' => array(),
				),
				'input'  => array(
					'class'       => array(),
					'name'        => array(),
					'id'          => array(),
					'type'        => array(),
					'value'       => array(),
					'placeholder' => array(),
				),
				'span'   => array(
					'class' => array(),
				),
				'div'    => array(
					'class' => array(),
				),
				'button' => array(
					'class' => array(),
					'type'  => array(),
					'id'    => array(),
				),
				'i'      => array(
					'class' => array(),
				),
			)
		);
	}

	/**
	 * This is for enqueuing the script for the theme options page only.
	 *
	 * @param  string $page contains the page name.
	 */
	public function wonkasoft_tools_options_js( $page ) {

		if ( 'wonkasoft-tools_page_wonkasoft-tools-options' === $page ) :
			wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), '4.3.1', 'all' );

			wp_style_add_data( 'bootstrap', array( 'integrity', 'crossorigin' ), array( 'sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T', 'anonymous' ) );

			wp_enqueue_script( 'bootstrapjs', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array( 'jquery' ), '4.3.1', true );

			wp_script_add_data( 'bootstrapjs', array( 'integrity', 'crossorigin' ), array( 'sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM', 'anonymous' ) );

			wp_enqueue_script( 'wonkasoft-tools-options-js', str_replace( array( 'http:', 'https:' ), '', get_stylesheet_directory_uri() . '/includes/js/wonkasoft-tools-options-js.js' ), array( 'jquery' ), '20190819', true );
		endif;
	}

}
