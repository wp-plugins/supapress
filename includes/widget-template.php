<?php

class SupaPress_WidgetTemplate {

	public static function get_default( $prop = null ) {

		echo "$prop";

		if ( $prop === 'isbn_list' ) {
			$template = self::isbn_list();
		} elseif( $prop === 'widget_type' ) {
			$template = self::widget_type();;
		} else {
			$template = $prop;
		}

		return apply_filters( 'supapress_default_template', $template, $prop );
	}

	public static function widget_type() {
		$template =
			'<div>
				<h4>Please select the type of widget you would like to use</h4>
				<label for="widget_type">ISBN Lookup</label>
				<input name="widget_type" id="widget_type" type="radio" value="isbn_lookup" checked="checked" />
			</div>';

		return $template;
	}

	public static function isbn_list() {
		$template =
			'<div>
				<h4>Please enter your list of ISBNs</h4>
				<label for="isbn_list">ISBN List</label>
				<textarea name="isbn_list" id="isbn_list"></textarea>
			</div>';

		return $template;
	}
}