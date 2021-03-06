<div class="wrap settings">
	<h2>SupaPress Settings</h2>
    <?php do_action( 'supapress_admin_notices' ); ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'supapress-settings' ); ?>
		<?php do_settings_sections( 'supapress-settings' ); ?>
		<div>
			<p><label for="api_key">API Key:</label></p>
			<p><input name="api_key" id="api_key" type="text" placeholder="29b531208ee87a8e132deecdfdb209e6" value="<?php echo esc_attr( get_option('api_key') ); ?>" /></p>
		</div>
        <div>
            <p><label for="no_books">No books text (Optional):</label></p>
            <p><input name="no_books" id="no_books" type="text" placeholder="No books found..." value="<?php echo esc_attr( get_option('no_books') ); ?>" /></p>
        </div>
		<div>
			<p><label for="service_url">Service URL (Optional):</label></p>
			<p><input name="service_url" id="service_url" type="text" placeholder="http://folioservices.lb.supadu.com/" value="<?php echo esc_attr( get_option('service_url') ); ?>" /></p>
		</div>
 		<?php submit_button(); ?>
	</form>
</div>