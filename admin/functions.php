<?php

function supapress_current_action() {
	if ( isset( $_POST['action'] ) ) {
		return $_POST['action'];
	} elseif ( isset( $_GET['action'] ) ) {
		return $_GET['action'];
	}

	return false;
}

function supapress_save_widget( $post_id = -1 ) {
	if ( -1 != $post_id ) {
		$widget = supapress_widget( $post_id );
	}

	if ( empty( $widget ) ) {
		$widget = SupaPress_Widget::get_template();
	}

	if ( isset( $_POST['title'] ) ) {
		$widget->set_title( sanitize_text_field( $_POST['title'] ) );
	}

	$properties = $widget->get_properties();

	foreach($properties as $key => $value) {
		if ( isset( $_POST[ $key ] ) ) {
			if($key === 'isbn_list') {
				$properties[ $key ] = explode( "\r\n", $_POST[ $key ] );
			} else {
				$properties[ $key ] = trim( $_POST[ $key ] );
			}
		}
	}

	$widget->set_properties( $properties );

	return $widget->save();
}