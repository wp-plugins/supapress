<?php

class SupaPress_Help_Tabs {

	private $screen;

	public function __construct( WP_Screen $screen ) {
		$this->screen = $screen;
	}

	public function set_help_tabs( $type ) {
		switch ( $type ) {
			case 'list':
				$this->screen->add_help_tab( array(
					'id' => 'list_overview',
					'title' => 'Overview',
					'content' => $this->content( 'list_overview' )
				) );

				$this->screen->add_help_tab( array(
					'id' => 'list_available_actions',
					'title' => 'Available Actions',
					'content' => $this->content( 'list_available_actions' )
				) );

				$this->sidebar();

				return;
			case 'add':
				$this->screen->add_help_tab( array(
					'id' => 'add_new',
					'title' => 'Adding A Widget',
					'content' => $this->content( 'add_new' )
				) );

				$this->sidebar();

				return;
			case 'edit':
				$this->screen->add_help_tab( array(
					'id' => 'edit_overview',
					'title' => 'Overview',
					'content' => $this->content( 'edit_overview' )
				) );

				$this->sidebar();

				return;
		}
	}

	private function content( $name ) {
		$content = array();

		$content['list_overview'] = '<p>On this screen, you can manage widgets provided by SupaPress. You can manage an unlimited number of widgets. Each widget has a unique ID and SupaPress shortcode ([supapress ...]). To insert a widget into a post or a text widget, insert the shortcode into the target.</p>';
		$content['list_available_actions'] = '<p>Hovering over a row in the widgets list will display action links that allow you to manage your widget. You can perform the following actions:</p>';
		$content['list_available_actions'] .= '<p><strong>Edit</strong> - Navigates to the editing screen for that widget. You can also reach that screen by clicking on the widget title.</p>';
		$content['list_available_actions'] .= '<p><strong>Duplicate</strong> - Clones that widget. A cloned widget inherits all content from the original, but has a different ID.</p>';
		$content['add_new'] = '<p>You can add a new widget on this screen.</p>';
		$content['edit_overview'] = '<p>On this screen, you can edit a widget. A widget will be one of the following:</p>';

		if ( ! empty( $content[$name] ) ) {
			return $content[$name];
		}
	}

	public function sidebar() {
		$content = '<p><strong>For more information:</strong></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">Docs</a></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">FAQ</a></p>';
		$content .= '<p><a href="http://www.supadu.com/" target="_blank">Support</a></p>';

		$this->screen->set_help_sidebar( $content );
	}
}