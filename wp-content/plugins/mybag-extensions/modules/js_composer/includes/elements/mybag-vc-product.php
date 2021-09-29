<?php
if ( ! function_exists( 'mybag_vc_products_element' ) ):

    function mybag_vc_products_element( $atts, $content = null ){

        extract(   shortcode_atts( array(
            'title'             => '',
            'pre_title'         => '',
            'product_content'   => 'recent_products',
            'el_class'          => '',
        ), $atts ) );

        $args = array(
        	'title'					=> $title,
            'pre_title'             => $pre_title,
            'product_content'       => $product_content,
            'el_class'              => $el_class
        );

        $html = '';
        if( function_exists( 'mybag_products_element' ) ) {
            ob_start();
            mybag_products_element( $args );
            $html = ob_get_clean();
        }

        return $html;
    }

    add_shortcode( 'mybag_products' , 'mybag_vc_products_element' );

endif;