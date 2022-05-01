<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Zago
 */
?>
<?php 
	$enable_category     = zago_get_option( 'latest_category_enable' );
    $enable_posted_on     = zago_get_option( 'latest_posted_on_enable' );
    $enable_video = zago_get_option( 'latest_video_enable' );
    $header_font_size = zago_get_option( 'latest_font_size');
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid-item' ); ?>>
	<div class="post-item">

		<?php if ( has_post_thumbnail()) { ?>
			<figure>
			    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
			
                <?php $homepage_video_url = get_post_meta( get_the_ID(), 'zago-video-url', true ); ?>
                <?php if (!empty($homepage_video_url)): ?>
                   <a href="<?php the_permalink();?>"> <div class="homepage-video-icon"><i class="fa fa-play"></i></div></a>
                <?php endif ?>
            </figure>
		<?php } ?>
		<div class="entry-meta posted-on">
			<?php zago_posted_on(); ?>
		</div><!-- .entry-meta -->
		<div class="entry-container">
			<header class="entry-header">

				<div class="entry-meta">
					<?php zago_entry_meta(); ?>
				</div><!-- .entry-meta -->
				
				<?php
				if ( is_single() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title" ><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif; ?>
			</header><!-- .entry-header -->

				<div class="entry-meta author ">
					<?php zago_author(); ?>
				</div><!-- .entry-meta -->

			<div class="entry-content">
				<?php the_excerpt(); ?>
			</div><!-- .entry-content -->
			<?php $latest_readmore_text = zago_get_option( 'latest_readmore_text' );
	        if (!empty ($latest_readmore_text)) { ?>
	          <div class="latest-read-more"><a href="<?php the_permalink();?>" class="btn"><?php echo esc_html($latest_readmore_text);?></a> </div>
        <?php } ?>
		</div><!-- .entry-container -->
		
	</div><!-- .post-item -->
</article><!-- #post-## -->
