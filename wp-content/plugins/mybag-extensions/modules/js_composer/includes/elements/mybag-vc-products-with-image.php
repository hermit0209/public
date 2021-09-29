<?php
if ( ! function_exists( 'mybag_vc_products_with_image_element' ) ):

    function mybag_vc_products_with_image_element( $atts, $content = null ){

        extract(   shortcode_atts( array(
            'limit'             => 4,
            'columns'           => 2,
            'product_content'   => 'recent_products',
            'image'             => '',
        ), $atts ) );

        $args = array(
        	'limit'					=> $limit,
            'columns'               => $columns,
            'product_content'       => $product_content,
        );

        $html = '';
        if( function_exists( 'mybag_products_with_image' ) ) {
            ob_start();
            mybag_products_with_image( $args, $image );
            $html = ob_get_clean();
        }

        return $html;
    }

    add_shortcode( 'mybag_products_with_image' , 'mybag_vc_products_with_image_element' );

endif;