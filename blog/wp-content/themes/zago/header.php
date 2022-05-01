<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zago
 */
/**
* Hook - zago_action_doctype.
*
* @hooked zago_doctype -  10
*/
do_action( 'zago_action_doctype' );
?>
<head>
<?php
/**
* Hook - zago_action_head.
*
* @hooked zago_head -  10
*/
do_action( 'zago_action_head' );
?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'wp_body_open' ); ?>
<?php

/**
* Hook - zago_action_before.
*
* @hooked zago_page_start - 10
*/
do_action( 'zago_action_before' );

/**
*
* @hooked zago_header_start - 10
*/
do_action( 'zago_action_before_header' );

/**
*
*@hooked zago_site_branding - 10
*@hooked zago_header_end - 15 
*/
do_action('zago_action_header');

/**
*
* @hooked zago_content_start - 10
*/
do_action( 'zago_action_before_content' );

/**
 * Banner start
 * 
 * @hooked zago_banner_header - 10
*/
do_action( 'zago_banner_header' );  
