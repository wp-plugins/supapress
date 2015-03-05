<?php /** @type SupaPress_Book $supapress */ ?>

<?php if($supapress->has_books()) : ?>

	<?php while ( $supapress->has_books() ) : $supapress->the_book(); ?>

		<div class="book-wrapper">
			<div class="image-wrapper">
				<?php $supapress->the_cover(); ?>
			</div>
			<?php

			$supapress->the_title();

			$supapress->the_price();

			$supapress->the_format();

			$supapress->the_author();

			$supapress->the_publication_date();

			$supapress->the_summary();

			?>
		</div>

	<?php endwhile;

else :

	$supapress->no_books();

endif;