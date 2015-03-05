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
            <textarea name="per_row" id="isbn_list" rows="8" cols="80"><?php echo implode( "\n", $properties['isbn_list']); ?></textarea>
        </div>
        <div>
            <h4>Please choose how many to display per row</h4>
            <p><label for="per_row">Per row:</label></p>
            <select name="per_row" id="per_row">
                <option value="6"<?php echo supapress_selected($properties, 'per_row', 6); ?>>6</option>
                <option value="4"<?php echo supapress_selected($properties, 'per_row', 4); ?>>4</option>
                <option value="3"<?php echo supapress_selected($properties, 'per_row', 3); ?>>3</option>
                <option value="2"<?php echo supapress_selected($properties, 'per_row', 2); ?>>2</option>
                <option value="1"<?php echo supapress_selected($properties, 'per_row', 1); ?>>1</option>
            </select>
        </div>
		<div>
			<h4>Please choose you preferred order</h4>
			<p><label for="order">Order:</label></p>
			<select name="order" id="order">
				<option value="as-entered"<?php echo supapress_selected($properties, 'order', 'relevance'); ?>>As entered</option>
				<option value="publishdate-desc"<?php echo supapress_selected($properties, 'order', 'publishdate-desc'); ?>>Newest to Oldest</option>
				<option value="publishdate-asc"<?php echo supapress_selected($properties, 'order', 'publishdate-asc'); ?>>Oldest to Newest</option>
				<option value="title-az"<?php echo supapress_selected($properties, 'order', 'title-az'); ?>>Title - A to Z</option>
				<option value="title-za"<?php echo supapress_selected($properties, 'order', 'title-za'); ?>>Title - Z to A</option>
				<option value="price-asc"<?php echo supapress_selected($properties, 'order', 'price-asc'); ?>>Price - Low to High</option>
				<option value="price-desc"<?php echo supapress_selected($properties, 'order', 'price-desc'); ?>>Price - High to Low</option>
			</select>
		</div>
		<div>
			<h4>Please choose what you information you would like to display</h4>
			<div class="field-wrapper">
				<label>Title:</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_title" value="off" />
					<input type="checkbox" name="show_title" class="onoffswitch-checkbox" id="show_title"<?php echo supapress_checked($properties, 'show_title'); ?> />
					<label class="onoffswitch-label" for="show_title">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="field-wrapper">
				<label>Price (USD):</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_price" value="off" />
					<input type="checkbox" name="show_price" class="onoffswitch-checkbox" id="show_price"<?php echo supapress_checked($properties, 'show_price'); ?> />
					<label class="onoffswitch-label" for="show_price">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="field-wrapper">
				<label for="format">Format:</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_format" value="off" />
					<input type="checkbox" name="show_format" class="onoffswitch-checkbox" id="show_format"<?php echo supapress_checked($properties, 'show_format'); ?> />
					<label class="onoffswitch-label" for="show_format">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="field-wrapper">
				<label for="author">Author Name:</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_author" value="off" />
					<input type="checkbox" name="show_author" class="onoffswitch-checkbox" id="show_author"<?php echo supapress_checked($properties, 'show_author'); ?> />
					<label class="onoffswitch-label" for="show_author">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="field-wrapper">
				<label for="author">Publication Date:</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_pubdate" value="off" />
					<input type="checkbox" name="show_pubdate" class="onoffswitch-checkbox" id="show_pubdate"<?php echo supapress_checked($properties, 'show_pubdate'); ?> />
					<label class="onoffswitch-label" for="show_pubdate">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
			<div class="field-wrapper">
				<label for="author">Summary:</label>
				<div class="onoffswitch">
					<input type="hidden" name="show_summary" value="off" />
					<input type="checkbox" name="show_summary" class="onoffswitch-checkbox" id="show_summary"<?php echo supapress_checked($properties, 'show_summary'); ?> />
					<label class="onoffswitch-label" for="show_summary">
						<span class="onoffswitch-inner"></span>
						<span class="onoffswitch-switch"></span>
					</label>
				</div>
			</div>
		</div>
		<?php submit_button(); ?>
	</form>
</div>