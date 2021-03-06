<?php
/**
 * AdminStylingOptions
 *
 * @package CustomCookieMessage\Forms
 */

namespace CustomCookieMessage\Forms;

/**
 * Class AdminStylingOptions
 *
 * @package CustomCookieMessage\Forms
 */
class AdminStylingOptions extends AdminBase {

	use AdminTrait;

	/**
	 * Singlenton.
	 *
	 * @var AdminStylingOptions
	 */
	static protected $single;

	/**
	 * Settings Sections.
	 *
	 * @var string
	 */
	protected $section_page = 'styling_options';

	/**
	 * CookieList constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'admin_init', [ $this, 'cookies_initialize_styling_options' ] );
	}

	/**
	 * Access to the single instance of the class.
	 *
	 * @since 2.0.0
	 *
	 * @return AdminStylingOptions
	 */
	public static function single() {
		if ( empty( self::$single ) ) {
			self::$single = new self();
		}

		return self::$single;
	}

	/**
	 * Define settings.
	 */
	public function cookies_initialize_styling_options() {

		add_settings_section( 'styling', esc_html__( 'Styling Options', 'custom-cookie-message' ), [ $this, 'cookies_styling_options_callback' ], $this->section_page );

		add_settings_field( 'message_color_picker', esc_html__( 'Message container background', 'custom-cookie-message' ), [ $this, 'cookies_message_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'message_height_slider_amount', esc_html__( 'Message container padding top and bottom', 'custom-cookie-message' ), [ $this, 'cookies_message_height_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'opacity_slider_amount', esc_html__( 'Message container opacity', 'custom-cookie-message' ), [ $this, 'cookies_opacity_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_font', esc_html__( 'Text font', 'custom-cookie-message' ), [ $this, 'cookies_text_font_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_size', esc_html__( 'Text size', 'custom-cookie-message' ), [ $this, 'cookies_text_size_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'text_color_picker', esc_html__( 'Text Color', 'custom-cookie-message' ), [ $this, 'cookies_text_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'link_color_picker', esc_html__( 'Link Color', 'custom-cookie-message' ), [ $this, 'cookies_link_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_color_picker', esc_html__( 'Button Color', 'custom-cookie-message' ), [ $this, 'cookies_button_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_hover_color_picker', esc_html__( 'Button Hover Color', 'custom-cookie-message' ), [ $this, 'cookies_button_hover_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_text_color_picker', esc_html__( 'Button Text Color', 'custom-cookie-message' ), [ $this, 'cookies_button_text_color_picker_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_height_slider_amount', esc_html__( 'Button Height', 'custom-cookie-message' ), [ $this, 'cookies_button_height_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'button_width_slider_amount', esc_html__( 'Button Width', 'custom-cookie-message' ), [ $this, 'cookies_button_width_slider_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'modal_background', esc_html__( 'Modal Background', 'custom-cookie-message' ), [ $this, 'cookies_modal_background_callback' ], $this->section_page, 'styling' );

		add_settings_field( 'modal_background_opacity', esc_html__( 'Modal Background Opacity', 'custom-cookie-message' ), [ $this, 'cookies_modal_background_opacity_callback' ], $this->section_page, 'styling' );
	}

	/**
	 * Description Styles Options.
	 */
	public function cookies_styling_options_callback() {
		echo '<p>' . esc_html_e( 'Select the styling for the cookie message.', 'cookie-message' ) . '</p>';
	}

	/**
	 * Background color Picker field.
	 */
	public function cookies_message_color_picker_callback() {
		$val = isset( $this->options['styles']['message_color_picker'] ) ? $this->options['styles']['message_color_picker'] : '#3d3d3d';
		echo '<input type="text" id="message_color_picker" name="custom_cookie_message[styles][message_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Container Padding.
	 */
	public function cookies_message_height_slider_callback() {
		$val = isset( $this->options['styles']['message_height_slider_amount'] ) ? $this->options['styles']['message_height_slider_amount'] : '10';
		echo '<input type="text" id="message_height_slider_amount" name="custom_cookie_message[styles][message_height_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="message_height_slider" class="slider"><div id="message_height_custom_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Opacity.
	 */
	public function cookies_opacity_slider_callback() {
		$val = isset( $this->options['styles']['opacity_slider_amount'] ) ? $this->options['styles']['opacity_slider_amount'] : '100';
		echo '<input type="text" id="opacity_slider_amount" name="custom_cookie_message[styles][opacity_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="opacity_slider" class="slider"><div id="opacity_slider_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Text font family.
	 */
	public function cookies_text_font_callback() {
		$val = isset( $this->options['styles']['text_font'] ) ? $this->options['styles']['text_font'] : '';
		echo '<input type="text" id="text_font" name="custom_cookie_message[styles][text_font]" value="' . $val . '" class="regular-text ltr" />'; // WPCS: XSS ok.
		echo '<div><p>Replace your standard paragraph font-family. Leave empty for the standard font-family</p></div>';
	}

	/**
	 * Text size family.
	 */
	public function cookies_text_size_callback() {
		$val = isset( $this->options['styles']['text_size'] ) ? $this->options['styles']['text_font'] : '';
		echo '<input type="text" id="text_size" name="custom_cookie_message[styles][text_size]" value="' . $val . '" class="regular-text ltr" />'; // WPCS: XSS ok.
		echo '<div><p>Size of the text in the banner and modal</p></div>';
	}

	/**
	 * Color Text.
	 */
	public function cookies_text_color_picker_callback() {
		$val = isset( $this->options['styles']['text_color_picker'] ) ? $this->options['styles']['text_color_picker'] : '';
		echo '<input type="text" id="text_color_picker" name="custom_cookie_message[styles][text_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Color link.
	 */
	public function cookies_link_color_picker_callback() {
		$val = isset( $this->options['styles']['link_color_picker'] ) ? $this->options['styles']['link_color_picker'] : '';
		echo '<input type="text" id="link_color_picker" name="custom_cookie_message[styles][link_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button color.
	 */
	public function cookies_button_color_picker_callback() {
		$val = isset( $this->options['styles']['button_color_picker'] ) ? $this->options['styles']['button_color_picker'] : '';
		echo '<input type="text" id="button_color_picker" name="custom_cookie_message[styles][button_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button hover color.
	 */
	public function cookies_button_hover_color_picker_callback() {
		$val = isset( $this->options['styles']['button_hover_color_picker'] ) ? $this->options['styles']['button_hover_color_picker'] : '';
		echo '<input type="text" id="button_hover_color_picker" name="custom_cookie_message[styles][button_hover_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button text color.
	 */
	public function cookies_button_text_color_picker_callback() {
		$val = $this->options['styles']['button_text_color_picker'];
		echo '<input type="text" id="button_text_color_picker" name="custom_cookie_message[styles][button_text_color_picker]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Button height.
	 */
	public function cookies_button_height_slider_callback() {
		$val = isset( $this->options['styles']['button_height_slider_amount'] ) ? $this->options['styles']['button_height_slider_amount'] : '5';
		echo '<input type="text" id="button_height_slider_amount" name="custom_cookie_message[styles][button_height_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="button_height_slider" class="slider"><div id="button_height_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Button width.
	 */
	public function cookies_button_width_slider_callback() {
		$val = isset( $this->options['styles']['button_width_slider_amount'] ) ? $this->options['styles']['button_width_slider_amount'] : '10';
		echo '<input type="text" id="button_width_slider_amount" name="custom_cookie_message[styles][button_width_slider_amount]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="button_width_slider" class="slider"><div id="button_width_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

	/**
	 * Modal background.
	 */
	public function cookies_modal_background_callback() {
		$val = isset( $this->options['styles']['modal_bg'] ) ? $this->options['styles']['modal_bg'] : '#3d3d3d';
		echo '<input type="text" id="modal_bg" name="custom_cookie_message[styles][modal_bg]" value="' . $val . '" class="cpa-color-picker" >'; // WPCS: XSS ok.
	}

	/**
	 * Modal opacity.
	 */
	public function cookies_modal_background_opacity_callback() {
		$val = isset( $this->options['styles']['modal_bg_opacity'] ) ? $this->options['styles']['modal_bg_opacity'] : '50';
		echo '<input type="text" id="modal_bg_opacity_amount" name="custom_cookie_message[styles][modal_bg_opacity]" value="' . $val . '" readonly class="hidden">'; // WPCS: XSS ok.
		echo '<div id="modal_bg_opacity_slider" class="slider"><div id="modal_bg_opacity_slider_handle" class="ui-slider-handle ui-slider-handle-custom"></div></div>';
	}

}
