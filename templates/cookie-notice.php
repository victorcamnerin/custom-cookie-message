<?php
/**
 * Template cookie notice.
 *
 * @package CustomCookieMessage.
 */

require_once ABSPATH . 'wp-admin/includes/plugin.php';

$options = get_option( 'custom_cookie_message' );

/**
 * To support polylang translations.
 *
 * @var esc_html_e()
 */
$esc_html = 'esc_html_e';

if ( is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'polylang-pro/polylang.php' ) ) {
	$esc_html = 'pll_e';
}

$functional_check  = 'checked';
$advertising_check = 'checked';

if ( ! empty( $_COOKIE['custom_cookie_message'] ) ) {
	$cookie_preferences = json_decode( stripslashes( $_COOKIE['custom_cookie_message'] ) );

	// JSON Cookie values are strings.
	$functional_check  = 'false' === $cookie_preferences->functional ? '' : $functional_check;
	$advertising_check = 'false' === $cookie_preferences->advertising ? '' : $advertising_check;

}

?>
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<defs>
<symbol id="icon-cancel-circle" viewBox="0 0 32 32">
<title>cancel-circle</title>
<path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM16 29c-7.18 0-13-5.82-13-13s5.82-13 13-13 13 5.82 13 13-5.82 13-13 13z"></path>
<path d="M21 8l-5 5-5-5-3 3 5 5-5 5 3 3 5-5 5 5 3-3-5-5 5-5z"></path>
</symbol>
</defs>
</svg>
<div id="custom-cookie-message-banner" class="custom-cookie-message-banner custom-cookie-message-banner--<?php echo esc_attr( $options['general']['location_options'] ); ?>">
	<div class="custom-cookie-message-banner__content">
		<div class="custom-cookie-message-banner__text">
			<p><?php $esc_html( $options['content']['textarea_warning_text'], 'custom-cookie-message' ); ?>
				<?php if ( $options['general']['cookies_page_link'] ) : ?>
					<a href="<?php echo esc_url( $options['general']['cookies_page_link'] ); ?>" title="<?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?>"><?php $esc_html( $options['content']['input_link_text'], 'custom-cookie-message' ); ?></a>
					<?php endif; ?>
				<button id="custom-cookie-message-preference" class=".custom-cookie-message-banner__button">
					<?php $esc_html( $options['content']['input_button_text'], 'custom-cookie-message' ); ?>
				</button>
			</p>
		</div>
		<div class="custom-cookie-message-banner__close"><?php esc_html_e( 'Close', 'custom-cookie-message' ); ?>                <svg class="icon icon-cancel-circle"><use xlink:href="#icon-cancel-circle"></use></svg></div>
	</div>
</div>

<div id="custom-cookie-message-modal" class="custom-cookie-message-modal custom-cookie-message-modal--off">
	<div class="custom-cookie-message-modal__box">
		<div class="custom-cookie-message-modal__close"><?php esc_html_e( 'Close', 'custom-cookie-message' ); ?><svg class="icon icon-cancel-circle"><use xlink:href="#icon-cancel-circle"></use></svg></div>
		<h2 class="custom-cookie-message-modal__title"><?php $esc_html( $options['cookie_granularity_settings']['headline'], 'custom-cookie-message' ); ?></h2>
		<div class="custom-cookie-message-modal__tabs">
			<ul class="custom-cookie-message-modal__list">
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--required_message custom-cookie-message-modal__item--active"><?php esc_html_e( 'Required Cookies', 'custom-cookie-message' ); ?></li>
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--functional_message"><?php esc_html_e( 'Functional Cookies', 'custom-cookie-message' ); ?></li>
				<li class="custom-cookie-message-modal__item custom-cookie-message-modal__item--advertising_message"><?php esc_html_e( 'Advertising Cookies', 'custom-cookie-message' ); ?></li>
			</ul>
		</div>
		<div class="custom-cookie-message-modal__content">
			<div class="custom-cookie-message-modal__required_message">
				<?php echo wpautop( $options['cookie_granularity_settings']['required_cookies_message'] ); // WPCS: XSS ok. ?>
			</div>
			<div class="custom-cookie-message-modal__functional_message hide">
				<?php echo wpautop( $options['cookie_granularity_settings']['functional_cookies_message'] ); // WPCS: XSS ok. ?>
				<label class="custom-cookie-message-modal__checkbox">
					<?php esc_html_e( 'Active', 'custom-cookie-message' ); ?>
					<input type="checkbox" id="ccm-functional" <?php echo esc_attr( $functional_check ); ?>>
				</label>
			</div>
			<div class="custom-cookie-message-modal__advertising_message hide">
				<?php echo wpautop( $options['cookie_granularity_settings']['advertising_cookies_message'] ); // WPCS: XSS ok. ?>
				<label class="custom-cookie-message-modal__checkbox">
					<?php esc_html_e( 'Active', 'custom-cookie-message' ); ?>
					<input type="checkbox" id="ccm-advertising" <?php echo esc_attr( $advertising_check ); ?>>
				</label>
			</div>
		</div>
		<div class="custom-cookie-message-modal__actions">
			<button id="ccm-save-preference" class=".custom-cookie-message-popup__button"><?php $esc_html( $options['content']['save_settings_button'], 'custom-cookie-message' ); ?></button>
		</div>
	</div>
</div>
