<?php 
/**
 * List of posts for post choices.
 * @return Array Array of post ids and name.
 */
function zago_post_choices() {
    $posts = get_posts( array( 'numberposts' => -1 ) );
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'zago' );
    foreach ( $posts as $post ) {
        $choices[ $post->ID ] = $post->post_title;
    }
    return  $choices;
}

if ( ! function_exists( 'zago_switch_options' ) ) :
    /**
     * List of custom Switch Control options
     * @return array List of switch control options.
     */
    function zago_switch_options() {
        $arr = array(
            'on'        => esc_html__( 'Enable', 'zago' ),
            'off'       => esc_html__( 'Disable', 'zago' )
        );
        return apply_filters( 'zago_switch_options', $arr );
    }
endif;


 /**
 * Get an array of google fonts.
 * 
 */
function zago_font_choices() {
    $font_family_arr = array();
    $font_family_arr[''] = esc_html__( '--Default--', 'zago' );

    // Make the request
    $request = wp_remote_get( get_theme_file_uri( 'assets/fonts/webfonts.json' ) );

    if( is_wp_error( $request ) ) {
        return false; // Bail early
    }
    // Retrieve the data
    $body = wp_remote_retrieve_body( $request );
    $data = json_decode( $body );
    if ( ! empty( $data ) ) {
        foreach ( $data->items as $items => $fonts ) {
            $family_str_arr = explode( ' ', $fonts->family );
            $family_value = implode( '-', array_map( 'strtolower', $family_str_arr ) );
            $font_family_arr[ $family_value ] = $fonts->family;
        }
    }

    return apply_filters( 'zago_font_choices', $font_family_arr );
}

if ( ! function_exists( 'zago_typography_options' ) ) :
    /**
     * Returns list of typography
     * @return array font styles
     */
    function zago_typography_options(){
        $choices = array(
            'default'         => esc_html__( 'Default', 'zago' ),
            'header-font-1'   => esc_html__( 'Raleway', 'zago' ),
            'header-font-2'   => esc_html__( 'Poppins', 'zago' ),
            'header-font-3'   => esc_html__( 'Montserrat', 'zago' ),
            'header-font-4'   => esc_html__( 'Open Sans', 'zago' ),
            'header-font-5'   => esc_html__( 'Lato', 'zago' ),
            'header-font-6'   => esc_html__( 'Ubuntu', 'zago' ),
            'header-font-7'   => esc_html__( 'Playfair Display', 'zago' ),
            'header-font-8'   => esc_html__( 'Lora', 'zago' ),
            'header-font-9'   => esc_html__( 'Titillium Web', 'zago' ),
            'header-font-10'   => esc_html__( 'Muli', 'zago' ),
            'header-font-11'   => esc_html__( 'Oxygen', 'zago' ),
            'header-font-12'   => esc_html__( 'Nunito Sans', 'zago' ),
            'header-font-13'   => esc_html__( 'Maven Pro', 'zago' ),
            'header-font-14'   => esc_html__( 'Cairo', 'zago' ),
            'header-font-15'   => esc_html__( 'Philosopher', 'zago' ),
            'header-font-16'   => esc_html__( 'Quicksand', 'zago' ),
            'header-font-17'   => esc_html__( 'Henny Penny', 'zago' ),
            'header-font-18'   => esc_html__( 'Fredericka', 'zago' ),
            'header-font-19'   => esc_html__( 'Marck Script', 'zago' ),
            'header-font-20'   => esc_html__( 'Kaushan Script', 'zago' ),
        );

        $output = apply_filters( 'zago_typography_options', $choices );
        if ( ! empty( $output ) ) {
            ksort( $output );
        }

        return $output;
    }
endif;


if ( ! function_exists( 'zago_body_typography_options' ) ) :
    /**
     * Returns list of typography
     * @return array font styles
     */
    function zago_body_typography_options(){
        $choices = array(
            'default'         => esc_html__( 'Default', 'zago' ),
            'body-font-1'     => esc_html__( 'Raleway', 'zago' ),
            'body-font-2'     => esc_html__( 'Poppins', 'zago' ),
            'body-font-3'     => esc_html__( 'Roboto', 'zago' ),
            'body-font-4'     => esc_html__( 'Open Sans', 'zago' ),
            'body-font-5'     => esc_html__( 'Lato', 'zago' ),
            'body-font-6'   => esc_html__( 'Ubuntu', 'zago' ),
            'body-font-7'   => esc_html__( 'Playfair Display', 'zago' ),
            'body-font-8'   => esc_html__( 'Lora', 'zago' ),
            'body-font-9'   => esc_html__( 'Titillium Web', 'zago' ),
            'body-font-10'   => esc_html__( 'Muli', 'zago' ),
            'body-font-11'   => esc_html__( 'Oxygen', 'zago' ),
            'body-font-12'   => esc_html__( 'Nunito Sans', 'zago' ),
            'body-font-13'   => esc_html__( 'Maven Pro', 'zago' ),
            'body-font-14'   => esc_html__( 'Cairo', 'zago' ),
            'body-font-15'   => esc_html__( 'Philosopher', 'zago' ),
        );

        $output = apply_filters( 'zago_body_typography_options', $choices );
        if ( ! empty( $output ) ) {
            ksort( $output );
        }

        return $output;
    }
endif;

 ?>