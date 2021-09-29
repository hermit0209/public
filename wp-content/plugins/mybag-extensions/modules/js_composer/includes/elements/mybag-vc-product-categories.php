<?php
if ( ! function_exists( 'mybag_vc_product_categories_element' ) ):

    function mybag_vc_product_categories_element( $atts, $content = null ){

        extract(   shortcode_atts( array(
            'orderby'           => 'name',
            'order'             => 'ASC',
            'slug'              => '',
        ), $atts ) );

        if( ! empty( $slug ) ) {
            $slug = explode(",", $slug);
        }

        $args = array(
            'category_args'         => array(
                'orderby'               => $orderby,
                'order'                 => $order,
                'number'                => 4,
                'hide_empty'            => false,
                'slug'                  => $slug
            )
        );

        $html = '';
        if( function_exists( 'mybag_products_categories' ) ) {
            ob_start();
            mybag_products_categories( $args );
            $html = ob_get_clean();
        }

        return $html;
    }

    add_shortcode( 'mybag_product_categories' , 'mybag_vc_product_categories_element' );

endif;