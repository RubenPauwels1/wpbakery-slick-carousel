<?php
/*
	Plugin Name: WP Bakery - Slick Gallery Block
	Plugin URI: https://flux.be
	Description: Adds a Slick Gallery block to the WP Bakery back-end blocks.
	Author: Ruben Pauwels, Flux
	Author URI: https://flux.be
	Version: 0.1.0
	Text Domain: wpbakery-flux
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class WPBakeryShortCode_VC_Slick_Gallery extends WPBakeryShortCode {

    function __construct( ) {
        add_action('init', array($this, 'slick_gallery_mapping'));
        add_shortcode('slick_gallery', array($this, 'slick_gallery_html'));

        wp_enqueue_style( 'flux-slick-carousel-css', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
        wp_enqueue_script( 'flux-slick-carousel-js', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.0.0', true );

	}

	public function slick_gallery_html( $atts ) {

		extract( 
            shortcode_atts( 
                array(
                    'class' => '',
                    'images' => '',
                ), $atts 
            )
        );
        
        $images = $atts['images'];
        $images = explode(',', $images);

        $extra_class = $atts['class'];
        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width_class, $this->settings['base'], $atts );


	
        $output = '<div class="slick-slider ' . $extra_class . ' ' . $css_class . '">';
        foreach($images as $image_id){
            $image_url = wp_get_attachment_url( $image_id );

            $output .= '<div class="slide">';
            $output .= '<img src="' . $image_url . '" alt="' . $image_id . '" />';
            $output .= '</div>';
        }
        $output .= '</div>';
    
        return $output;
    }

    public function slick_gallery_mapping(){
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
    
        vc_map( array(
            'base' => 'slick_gallery',
            'name' => __( 'Slick Gallery', 'js_composer' ),
            'class' => 'flux_slick',
            'icon' => 'icon-heart',
            'params' => array(
                array(
                    'type' => 'textfield',
                    'holder' => 'class',
                    'class' => '',
                    'heading' => __( 'Extra class name', 'js_composer' ),
                    'param_name' => 'class',
                    'value' => "",
                    'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
                ),
                array(
                    'type' => 'attach_images',
                    'holder' => '',
                    'class' => '',
                    'heading' => __( 'Images', 'js_composer' ),
                    'param_name' => 'images',
                    'value' => "",
                    'description' => __( 'Select images from media library.', 'js_composer' ),
                ),
            ),
        ) );
    }
}

//INIT
new WPBakeryShortCode_VC_Slick_Gallery();

