jQuery( function ( $ ) {

  'use stric';

  var cookies = [];

  /**
   * Retrieve a list of cookies.
   *
   * @returns {Array|*}
   */
  function getCookies () {
    var pairs = document.cookie.split( ';' );
    var cookies;

    cookies = pairs.map( function ( cookie ) {
      return cookie.split( '=' )[ 0 ];
    } );

    return cookies;
  }

  $.get( {
    url: '/',
    crossDomain: true,
    beforeSend: function ( xhr ) {
      xhr.setRequestHeader( 'withCredentials', true );
    },
  } )
   .done( function () {
     cookies = getCookies();
     var html;

     cookies.map( function ( cookie ) {
       html = '<div class="cookie">';
       html = html + 'Cookie machine name: ' + cookie;
       html = html + '<label for="' + cookie + '-label">Label: ';
       html = html + '<input type="text" name="cookie_list[' + cookie.trim + '][label]" id="' + cookie + '-label">';
       html = html + '</label>';
       html = html + '</div>';

       // TODO: This code should be refactored.
       // $( '.cookie_list_wrapper' ).append( html ).fadeIn();
     } );

   } );

  // Add Color Picker to all inputs that have 'color-field' class
  $( '.cpa-color-picker' ).wpColorPicker();

  let life_time_value = 0;

  switch ( parseInt( $( '#life_time_slider_amount' ).val() ) ) {
    case 0:
      life_time_value = 1;
      break;
    case customCookieMessageAdminLocalize.life_time.week_seconds:
      life_time_value = 2;
      break;
    case customCookieMessageAdminLocalize.life_time.month_seconds:
      life_time_value = 3;
      break;
    case customCookieMessageAdminLocalize.life_time.year_seconds:
      life_time_value = 4;
      break;
    default:
      life_time_value = 5;
      break;
  }

  $( '#life_time_slider' ).slider( {
    range: 'min',
    value: parseInt( life_time_value ),
    min: 1,
    max: 5,
    create: function () {
      switch ( parseInt( $( '#life_time_slider_amount' ).val() ) ) {
        case 0:
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.no_life_time );
          break;
        case customCookieMessageAdminLocalize.life_time.week_seconds:
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.week_life_time );
          break;
        case customCookieMessageAdminLocalize.life_time.month_seconds:
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.month_life_time );
          break;
        case customCookieMessageAdminLocalize.life_time.year_seconds:
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.year_life_time );
          break;
        default:
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.end_less_life_time );
          break;
      }

    },
    slide: function ( event, ui ) {
      switch ( parseInt( ui.value ) ) {
        case 1:
          $( '#life_time_slider_amount' ).val( 0 );
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.no_life_time );
          break;
        case 2:
          $( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.week_seconds );
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.week_life_time );
          break;
        case 3:
          $( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.month_seconds );
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.month_life_time );
          break;
        case 4:
          $( '#life_time_slider_amount' ).val( customCookieMessageAdminLocalize.life_time.year_seconds );
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.year_life_time );
          break;
        case 5:
          $( '#life_time_slider_amount' ).val( 5 );
          $( '.life_time_message' ).text( customCookieMessageAdminLocalize.life_time_messages.end_less_life_time );
          break;
      }
    },
  } );

  $( '#opacity_slider' ).slider( {
    range: 'min',
    value: parseInt( $( '#opacity_slider_amount' ).val() ),
    min: 50,
    max: 100,
    create: function () {
      $( '#opacity_slider_handle' ).text( $( '#opacity_slider_amount' ).val() + '%' );
    },
    slide: function ( event, ui ) {
      $( '#opacity_slider_handle' ).text( ui.value + '%' );
      $( '#opacity_slider_amount' ).val( ui.value );
    },
  } );

  $( '#modal_bg_opacity_slider' ).slider( {
    range: 'min',
    value: parseInt( $( '#modal_bg_opacity_amount' ).val() ),
    min: 50,
    max: 100,
    create: function () {
      $( '#modal_bg_opacity_slider_handle' ).text( $( '#modal_bg_opacity_amount' ).val() + '%' );
    },
    slide: function ( event, ui ) {
      $( '#modal_bg_opacity_slider_handle' ).text( ui.value + '%' );
      $( '#modal_bg_opacity_amount' ).val( ui.value );
    },
  } );

  $( '#message_height_slider' ).slider( {
    range: 'min',
    value: parseInt( $( '#message_height_slider_amount' ).val() ),
    min: 5,
    max: 40,
    create: function () {
      $( '#message_height_custom_handle' ).text( $( '#message_height_slider_amount' ).val() + ' px' );
    },
    slide: function ( event, ui ) {
      $( '#message_height_custom_handle' ).text( ui.value + ' px' );
      $( '#message_height_slider_amount' ).val( ui.value );
    },
  } );

  $( '#button_height_slider' ).slider( {
    range: 'min',
    value: parseInt( $( '#button_height_slider_amount' ).val() ),
    min: 5,
    max: 40,
    create: function () {
      $( '#button_height_handle' ).text( $( '#button_height_slider_amount' ).val() + ' px' );
    },
    slide: function ( event, ui ) {
      $( '#button_height_handle' ).text( ui.value + ' px' );
      $( '#button_height_slider_amount' ).val( ui.value );
    },
  } );

  $( '#button_width_slider' ).slider( {
    range: 'min',
    value: parseInt( $( '#button_width_slider_amount' ).val() ),
    min: 5,
    max: 40,
    create: function () {
      $( '#button_width_handle' ).text( $( '#button_width_slider_amount' ).val() + ' px' );
    },
    slide: function ( event, ui ) {
      $( '#button_width_handle' ).text( ui.value + ' px' );
      $( '#button_width_slider_amount' ).val( ui.value );
    },
  } );

  $( '#cookies_page_link' ).ccm_suggest( {
    url: customCookieMessageAdminLocalize.rest_post_link,
    cache: false,
    beforeSend: function ( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );
    },
  }, {
    multiple: false,
  } );

  $( '#functional_cookies_ban' ).ccm_suggest( {
    url: customCookieMessageAdminLocalize.rest_cookie_list + '/functional',
    cache: false,
    beforeSend: function ( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );
    },
  }, {
    multiple: true,
  } );

  $( '#advertising_cookies_ban' ).ccm_suggest( {
    url: customCookieMessageAdminLocalize.rest_cookie_list + '/advertising',
    cache: false,
    beforeSend: function ( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );
    },
  }, {
    multiple: true,
  } );

  $( '.notice.custom-cookie-message' )
    .on( 'click', '.custom-cookie-message-upgrade', function ( e ) {
      e.preventDefault();

      let formData = new FormData();

      formData.append( '_ccm_nonce', customCookieMessageAdminLocalize.ccm_nonce );

      $.ajax( {
        url: customCookieMessageAdminLocalize.rest_url,
        method: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function ( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', customCookieMessageAdminLocalize.wp_rest_nonce );
        },
      } )
       .done( function ( response ) {
         $( '.notice.custom-cookie-message' ).fadeOut().remove();
       } )
       .fail( function ( response ) {
         $( '.notice.custom-cookie-message' ).removeClass( 'notice-info' ).addClass( 'notice-error' );
       } );
    } );
} );
