<?php

function supapress_register_post_types() {
	if ( class_exists( 'SupaPress_Widget' ) ) {
		SupaPress_Widget::register_post_type();
		return true;
	} else {
		return false;
	}
}

function supapress_call_supafolio($service, $params, $properties) {

	if((string) get_option('service_url') !== '') {
		$baseUrl = rtrim( get_option('service_url'), '/' ) . '/';
	} else {
		$baseUrl = SUPAPRESS_DEFAULT_SERVICE_URL;
	}

	if((string) get_option('api_key') !== '') {
		$api = trim( get_option('api_key') );
	} else {
		$api = SUPAPRESS_DEFAULT_SERVICE_API;
	}

	$url = $baseUrl . $service;

	if( count( $params ) > 0 ) {
		$url .= '?';

		foreach( $params as $key => $value ) {
            if($properties['widget_type'] === 'isbn_lookup' && $key === 'isbns' && trim( $value ) === '') {
                return (string) get_option('no_books') !== '' ? get_option('no_books') : SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE;
            }

			$url .= "$key=$value&";
		}

		$url = rtrim($url, '&');
	}

	$response = wp_remote_post( $url, array(
		'method' => 'GET',
		'timeout' => 45,
		'redirection' => 5,
		'httpversion' => '1.0',
		'blocking' => true,
		'headers' => array( 'x-apikey' => $api ),
		'body' => array(),
		'cookies' => array()
	));

	if ( is_wp_error( $response ) ) {
		return "Something went wrong: " . $response->get_error_message();
	} else {
		$result = json_decode($response['body']);

		if($result !== null) {
			if ( $result->status !== 'success' ) {
				foreach ( $result->data->errors as $error ) {
					return "Something went wrong: " . $error->message;
				}
			} else {
				return ( $result->data );
			}
		} else {
			return "Something went wrong";
		}
	}

	return "Something went wrong";
}

function supapress_plugin_url( $path = '' ) {
	$url = untrailingslashit( SUPAPRESS_PLUGIN_URL );

	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) )
		$url .= '/' . ltrim( $path, '/' );

	return $url;
}

function supapress_render_isbn_lookup_grid($result, $properties) {
	$supapress = new SupaPress_Book($result, $properties);
	$html = "";
    $perRow = "6";

    if(isset($properties['per_row']) && trim( $properties['per_row'] ) !==  '') {
        $perRow = trim( $properties['per_row'] );
    }

	if($supapress) {
		$html = "<div id='isbn-grid' class='isbn-grid per-row-{$perRow}'>";

		ob_start();

		include_once SUPAPRESS_PLUGIN_DIR . '/views/isbn-lookup-grid.php';

		$html .= ob_get_contents();
		$html .= '</div>';

		ob_end_clean();
	}

	return $html;
}