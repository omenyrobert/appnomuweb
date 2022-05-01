<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zago
 */

/**
 *
 * @hooked zago_mustread_section
 */
if (is_home()) {

	do_action( 'zago_action_mustread' );
}

/**
 *
 * @hooked zago_footer_start
 */
do_action( 'zago_action_before_footer' );

/**
 * Hooked - zago_footer_top_section -10
 * Hooked - zago_footer_section -20
 */
do_action( 'zago_action_footer' );

/**
 * Hooked - zago_footer_end. 
 */
do_action( 'zago_action_after_footer' );

wp_footer(); ?>

</body>  
</html>