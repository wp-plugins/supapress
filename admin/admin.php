<?php

require_once SUPAPRESS_PLUGIN_DIR . '/admin/functions.php';

require_once SUPAPRESS_PLUGIN_DIR . '/admin/includes/help-tabs.php';

add_action( 'admin_menu', 'supapress_add_menu' );

add_action( 'admin_init', 'supapress_register_settings' );

add_action( 'supapress_admin_notices', 'supapress_admin_updated_message' );

function supapress_add_menu() {
	add_object_page( 'SupaPress', 'SupaPress', 'read', 'supapress', 'supapress_list', SUPAPRESS_PLUGIN_URL . '/images/menu_icon.png' );

	add_action( 'load-' . add_submenu_page( 'supapress', 'Edit SupaPress Widget', 'supapress', 'read', 'supapress', 'supapress_list' ), 'supapress_add_page_load' );

	add_action( 'load-' . add_submenu_page( 'supapress', 'Add New SupaPress Widget', 'Add New', 'read', 'supapress-new', 'supapress_add_new_widget' ), 'supapress_add_page_load' );

	add_submenu_page( 'supapress', 'SupaPress Settings', 'Settings', 'read', 'supapress-settings', 'supapress_settings' );
}

add_filter( 'set-screen-option', 'supapress_set_screen_options', 10, 3 );

function supapress_set_screen_options( $result, $option, $value ) {
	$supapress_screens = array( 'supapress_widgets_per_page' );

	if ( in_array( $option, $supapress_screens ) )
		$result = $value;

	return $result;
}

add_action( 'admin_enqueue_scripts', 'supapress_admin_enqueue_scripts' );

function supapress_admin_enqueue_scripts() {
	wp_enqueue_style( 'supapress-admin', supapress_plugin_url( 'admin/css/styles.css' ), array(), SUPAPRESS_VERSION, 'all' );
}

function supapress_list() {
	if ( $post = supapress_get_current_contact_form() ) {
		include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/edit.php';
	} else {
		$list_table = new SupaPress_Widget_List_Table();
		$list_table->prepare_items();

		include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/list.php';
	}
};

function supapress_add_new_widget() {
	include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/add.php';
};

function supapress_settings() {
	include_once SUPAPRESS_PLUGIN_DIR . '/admin/views/settings.php';
};

function supapress_register_settings() {
	register_setting( 'supapress-settings', 'api_key' );
	register_setting( 'supapress-settings', 'service_url' );
}

function supapress_admin_updated_message() {
	if ( empty( $_REQUEST['message'] ) )
		return;

	if ( 'created' == $_REQUEST['message'] ) {
		$updated_message = esc_html( 'SupaPress Widget created.' );
	} elseif ( 'saved' == $_REQUEST['message'] ) {
		$updated_message = esc_html( 'SupaPress Widget updated.' );
	} elseif ( 'deleted' == $_REQUEST['message'] ) {
		$updated_message = esc_html( 'SupaPress Widget deleted.' );
	}

	if ( empty( $updated_message ) )
		return;

	echo "<div id='message' class='updated'><p>$updated_message</p></div>";
}

function supapress_add_page_load() {
	global $plugin_page;

	$action = supapress_current_action();
	$current_screen = get_current_screen();
	$help_tabs = new SupaPress_Help_Tabs( $current_screen );
	$post = null;

	$_GET['post'] = isset( $_GET['post'] ) ? $_GET['post'] : '';

	if($action === 'add') {
		$id = supapress_save_widget();

		$query = array(
			'message' => 'created',
			'post' => $id
		);

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif($action === 'edit' && !empty( $_POST ) ) {
		$id = supapress_save_widget($_POST['postId']);

		$query = array(
			'message' => 'saved',
			'post' => $id
		);

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif ( 'copy' == $action ) {
		$id = absint( $_GET['post'] );

		check_admin_referer( 'supapress-copy-widget_' . $id );

		if ( ! current_user_can( 'publish_pages', $id ) )
			wp_die( 'You are not allowed to edit this item.' );

		$query = array();

		if ( $widget = supapress_widget( $id ) ) {
			$new_widget = $widget->copy();
			$new_widget->save();

			$query['post'] = $new_widget->id();
			$query['message'] = 'created';
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} elseif ( 'delete' == $action ) {
		if ( ! empty( $_POST['postId'] ) )
			check_admin_referer( 'supapress-delete-widget_' . $_POST['postId'] );
		elseif ( ! is_array( $_REQUEST['post'] ) )
			check_admin_referer( 'supapress-delete-widget_' . $_REQUEST['post'] );
		else
			check_admin_referer( 'bulk-posts' );

		$posts = empty( $_POST['postId'] )
			? (array) $_REQUEST['post']
			: (array) $_POST['postId'];

		$deleted = 0;

		foreach ( $posts as $post ) {
			$post = SupaPress_Widget::get_instance( $post );

			if ( empty( $post ) )
				continue;

			if ( ! current_user_can( 'publish_pages', $post->id() ) )
				wp_die( 'You are not allowed to delete this item.' );

			if ( ! $post->delete() )
				wp_die( 'Error in deleting.' );

			$deleted += 1;
		}

		$query = array();

		if ( ! empty( $deleted ) )
			$query['message'] = 'deleted';

		$redirect_to = add_query_arg( $query, menu_page_url( 'supapress', false ) );

		wp_safe_redirect( $redirect_to );
	} else {
		if ( ! empty( $_GET['post'] ) ) {
			$post = SupaPress_Widget::get_instance( $_GET['post'] );
		}

		if( $post ) {
			$help_tabs->set_help_tabs( 'edit' );
		} elseif( $plugin_page === 'supapress-new' ) {
			$help_tabs->set_help_tabs( 'add' );
		} else {
			$help_tabs->set_help_tabs( 'list' );
		}

		if ( ! class_exists( 'SupaPress_Widget_List_Table' ) ) {
			require_once SUPAPRESS_PLUGIN_DIR . '/admin/includes/widget-list-table.php';
		}

		add_filter( 'manage_' . $current_screen->id . '_columns',
			array( 'SupaPress_Widget_List_Table', 'define_columns' ) );

		add_screen_option( 'per_page', array(
			'label' => 'Widgets',
			'default' => 20,
			'option' => 'supapress_widgets_per_page'
		) );
	}
}