<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( ! function_exists( 'blog22_enqueue_styles' ) ) :
    /**
     * Load assets.
     *
     * @since 1.0.0
     */
    function blog22_enqueue_styles() {
        wp_enqueue_style( 'blog22-style-parent', get_template_directory_uri() . '/style.css' );
        wp_enqueue_style( 'blog22-style', get_stylesheet_directory_uri() . '/style.css', array( 'blog22-style-parent' ), '1.0.0' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'blog22_enqueue_styles', 99 );

if ( ! function_exists( 'blog22_get_default_theme_options' ) ) :

    /**
     * Get default theme options.
     *
     * @since 1.0.0
     *
     * @return array Default theme options.
     */
function blog22_get_default_theme_options() {

    $theme_data = wp_get_theme();
    $defaults = array();

    $defaults['disable_homepage_content_section']           = true;
    $defaults['show_header_social_links']   = false;

    $defaults['disable_header_background_section'] = false;
    $defaults['show_header_search']     = false;

    // Featured Slider Section
    $defaults['disable_featured-slider_section']    = false;
    $defaults['slider_layout_option']           = 'default-slider';
    $defaults['slider_content_position_option']         = 'default-position';
    $defaults['sr_content_type']            = 'sr_page';
    $defaults['slider_speed']               = 800;
    $defaults['disable_white_overlay']      = true;
    $defaults['slider_content_enable']      = true;
    $defaults['disable_blog_banner_section']        = false;


    // Author Section
    $defaults['disable_message_section']    = false;

    // Latest Posts Section
    $defaults['latest_posts_title']         = esc_html__( 'Recent New Stories', 'blog22' );
    $defaults['pagination_type']        = 'default';
    $defaults['latest_category_enable']     = true;
    $defaults['latest_posted_on_enable']        = true;
    $defaults['latest_video_enable']        = true;
    $defaults['latest_readmore_text']               = esc_html__('Read More','blog22');

    // About Section
    $defaults['disable_about_section']  = false;
    $defaults['number_of_about_items']          = 3;
    $defaults['about_layout_option']            = 'default-about';
    $defaults['about_content_enable']       = false;


    // Single Post Option
    $defaults['single_post_category_enable']        = true;
    $defaults['single_post_posted_on_enable']       = true;
    $defaults['single_post_video_enable']       = true;
    $defaults['single_post_comment_enable']     = true;
    $defaults['single_post_pagination_enable']      = true;
    $defaults['single_post_image_enable']       = true;
    $defaults['single_post_header_image_enable']        = true;
    $defaults['single_post_header_image_as_header_image_enable']        = true;

    // Single Post Option
    $defaults['single_page_video_enable']       = true;
    $defaults['single_page_image_enable']       = true;
    $defaults['single_page_header_image_enable']        = true;
    $defaults['single_page_header_image_as_header_image_enable']        = true;
    

    //General Section
    $defaults['readmore_text']              = esc_html__('Read More','blog22');
    $defaults['excerpt_length']             = 20;
    $defaults['layout_options_blog']            = 'no-sidebar';
    $defaults['layout_options_archive']         = 'no-sidebar';
    $defaults['layout_options_page']            = 'no-sidebar'; 
    $defaults['layout_options_single']          = 'no-sidebar'; 

    //Footer section        
    $defaults['copyright_text']             = esc_html__( 'Copyright &copy; All rights reserved.', 'blog22' );

    return $defaults;
}
endif;
add_filter( 'zago_filter_default_theme_options', 'blog22_get_default_theme_options', 99 );


if ( ! function_exists( 'blog22_customize_backend_styles' ) ) :
    /**
     * Load assets.
     *
     * @since 1.0.0
     */
    function blog22_customize_backend_styles() {
        wp_enqueue_style( 'blog22-style', get_stylesheet_directory_uri() . '/customizer-style.css' );
    }
endif;
add_action( 'customize_controls_enqueue_scripts', 'blog22_customize_backend_styles', 99 );