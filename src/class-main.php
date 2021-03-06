<?php
/**
 * Class Main
 *
 * @package CustomCookieMessage
 */

namespace CustomCookieMessage;

use CustomCookieMessage\Controller\Controller;
use CustomCookieMessage\Forms\AdminForm;

/**
 * Custom Cookie Message.
 *
 * @package CustomCookieMessage
 */
class Main {

	/**
	 * Current version.
	 *
	 * @var string
	 */
	protected $version = '2.1.0';

	/**
	 * Store singlenton CustomCookieMessage\Main.
	 *
	 * @var Main
	 */
	protected static $single;

	/**
	 * Main constructor.
	 */
	public function __construct() {

		spl_autoload_register( [ $this, 'autoload' ] );

		add_action( 'init', [ $this, 'init' ], 30 );

		register_activation_hook( CUSTOM_COOKIE_MESSAGE_FILE, [ __CLASS__, 'plugin_activation' ] );

	}

	/**
	 * Autoloader
	 *
	 * @since 2.0.0
	 *
	 * @param string $class_name Class name loader.
	 */
	public function autoload( $class_name ) {
		if ( ! preg_match( '/^CustomCookieMessage/', $class_name ) ) {
			return;
		}

		$class_normalize = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', $class_name ) );
		$class_normalize = preg_replace( '#/([a-zA-Z]*)$#', '/class-$1', $class_normalize );
		$class_normalize = explode( '/', $class_normalize );

		array_shift( $class_normalize );
		$class_file_name = array_pop( $class_normalize );

		$class_path = implode( '/', $class_normalize );

		$file_path = empty( $class_path ) ? strtolower( $class_file_name ) . '.php' : $class_path . '/' . strtolower( $class_file_name ) . '.php';

		if ( file_exists( CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path ) ) {
			include CUSTOM_COOKIE_MESSAGE_DIR . '/src/' . $file_path;
		} else {
			wp_die( 'File does not exists ' . esc_url( CUSTOM_COOKIE_MESSAGE_DIR ) . '/src/' . esc_url( $file_path ) );
		}
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @return Main
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Get the static version.
	 *
	 * @return string Version self::$version.
	 */
	public static function version() {
		return self::single()->get_version();
	}

	/**
	 * Get version.
	 *
	 * @return string Version CUSTOM_COOKIE_MESSAGE_VERSION.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Default trigger.
	 */
	public function init() {

		Controller::single();

		load_plugin_textdomain( 'custom-cookie-message' );

		add_shortcode( 'ccm_preferences', [ '\CustomCookieMessage\Shortcode', 'ccm_shortcode_preferences' ] );

		if ( is_admin() ) {
			AdminForm::single();

			add_action( 'upgrader_process_complete', [ __CLASS__, 'update' ] );

			add_action( 'admin_notices', [ __CLASS__, 'admin_notices' ] );
		}

		add_action( 'wp_footer', [ $this, 'display_frontend_notice' ] );

		if ( ! empty( $_COOKIE['custom_cookie_message'] ) ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'ccm_handle_scripts' ], 99 );
		}

	}

	/**
	 * Enable plugin cookie.
	 */
	public static function plugin_activation() {

		if ( empty( get_option( 'custom_cookie_message', [] ) ) ) {
			add_option( 'custom_cookie_message', self::ccm_set_default_options() );
			update_site_option( 'custom_cookie_message_version', self::version() );
		}

	}

	/**
	 * Were updates in options and code are written.
	 *
	 * @since 2.0.0
	 */
	public static function update() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );

		$update_queue = [];
		while ( $current_version > $current_installed_version ) {
			++ $current_installed_version;
			if ( method_exists( '\CustomCookieMessage\Update', 'custom_cookie_message_' . $current_installed_version ) ) {
				$update_queue[] = [ '\CustomCookieMessage\Update', 'custom_cookie_message_' . $current_installed_version ];
			}
		}

		// Is empty, nani?
		if ( empty( $update_queue ) ) {
			return;
		}

		foreach ( $update_queue as $update_function ) {
			$update_function();
		}

		update_site_option( 'custom_cookie_message_version', self::version() );

	}

	/**
	 * Handle all admin notices.
	 */
	public static function admin_notices() {

		$current_installed_version = str_replace( '.', '', get_site_option( 'custom_cookie_message_version', '1.6.4' ) );
		$current_version           = str_replace( '.', '', self::version() );
		$output                    = '';

		if ( $current_installed_version < $current_version ) {
			$output .= '<div class="notice notice-info">';
			$output .= '<h2 class="notice-title">';
			$output .= esc_html( 'Custom Cookie Message' );
			$output .= '</h2>';
			$output .= '<p>';
			$output .= esc_html__( 'An update is available.', 'custom-cookie-message' );
			$output .= ' <a href="#ccm-upgrade" class="custom-cookie-message-upgrade">' . esc_html__( 'Upgrade now', 'custom-cookie-message' ) . '</a>';
			$output .= '</p>';
			$output .= '</div>';
		}

		echo $output; // WPCS: XSS ok.

	}

	/**
	 * Clean pattern list.
	 *
	 * @param array $patter_array List cookie pattern to block.
	 *
	 * @return string
	 */
	protected function ccm_patter_list( $patter_array ) {

		if ( ! is_array( $patter_array ) ) {
			return '';
		}

		$patter_array = array_filter(
			$patter_array, function ( $value ) {
				return '' !== trim( $value );
			}
		);

		$patter_array = array_map(
			function ( $pattern ) {
					return '(' . trim( $pattern ) . ')';
			}, $patter_array
		);

		return implode( '|', $patter_array );

	}

	/**
	 * Dequeue cookies when user change settings.
	 */
	public function ccm_handle_scripts() {
		global $wp_scripts;
		$options = get_option( 'custom_cookie_message' );

		$cookie_ccm = json_decode( stripslashes( $_COOKIE['custom_cookie_message'] ) );

		$functional_list  = explode( ',', $options['cookie_granularity_settings']['functional_list'] . ',' );
		$advertising_list = explode( ',', $options['cookie_granularity_settings']['advertising_list'] . ',' );

		$functional_list  = $this->ccm_patter_list( $functional_list );
		$advertising_list = $this->ccm_patter_list( $advertising_list );

		foreach ( $wp_scripts->queue as $handle ) {

			if ( 'false' === $cookie_ccm->functional && preg_match( "@{$functional_list}@", $handle ) ) {
				wp_dequeue_script( $handle );
			}
			if ( 'false' === $cookie_ccm->advertising && preg_match( "@{$advertising_list}@", $handle ) ) {
				wp_dequeue_script( $handle );
			}
		}
	}

	/**
	 * Template notice.
	 */
	public function display_frontend_notice() {

		wp_enqueue_style( 'custom-cookie-message-popup-styles', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/css/custom-cookie-message-popup.css', [], $this->version, 'screen' );
		wp_add_inline_style( 'custom-cookie-message-popup-styles', $this->custom_css() );
		wp_enqueue_script( 'custom-cookie-message-popup', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/custom-cookie-message-popup.js', [ 'jquery' ], $this->version, true );
		wp_enqueue_script( 'custom-cookie-message-svg', CUSTOM_COOKIE_MESSAGE_PLUGIN_URL . '/assets/js/svgxuse.js', [], $this->version, true );
		wp_localize_script(
			'custom-cookie-message-popup', 'customCookieMessageLocalize', [
				'options'             => get_option( 'custom_cookie_message' ),
				'wp_rest'             => wp_create_nonce( 'wp_rest' ),
				'rest_url_banner'     => rest_url( 'custom-cm/banner' ),
				'rest_url_preference' => rest_url( 'custom-cm/cookie-preference' ),
			]
		);

	}

	protected function parse_to_rgba( $colour, $opacity = 1) {
		list( $r, $g, $b ) = sscanf( $colour, '#%02x%02x%02x' );

		$opacity   = $opacity / 100;
		$rgba = sprintf('rgba(%s, %s, %s, %s)', $r, $g, $b, $opacity);

		return $rgba;
	}

	/**
	 * Create styles from the options
	 */
	protected function custom_css() {
		$options = get_option( 'custom_cookie_message' );
		$styles = $options['styles'];

		$banner_background = $this->parse_to_rgba( $styles['message_color_picker'], $styles['opacity_slider_amount'] );

		$modal_background = $this->parse_to_rgba( $styles['modal_bg'], $styles['modal_bg_opacity'] );

		$css = '';
		$css .= '.custom-cookie-message-banner {';
			$css .= sprintf('background-color: %s;', $banner_background );
			$css .= sprintf('padding: %spx 0;', $styles['message_height_slider_amount'] );
		$css .= '}';

		$css .= '.custom-cookie-message-banner__text,';
		$css .= '.custom-cookie-message-modal__box {';
			$css .= sprintf('color: %s;', $styles['text_color_picker'] );
			if (! empty( $styles['text_font'] ) ) {
				$css .= sprintf('font-family: %s;', $styles['text_font']);
			}
			$css .= sprintf('font-size: %s;', $styles['text_size'] );
		$css .= '}';

		$css .= '.custom-cookie-message-banner a {';
			$css .= sprintf('color: %s;', $styles['link_color_picker']);
		$css .= '}';

		$css .= '.custom-cookie-message-modal {';
			$css .= sprintf('background-color: %s;', $modal_background);
		$css .= '}';

		$css .= '.custom-cookie-message-banner__button,';
		$css .= '.custom-cookie-message-popup__button {';
			$css .= sprintf('background-color: %s;', $styles['button_color_picker']);
			$css .= sprintf('color: %s;', $styles['button_text_color_picker']);
			$css .= sprintf(
				'padding: %spx %spx;', $styles['button_height_slider_amount'],
				$styles['button_width_slider_amount']
			);
		$css .= '}';

		return $css;
	}

	/**
	 * Include template if we could locate it.
	 *
	 * @param string $template_name Template name.
	 */
	public static function get_template( $template_name = 'cookie-notice.php' ) {

		$located = static::locate_template( $template_name );

		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( ' < code>%s </code > does not exist . ', esc_html( $located ) ), esc_html( self::version() ) );

			return;
		}

		include $located;
	}

	/**
	 * Helper to locate templates.
	 *
	 * @param string $template_name Template name.
	 *
	 * @return string
	 */
	public static function locate_template( $template_name ) {
		$template_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . ' / ';

		$default_path = CUSTOM_COOKIE_MESSAGE_PLUGIN_PATH . '/templates';

		$template = locate_template(
			[
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			]
		);

		if ( ! $template ) {
			$template = $default_path . '/' . $template_name;
		}

		return apply_filters( 'ccm_locate_template', $template, $template_name, $template_path );
	}

	/**
	 * TODO: Move this away from here.
	 */
	public function cookie_setcookie() {
		// TODO: Create option with life span. Also move it to REST API.
		setcookie( 'cookie-warning-message', 15, 30 * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		wp_send_json( 1 );
	}

	/**
	 * Default Options.
	 *
	 * @return mixed|void
	 */
	public static function ccm_set_default_options() {

		$defaults = [
			'general'                     => [
				'life_time'         => MONTH_IN_SECONDS,
				'location_options'  => 'top-fixed',
				'cookies_page_link' => '',
			],
			'content'                     => [
				'input_button_text'     => 'Change Settings',
				'save_settings_button'  => 'Save Settings',
				'input_link_text'       => 'Read more',
				'textarea_warning_text' => 'This website uses cookies. By using our website you accept our use of cookies.',
				'shortcode_text'        => 'Cookie Preferences',
			],
			'styles'                      => [
				'message_color_picker'         => '#3E3E3B',
				'message_height_slider_amount' => '10',
				'opacity_slider_amount'        => '100',
				'button_color_picker'          => '#EBECED',
				'button_hover_color_picker'    => '#CBC5C1',
				'button_text_color_picker'     => '#3E3E3B',
				'button_height_slider_amount'  => '15',
				'button_width_slider_amount'   => '10',
				'text_color_picker'            => '#c0c0c0',
				'text_size'                    => '16px',
				'text_font'                    => '',
				'link_color_picker'            => '#CBC5C1',
				'modal_bg'                     => '#3d3d3d',
				'modal_bg_opacity'             => '50',

			],
			'cookie_granularity_settings' => [
				'headline'                    => 'Privacy Preferences',
				'required_cookies_message'    => 'Required Cookies Message',
				'functional_cookies_message'  => 'Functional Cookies Message',
				'advertising_cookies_message' => 'Advertising Cookies Message',
				'functional_list'             => '',
				'advertising_list'            => '',
			],
		];

		return apply_filters( 'custom_cookie_message_set_options', $defaults );
	}

}
