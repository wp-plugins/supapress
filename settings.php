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