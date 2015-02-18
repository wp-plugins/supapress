<?php

function supapress_register_post_types() {
	if ( class_exists( 'SupaPress_Widget' ) ) {
		SupaPress_Widget::register_post_type();
		return true;
	} else {
		return false;
	}
}

function callSupaFolio($service, $params) {

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
}

function supapress_plugin_url( $path = '' ) {
	$url = untrailingslashit( SUPAPRESS_PLUGIN_URL );

	if ( ! empty( $path ) && is_string( $path ) && false === strpos( $path, '..' ) )
		$url .= '/' . ltrim( $path, '/' );

	return $url;
}