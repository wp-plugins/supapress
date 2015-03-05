<?php

require_once SUPAPRESS_PLUGIN_DIR . '/includes/book.php';

add_action( 'plugins_loaded', 'supapress_add_shortcodes' );

function supapress_add_shortcodes() {
	add_shortcode( 'supapress', 'supapress_widget_short_tag' );
}

function supapress_widget_short_tag( $atts, $content = null, $code = '' ) {
	if ( is_feed() ) {
		return '[supapress]';
	}

	if ( 'supapress' == $code ) {
		$atts = shortcode_atts( array(
			'id' => 0,
			'title' => '',
		    'content' => $content
		), $atts );

		$id = (int) $atts['id'];
		$title = trim( $atts['title'] );

		if ( ! $widget = supapress_widget( $id ) ) {
			$widget = supapress_get_contact_form_by_title( $title );
		}
	} else {
		$widget = false;
	}

	if ( ! $widget )
		return '[supapress 404 "Not Found"]';

	return $widget->render();
}

add_action( 'wp_enqueue_scripts', 'supapress_do_enqueue_scripts' );

function supapress_do_enqueue_scripts() {
	wp_enqueue_style( 'supapress', supapress_plugin_url( 'includes/css/styles.css' ), array(), SUPAPRESS_VERSION, 'all' );
	wp_enqueue_script( 'supapress', supapress_plugin_url( 'includes/js/scripts.js' ), array( 'jquery' ), SUPAPRESS_VERSION, true );
}