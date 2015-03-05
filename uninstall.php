<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function supapress_delete_plugin() {
	global $wpdb;

	delete_option( 'api_key' );
	delete_option( 'no_books' );
	delete_option( 'service_url' );

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'supapress_widget',
		'post_status' => 'any'
	) );

	foreach ( $posts as $post )
		wp_delete_post( $post->ID, true );

	$table_name = $wpdb->prefix . "supapress";

	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
}

supapress_delete_plugin();