<?php
/**
 * Subtitle metabox file.
 *
 * @package BlogMelody
 * @since Zago 1.0
 */

if ( ! function_exists( 'zago_video_url_callback' ) ) :
    /** 
     * Outputs the content of the Video Url
     */
    function zago_video_url_callback( $post ) {
        wp_nonce_field( basename( __FILE__ ), 'zago_nonce' );
        $video_url = get_post_meta( $post->ID, 'zago-video-url', true );
        ?>
        <p>
         <label for="zago-video-url" class="zago-row-title"><?php esc_html_e( 'Video Url', 'zago' )?></label>
         <input class="widefat" type="url" name="zago-video-url" id="zago-video-url" value="<?php echo esc_url( $video_url ); ?>"/>     
        </p>

        <?php
    }
endif;

if ( ! function_exists( 'zago_video_url_save' ) ) :
    /**
     * Saves the Video Url input
     */
    function zago_video_url_save( $post_id ) {
        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ 'zago_nonce' ] ) && wp_verify_nonce( sanitize_key( $_POST[ 'zago_nonce' ] ), basename( __FILE__ ) ) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
            return;
        }

        // Checks for input and sanitizes/saves if needed
        if( isset( $_POST[ 'zago-video-url' ] ) ) {
            update_post_meta( $post_id, 'zago-video-url', sanitize_text_field( wp_unslash( $_POST[ 'zago-video-url' ] ) ) );
        }

    }
endif;
add_action( 'save_post', 'zago_video_url_save' );

