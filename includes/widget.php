<?php

class SupaPress_Widget {

	const post_type = 'supapress_widget';

	private static $found_items = 0;
	private static $current = null;

	private $id;
	private $name;
	private $title;
	private $properties = array();

	public static function count() {
		return self::$found_items;
	}

	public static function get_current() {
		return self::$current;
	}

	public static function register_post_type() {
		register_post_type( self::post_type, array(
			'labels' => array(
				'name' => 'SupaPress Widgets',
				'singular_name' => 'SupaPress Widget'
			)
		) );
	}

	public static function find( $args = '' ) {
		$defaults = array(
			'post_status' => 'any',
			'posts_per_page' => -1,
			'offset' => 0,
			'orderby' => 'ID',
			'order' => 'ASC' );

		$args = wp_parse_args( $args, $defaults );
		$args['post_type'] = self::post_type;

		$q = new WP_Query();
		$posts = $q->query( $args );

		self::$found_items = $q->found_posts;

		$objs = array();

		foreach ( (array) $posts as $post ) {
			$objs[] = new self( $post );
		}

		return $objs;
	}

	public static function get_template( $args = '' ) {
		$defaults = array( 'title' => '' );
		$args = wp_parse_args( $args, $defaults );

		$title = $args['title'];

		self::$current = $widget = new self;

		$widget->title = $title ? $title : 'Untitled';

		$properties = $widget->get_properties();

		foreach ( $properties as $key => $value ) {
			$properties[$key] = SupaPress_WidgetTemplate::get_default( $key );
		}

		$widget->properties = $properties;

		$widget = apply_filters( 'supapress_widget_default_pack', $widget, $args );

		return $widget;
	}

	public static function get_instance( $post ) {
		$post = get_post( $post );

		if ( ! $post || self::post_type != get_post_type( $post ) ) {
			return false;
		}

		self::$current = $widget = new self( $post );

		return $widget;
	}

	private function __construct( $post = null ) {
		$post = get_post( $post );

		if ( $post && self::post_type == get_post_type( $post ) ) {
			$this->id = $post->ID;
			$this->name = $post->post_name;
			$this->title = $post->post_title;

			$properties = $this->get_properties();

			foreach ( $properties as $key => $value ) {
				if ( metadata_exists( 'post', $post->ID, '_' . $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, '_' . $key, true );
				} elseif ( metadata_exists( 'post', $post->ID, $key ) ) {
					$properties[$key] = get_post_meta( $post->ID, $key, true );
				}
			}

			$this->properties = $properties;
		}

		do_action( 'supapress_widget', $this );
	}

	public function __get( $name ) {
		$message = __( '<code>%1$s</code> property of a <code>WPCF7_ContactForm</code> object is <strong>no longer accessible</strong>. Use <code>%2$s</code> method instead.', 'contact-form-7' );

		if ( 'id' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'id', 'id()' ) );
			}

			return $this->id;
		} elseif ( 'title' == $name ) {
			if ( WP_DEBUG ) {
				trigger_error( sprintf( $message, 'title', 'title()' ) );
			}

			return $this->title;
		} elseif ( $prop = $this->prop( $name ) ) {
			if ( WP_DEBUG ) {
				trigger_error(
					sprintf( $message, $name, 'prop(\'' . $name . '\')' ) );
			}

			return $prop;
		}

		return $name;
	}

	public function initial() {
		return empty( $this->id );
	}

	public function prop( $name ) {
		$props = $this->get_properties();
		return isset( $props[$name] ) ? $props[$name] : null;
	}

	public function get_properties() {
		$properties = (array) $this->properties;

		$properties = wp_parse_args( $properties, array(
			'widget_type' => array(),
			'isbn_list' => array(),
            'per_row' => array(),
			'order' => array(),
			'show_title' => array(),
			'show_price' => array(),
			'show_format' => array(),
			'show_author' => array(),
			'show_pubdate' => array(),
			'show_summary' => array(),
		) );

		$properties = (array) apply_filters( 'supapress_widget_properties', $properties, $this );

		return $properties;
	}

	public function set_properties( $properties ) {
		$defaults = $this->get_properties();

		$properties = wp_parse_args( $properties, $defaults );
		$properties = array_intersect_key( $properties, $defaults );

		$this->properties = $properties;
	}

	public function id() {
		return $this->id;
	}

	public function name() {
		return $this->name;
	}

	public function title() {
		return $this->title;
	}

	public function set_title( $title ) {
		$title = trim( $title );

		if ( '' === $title ) {
			$title = 'Untitled';
		}

		$this->title = $title;
	}

	public function save() {
		$props = $this->get_properties();

		if ( $this->initial() ) {
			$post_id = wp_insert_post( array(
				'post_type' => self::post_type,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => ''
			) );
		} else {
			$post_id = wp_update_post( array(
				'ID' => (int) $this->id,
				'post_status' => 'publish',
				'post_title' => $this->title,
				'post_content' => ''
			) );
		}

		if ( $post_id ) {
			foreach ( $props as $prop => $value ) {
				update_post_meta( $post_id, '_' . $prop, $value );
			}

			if ( $this->initial() ) {
				$this->id = $post_id;
			}
		}

		return $this->id;
	}

	public function copy() {
		$new = new self;
		$new->title = $this->title . '_copy';
		$new->properties = $this->properties;

		return apply_filters( 'supapress_copy', $new, $this );
	}

	public function delete() {
		if ( $this->initial() )
			return false;

		if ( wp_delete_post( $this->id, true ) ) {
			$this->id = 0;
			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function render() {
		if($this->properties['widget_type'] === 'isbn_lookup') {
			$service = 'search';
			$params = array(
				'isbns' => implode(',', $this->properties['isbn_list']),
				'order' => $this->properties['order']
			);
		} else {
			$service = 'search';
			$params = array();
		}

		$result = supapress_call_supafolio($service, $params, $this->properties);

		if(is_string($result)) {
			return "<p>$result</p>";
		} else {
			$html = '<div class="supapress">';
			$html .= supapress_render_isbn_lookup_grid($result, $this->properties);
			$html .= '</div>';

			return $html;
		}
	}
}

function supapress_widget( $id ) {
	return SupaPress_Widget::get_instance( $id );
}

function supapress_get_contact_form_by_title( $title ) {
	$page = get_page_by_title( $title, OBJECT, SupaPress_Widget::post_type );

	if ( $page )
		return supapress_widget( $page->ID );

	return null;
}

function supapress_get_current_contact_form() {
	if ( $current = SupaPress_Widget::get_current() ) {
		return $current;
	}
}