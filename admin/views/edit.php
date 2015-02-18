<?php /** @type SupaPress_Widget $post */ ?>
<?php $properties = $post->get_properties(); ?>
<div class="wrap">
	<h2>Add new SupaPress widget</h2>
	<?php do_action( 'supapress_admin_notices' ); ?>
	<form method="post">
		<input name="action" type="hidden" value="edit" />
		<input name="postId" type="hidden" value="<?php echo $post->id(); ?>" />
		<div>
			<h3>Widget Title</h3>
			<input name="title" type="text" placeholder="Untitled" size="80" value="<?php echo esc_attr( $post->title() ); ?>" />
		</div>
		<div>
			<h4>Please select the type of widget you would like to use</h4>
			<label for="widget_type">ISBN Lookup</label>
			<input name="widget_type" id="widget_type" type="radio" value="isbn_lookup" checked="checked" />
		</div>
		<div>
			<h4>Please enter your list of ISBNs</h4>
			<p><label for="isbn_list">ISBN List:</label></p>
			<textarea name="isbn_list" id="isbn_list" rows="8" cols="80"><?php echo implode( "\n", $properties['isbn_list']); ?></textarea>
		</div>
		<?php submit_button(); ?>
	</form>
</div>