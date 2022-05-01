<?php
/**
 * Post Slider options.
 *
 * @package Zago
 */

$default = zago_get_default_theme_options();

// Post Slider Author Section
$wp_customize->add_section( 'section_home_about',
	array(
		'title'      => __( 'Posts Slider', 'zago' ),
		'priority'   => 25,
		'capability' => 'edit_theme_options',
		'panel'      => 'home_page_panel',
		)
);

$wp_customize->add_setting( 'theme_options[disable_about_section]',
	array(
		'default'           => $default['disable_about_section'],
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'zago_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Zago_Switch_Control( $wp_customize, 'theme_options[disable_about_section]',
    array(
		'label' 			=> __('Enable/Disable Post Slider Section', 'zago'),
		'section'    		=> 'section_home_about',
		 'settings'  		=> 'theme_options[disable_about_section]',
		'on_off_label' 		=> zago_switch_options(),
    )
) );
// Add arrow enable setting and control.
$wp_customize->add_setting( 'theme_options[about_layout_option]', array(
	'default'           => $default['about_layout_option'],
	'sanitize_callback' => 'zago_sanitize_select',
) );

$wp_customize->add_control( 'theme_options[about_layout_option]', array(
	'label'             => esc_html__( 'Choose Featured Layout', 'zago' ),
	'section'           => 'section_home_about',
	'type'              => 'radio',
	'active_callback' => 'zago_about_active',
	'choices'				=> array( 
		'default-about'     => esc_html__( 'Default Design(Text Over Image)', 'zago' ), 
		'about-two'     => esc_html__( 'Design Two(Text Under Image)', 'zago' ),
		)
) );

// Add posted on enable setting and control.
$wp_customize->add_setting( 'theme_options[about_content_enable]', array(
	'default'           => $default['about_content_enable'],
	'sanitize_callback' => 'zago_sanitize_checkbox',
) );

$wp_customize->add_control( 'theme_options[about_content_enable]', array(
	'label'             => esc_html__( 'Enable Content', 'zago' ),
	'section'           => 'section_home_about',
	'type'              => 'checkbox',
	'active_callback' => 'zago_about_active',
) );


// Setting  Team Category.
$wp_customize->add_setting( 'theme_options[about_category]',
	array(

	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new Zago_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[about_category]',
		array(
		'label'    => __( 'Select Category', 'zago' ),
		'section'  => 'section_home_about',
		'settings' => 'theme_options[about_category]',	
		'active_callback' => 'zago_about_active',		
		)
	)
);
