<?php

if ( ! class_exists( 'WP_List_Table' ) )
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class SupaPress_Widget_List_Table extends WP_List_Table {

	public static function define_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Title',
			'shortcode' => 'Shortcode',
			'author' => 'Author',
			'date' => 'Date'
		);

		return $columns;
	}

	function __construct() {
		parent::__construct( array(
			'singular' => 'post',
			'plural' => 'posts',
			'ajax' => false
		) );
	}

	function prepare_items() {
		$per_page = $this->get_items_per_page( 'supapress_widgets_per_page' );

		$this->_column_headers = $this->get_column_info();

		$args = array(
			'posts_per_page' => $per_page,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => ( $this->get_pagenum() - 1 ) * $per_page
		);

		if ( ! empty( $_REQUEST['s'] ) )
			$args['s'] = $_REQUEST['s'];

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			if ( 'title' == $_REQUEST['orderby'] )
				$args['orderby'] = 'title';
			elseif ( 'author' == $_REQUEST['orderby'] )
				$args['orderby'] = 'author';
			elseif ( 'date' == $_REQUEST['orderby'] )
				$args['orderby'] = 'date';
		}

		if ( ! empty( $_REQUEST['order'] ) ) {
			if ( 'asc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'ASC';
			elseif ( 'desc' == strtolower( $_REQUEST['order'] ) )
				$args['order'] = 'DESC';
		}

		$this->items = SupaPress_Widget::find( $args );
		$total_items = SupaPress_Widget::count();
		$total_pages = ceil( $total_items / $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => $total_pages,
			'per_page' => $per_page
		) );
	}

	function get_columns() {
		return get_column_headers( get_current_screen() );
	}

	function get_sortable_columns() {
		$columns = array(
			'title' => array( 'title', true ),
			'author' => array( 'author', false ),
			'date' => array( 'date', false )
		);

		return $columns;
	}

	function get_bulk_actions() {
		return array( 'delete' => 'Delete' );
	}

	function column_default() {
		return '';
    }

	function column_cb( $item ) {
		/** @type SupaPress_Widget $item  */
		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->id() );
	}

	function column_title( $item ) {
		/** @type SupaPress_Widget $item  */
		$url = admin_url( 'admin.php?page=supapress&post=' . absint( $item->id() ) );
		$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );

		$actions = array( 'edit' => '<a href="' . $edit_link . '">Edit</a>' );

		if ( current_user_can( 'publish_pages', $item->id() ) ) {
			$copy_link = wp_nonce_url( add_query_arg( array( 'action' => 'copy' ), $url ), 'supapress-copy-widget_' . absint( $item->id() ) );
			$actions = array_merge( $actions, array( 'copy' => '<a href="' . $copy_link . '">Duplicate</a>' ) );
		}

		$a = sprintf( '<a class="row-title" href="%1$s" title="%2$s">%3$s</a>',	$edit_link,	esc_attr( sprintf( 'Edit &#8220;%s&#8221;',	$item->title() ) ),	esc_html( $item->title() ) );

		return '<strong>' . $a . '</strong> ' . $this->row_actions( $actions );
    }

	function column_author( $item ) {
		/** @type SupaPress_Widget $item  */
		$post = get_post( $item->id() );

		if ( ! $post )
			return false;

		$author = get_userdata( $post->post_author );

		return esc_html( $author->display_name );
    }

	function column_shortcode( $item ) {
		/** @type SupaPress_Widget $item  */
		$shortcodes = array( sprintf( '[supapress id="%1$d" title="%2$s"]', $item->id(), $item->title() ) );
		$output = '';

		foreach ( $shortcodes as $shortcode ) {
			$output .= "\n" . '<input type="text"'
				. ' onfocus="this.select();" readonly="readonly"'
				. ' value="' . esc_attr( $shortcode ) . '"'
				. ' class="shortcode-in-list-table wp-ui-text-highlight code" />';
		}

		return trim( $output );
	}

	function column_date( $item ) {
		/** @type SupaPress_Widget $item  */
		$post = get_post( $item->id() );

		if ( ! $post )
			return false;

		$t_time = mysql2date( 'Y/m/d g:i:s A', $post->post_date, true );
		$m_time = $post->post_date;
		$time = mysql2date( 'G', $post->post_date ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
			$h_time = sprintf( '%s ago', human_time_diff( $time ) );
		} else {
			$h_time = mysql2date( 'Y/m/d', $m_time );
		}

		return '<abbr title="' . $t_time . '">' . $h_time . '</abbr>';
    }
}