<?php
$title = esc_html( 'SupaPress Widgets' );
$addNew = ' <a href="' . esc_url( menu_page_url( 'supapress-new', false ) ) . '" class="add-new-h2">' . esc_html( 'Add New' ) . '</a>';
$searchCount = !empty( $_REQUEST['s'] ) ? sprintf( '<span class="subtitle">Search results for &#8220;%s&#8221;</span>', esc_html( $_REQUEST['s'] ) ) : '';
?>
<div class="wrap">
	<h2><?php echo $title.$addNew.$searchCount; ?></h2>
	<?php do_action( 'supapress_admin_notices' ); ?>
	<form method="get">
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
		<?php
		/** @type SupaPress_Widget_List_Table $list_table */
		$list_table->search_box( 'Search SupaPress Widgets', 'supapress-widget' );
		$list_table->display();
		?>
	</form>
</div>