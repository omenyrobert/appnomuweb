<?php
/**
 * Default theme options.
 *
 * @package Zago
 */

if ( ! function_exists( 'zago_get_default_theme_options' ) ) :

	/**
	 * Get default theme options.
	 *
	 * @since 1.0.0
	 *
	 * @return array Default theme options.
	 */
function zago_get_default_theme_options() {

	$theme_data = wp_get_theme();
	$defaults = array();

	$defaults['disable_homepage_content_section'] 			= true;
    $defaults['show_header_social_links'] 	= false;

    $defaults['disable_header_background_section'] = false;
    $defaults['show_header_search'] 	= false;

	// Featured Slider Section
	$defaults['disable_featured-slider_section']	= false;
	$defaults['slider_layout_option']			= 'default-slider';
	$defaults['slider_content_position_option']			= 'default-position';
	$defaults['sr_content_type']			= 'sr_page';
	$defaults['slider_speed']				= 800;
	$defaults['disable_white_overlay']		= false;
	$defaults['slider_content_enable']		= false;
	$defaults['disable_blog_banner_section']		= false;


	// Author Section
	$defaults['disable_message_section']	= false;

	// Latest Posts Section
	$defaults['latest_posts_title']	   	 	= esc_html__( 'Recent New Stories', 'zago' );
	$defaults['pagination_type']		= 'default';
	$defaults['latest_category_enable']		= true;
	$defaults['latest_posted_on_enable']		= true;
	$defaults['latest_video_enable']		= true;
	$defaults['latest_readmore_text']				= esc_html__('Read More','zago');

	// About Section
	$defaults['disable_about_section']	= false;
	$defaults['number_of_about_items']			= 3;
	$defaults['about_layout_option']			= 'default-about';
	$defaults['about_content_enable']		= false;


	// Single Post Option
	$defaults['single_post_category_enable']		= true;
	$defaults['single_post_posted_on_enable']		= true;
	$defaults['single_post_video_enable']		= true;
	$defaults['single_post_comment_enable']		= true;
	$defaults['single_post_pagination_enable']		= true;
	$defaults['single_post_image_enable']		= true;
	$defaults['single_post_header_image_enable']		= true;
	$defaults['single_post_header_image_as_header_image_enable']		= true;

	// Single Post Option
	$defaults['single_page_video_enable']		= true;
	$defaults['single_page_image_enable']		= true;
	$defaults['single_page_header_image_enable']		= true;
	$defaults['single_page_header_image_as_header_image_enable']		= true;
	

	//General Section
	$defaults['readmore_text']				= esc_html__('Read More','zago');
	$defaults['excerpt_length']				= 50;
	$defaults['layout_options_blog']			= 'no-sidebar';
	$defaults['layout_options_archive']			= 'no-sidebar';
	$defaults['layout_options_page']			= 'no-sidebar';	
	$defaults['layout_options_single']			= 'no-sidebar';	

	//Footer section 		
	$defaults['copyright_text']				= esc_html__( 'Copyright &copy; All rights reserved.', 'zago' );

	// Pass through filter.
	$defaults = apply_filters( 'zago_filter_default_theme_options', $defaults );
	return $defaults;
}

endif;

/**
*  Get theme options
*/
if ( ! function_exists( 'zago_get_option' ) ) :

	/**
	 * Get theme option
	 *
	 * @since 1.0.0
	 *
	 * @param string $key Option key.
	 * @return mixed Option value.
	 */
	function zago_get_option( $key ) {

		$default_options = zago_get_default_theme_options();
		if ( empty( $key ) ) {
			return;
		}

		$theme_options = (array)get_theme_mod( 'theme_options' );
		$theme_options = wp_parse_args( $theme_options, $default_options );

		$value = null;

		if ( isset( $theme_options[ $key ] ) ) {
			$value = $theme_options[ $key ];
		}

		return $value;

	}

endif;