<div id="isbn-grid">
	<?php if($result->search === null) : ?>
		<p>No books found...</p>
	<?php else : ?>
		<?php foreach($result->search as $book) : ?>
			<div class="l-3 m-6 s-12">
				<img src="<?php echo $book->image; ?>" alt="<?php echo $book->title; ?>" />
				<p><?php echo $book->title; ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>