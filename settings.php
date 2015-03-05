<?php

require_once SUPAPRESS_PLUGIN_DIR . '/includes/functions.php';
require_once SUPAPRESS_PLUGIN_DIR . '/includes/widget-template.php';
require_once SUPAPRESS_PLUGIN_DIR . '/includes/widget.php';

if ( is_admin() ) {
	require_once SUPAPRESS_PLUGIN_DIR . '/admin/admin.php';
} else {
	require_once SUPAPRESS_PLUGIN_DIR . '/includes/controller.php';
}

add_action( 'init', 'supapress_init' );

function supapress_init() {
	supapress_register_post_types();
}

add_action( 'activate_' . SUPAPRESS_PLUGIN_BASENAME, 'supapress_install' );

function supapress_install() {
	if ( $opt = get_option( 'supapress' ) )
		return;

	if ( get_posts( array( 'post_type' => 'supapress_widget' ) ) )
		return;

	$widget = SupaPress_Widget::get_template( array(
		'title' => 'Example ISBN Lookup'
	) );

	$properties = $widget->get_properties();

	$properties['widget_type'] = 'isbn_lookup';
	$properties['per_row'] = 4;
	$properties['order'] = 'as-entered';
	$properties['show_title'] = 'on';
	$properties['show_price'] = 'off';
	$properties['show_format'] = 'off';
	$properties['show_author'] = 'off';
	$properties['show_pubdate'] = 'off';
	$properties['show_summary'] = 'off';
	$properties['isbn_list'] = array(
		'9998887770001',
		'9998887770002',
		'9998887770003',
		'9998887770004',
		'9998887770005',
		'9998887770006',
		'9998887770007',
		'9998887770008'
	);

	$widget->set_properties( $properties );
	$widget->save();
}