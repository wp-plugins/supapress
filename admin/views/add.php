<div class="wrap">
	<h2>Add new SupaPress widget</h2>
	<form method="post">
		<input name="action" type="hidden" value="add" />
		<div>
			<h3>Widget Title</h3>
			<input name="title" type="text" placeholder="Untitled" size="80" />
		</div>
		<div>
			<h4>Please select the type of widget you would like to use</h4>
			<label for="widget_type">ISBN Lookup</label>
			<input name="widget_type" id="widget_type" type="radio" value="isbn_lookup" checked="checked" />
		</div>
		<div>
			<h4>Please enter your list of ISBNs</h4>
			<label for="isbn_list">ISBN List</label>
			<textarea name="isbn_list" id="isbn_list"></textarea>
		</div>
		<?php submit_button(); ?>
	</form>
</div>