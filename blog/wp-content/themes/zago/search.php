<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package BlogMelody
 */

get_header(); ?>
<div class="wrapper page-section">
	<div id="primary" class="content-area">
		<main id="main" class="site-main blog-posts-wrapper" role="main">
			<div class="col-1 grid">
				<?php
				if ( have_posts() ) : ?>

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;
				else :

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
		<?php 	/**
			* Hook - zago_pagination_action.
			*
			* @hooked zago_pagination 
			*/
			 do_action('zago_pagination_action'); ?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php

get_footer();
