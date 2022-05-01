<?php 
/**
 * Template part for displaying Author Section
 *
 *@package Zago
 */
   
?>  

<?php 
    $message_id = zago_get_option( 'message_page' );
        $args = array (
        'post_type'     => 'page',
        'posts_per_page' => 1,
        'p' => $message_id,
        
    ); 
 
    $the_query = new WP_Query( $args );

    // The Loop
    while ( $the_query->have_posts() ) : $the_query->the_post();
    ?>
        <div class="section-content">
           <?php if(has_post_thumbnail()) : ?>
                <div class="author-thumbnail">
                    <img src="<?php the_post_thumbnail_url( 'full' ); ?>">
                </div><!-- .author-thumbnail -->
            <?php endif; ?>
            <div class="entry-contanier">

                <div class="entry-header">
                    <h2 class="entry-title"><?php the_title(); ?></h2>
                </div><!-- .section-header -->

                <div class="entry-content">
                    <?php  
                        $excerpt = zago_the_excerpt( 35 );
                        echo wp_kses_post( wpautop( $excerpt ) );
                    ?>
                </div><!-- .entry-content -->
            </div>
        </div><!-- .section-content --> 
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>