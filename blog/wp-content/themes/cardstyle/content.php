<?php $format = get_post_format(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-card-outer">
		<div class="blog-card group">
			
			<div class="blog-card-inner" style="background-image:url('<?php the_post_thumbnail_url('cardstyle-large'); ?>');">
			
				<?php if ( has_post_format('video') && !is_sticky() ) echo'<span class="thumb-icon"><i class="fas fa-play"></i></span>'; ?>
				<?php if ( has_post_format('audio') && !is_sticky() ) echo'<span class="thumb-icon"><i class="fas fa-volume-up"></i></span>'; ?>
				<?php if ( is_sticky() ) echo'<span class="thumb-icon"><i class="fas fa-star"></i></span>'; ?>
				
				<div class="blog-card-inner-inner">
					<h2 class="blog-card-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				</div>
				
			</div>
			
			<div class="blog-card-bottom-outer">
				<div class="blog-card-bottom">
					<div class="blog-card-author">
						<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php echo get_avatar(get_the_author_meta('user_email'),'128'); ?></a>
					</div>
					<a class="blog-card-more-link" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more','cardstyle'); ?> <i class="fas fa-chevron-right"></i></a>
					<ul class="blog-card-meta group">
						<li class="blog-card-date"><i class="far fa-calendar"></i><?php the_time( get_option('date_format') ); ?></li>
						<?php if ( comments_open() && ( get_theme_mod( 'comment-count', 'on' ) =='on' ) ): ?>
							<li class="blog-card-comments"><i class="far fa-comment"></i><a href="<?php comments_link(); ?>"><?php comments_number( '0', '1', '%' ); ?></a></li>
						<?php endif; ?>
					</ul>
					
					<?php if (get_theme_mod('excerpt-length','26') != '0'): ?>
						<div class="blog-card-excerpt">
							<?php the_excerpt(); ?>
						</div>	
					<?php endif; ?>
					
				</div>
				<div class="blog-card-category group"><?php the_category(' '); ?></div>
			</div>
			
		</div>
	</div>
</article><!--/.post-->	