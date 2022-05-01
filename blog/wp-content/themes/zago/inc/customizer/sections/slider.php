<?php
/**
 * Slider options.
 *
 * @package Zago
 */

$default = zago_get_default_theme_options();

// Featured Slider Section
$wp_customize->add_section( 'section_featured_slider',
	array(
		'title'      => __( 'Featured Slider Section', 'zago' ),
		'priority'   => 10,
		'capability' => 'edit_theme_options',
		'panel'      => 'home_page_panel',
		)
);

$wp_customize->add_setting( 'theme_options[disable_featured-slider_section]',
	array(
		'default'           => $default['disable_featured-slider_section'],
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'zago_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Zago_Switch_Control( $wp_customize, 'theme_options[disable_featured-slider_section]',
    array(
		'label' 	=> __('Disable slider Section', 'zago'),
		'section'    			=> 'section_featured_slider',
		'on_off_label' 		=> zago_switch_options(),
    )
) );


// Add category enable setting and control.
$wp_customize->add_setting( 'theme_options[slider_content_enable]', array(
	'default'           => $default['slider_content_enable'],
	'sanitize_callback' => 'zago_sanitize_checkbox',
) );

$wp_customize->add_control( 'theme_options[slider_content_enable]', array(
	'label'             => esc_html__( 'Enable Content', 'zago' ),
	'section'           => 'section_featured_slider',
	'type'              => 'checkbox',
	'active_callback' => 'zago_slider_active',
) );

// Number of items
$wp_customize->add_setting('theme_options[slider_speed]', 
	array(
	'default' 			=> $default['slider_speed'],
	'type'              => 'theme_mod',
	'capability'        => 'edit_theme_options',	
	'sanitize_callback' => 'zago_sanitize_number_range'
	)
);

$wp_customize->add_control('theme_options[slider_speed]', 
	array(
	'label'       => __('Slider Speed', 'zago'),
	'description' => __('Slider Speed Default speed 800', 'zago'),
	'section'     => 'section_featured_slider',   
	'settings'    => 'theme_options[slider_speed]',		
	'type'        => 'number',
	'active_callback' => 'zago_slider_active',
	)
);


$wp_customize->add_setting( 'theme_options[disable_white_overlay]',
	array(
		'default'           => $default['disable_white_overlay'],
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'zago_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Zago_Switch_Control( $wp_customize, 'theme_options[disable_white_overlay]',
    array(
		'label' 	=> __('Disable White overlay and enable image overlay', 'zago'),
		'section'    			=> 'section_featured_slider',
		'on_off_label' 		=> zago_switch_options(),
		'active_callback' => 'zago_slider_active',
    )
) );

$wp_customize->add_setting('theme_options[sr_content_type]', 
	array(
	'default' 			=> $default['sr_content_type'],
	'type'              => 'theme_mod',
	'capability'        => 'edit_theme_options',	
	'sanitize_callback' => 'zago_sanitize_select'
	)
);

$wp_customize->add_control('theme_options[sr_content_type]', 
	array(
	'label'       => __('Content Type', 'zago'),
	'section'     => 'section_featured_slider',   
	'settings'    => 'theme_options[sr_content_type]',		
	'type'        => 'select',
	'active_callback' => 'zago_slider_active',
	'choices'	  => array(
			'sr_page'	  => __('Page','zago'),
			'sr_category'	  => __('Category','zago'),
		),
	)
);



$number_of_sr_items = zago_get_option( 'number_of_sr_items' );

// Setting  Slider Category.
$wp_customize->add_setting( 'theme_options[slider_category]',
	array(

	'capability'        => 'edit_theme_options',
	'sanitize_callback' => 'absint',
	)
);
$wp_customize->add_control(
	new zago_Dropdown_Taxonomies_Control( $wp_customize, 'theme_options[slider_category]',
		array(
		'label'    => __( 'Select Category', 'zago' ),
		'section'  => 'section_featured_slider',
		'settings' => 'theme_options[slider_category]',	
		'active_callback' => 'zago_slider_category',		
		)
	)
);

for( $i=1; $i<=4; $i++ ){

	// Additional Information First Page
	$wp_customize->add_setting('theme_options[slider_page_'.$i.']', 
		array(
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',	
		'sanitize_callback' => 'zago_dropdown_pages'
		)
	);

	$wp_customize->add_control('theme_options[slider_page_'.$i.']', 
		array(
		'label'       => sprintf( __('Select Page #%1$s', 'zago'), $i),
		'section'     => 'section_featured_slider',   
		'settings'    => 'theme_options[slider_page_'.$i.']',		
		'type'        => 'dropdown-pages',
		'active_callback' => 'zago_slider_page',
		)
	);

	// Cta Button Text
	$wp_customize->add_setting('theme_options[slider_custom_btn_text_' . $i . ']', 
		array(

		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',	
		'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control('theme_options[slider_custom_btn_text_' . $i . ']', 
		array(
		'label'       => sprintf( __('Button Label %d', 'zago'),$i ),
		'section'     => 'section_featured_slider',   
		'settings'    => 'theme_options[slider_custom_btn_text_' . $i . ']',	
		'active_callback' => 'zago_slider_active',	
		'type'        => 'text',
		)
	);


	// slider hr setting and control
	$wp_customize->add_setting( 'theme_options[slider_hr_'. $i .']', array(
		'sanitize_callback' => 'sanitize_text_field'
	) );

	$wp_customize->add_control( new Zago_Customize_Horizontal_Line( $wp_customize, 'theme_options[slider_hr_'. $i .']',
		array(
			'section'         => 'section_featured_slider',
			'active_callback' => 'zago_slider_active',
			'type'			  => 'hr',
	) ) );
}

$wp_customize->add_setting( 'theme_options[disable_blog_banner_section]',
	array(
		'default'           => $default['disable_blog_banner_section'],
		'type'              => 'theme_mod',
		'capability'        => 'edit_theme_options',
		'sanitize_callback' => 'zago_sanitize_switch_control',
	)
);
$wp_customize->add_control( new Zago_Switch_Control( $wp_customize, 'theme_options[disable_blog_banner_section]',
    array(
		'label' 			=> __('Disable Blog Header Section', 'zago'),
		'section'    		=> 'section_featured_slider',
		'on_off_label' 		=> zago_switch_options(),
    )
) );