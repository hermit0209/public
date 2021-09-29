<?php

if( ! function_exists( 'mybag_compare_page_shortcode' ) ) {
	
	function mybag_compare_page_shortcode() {
		ob_start();
		if( class_exists( 'YITH_Woocompare_Frontend' ) ) {
			global $yith_woocompare;
			
			if( function_exists( 'mybag_get_template' ) ) {
				mybag_get_template( 'shop/compare.php', array( 
					'products' 			  => $yith_woocompare->obj->get_products_list(), 
					'fields' 			  => $yith_woocompare->obj->fields(),
					'repeat_price' 		  => get_option( 'yith_woocompare_price_end' ),
					'repeat_add_to_cart'  => get_option( 'yith_woocompare_add_to_cart_end' )
				) );
			}
		} else {
			echo '<p class="alert alert-danger">' . esc_html__( 'You need to enable YITH Compare plugin for product comparison to work', 'mybag-extensions' ) . '</p>';
		}
		
		return ob_get_clean();
	}
}

add_shortcode( 'mybag_compare_page', 'mybag_compare_page_shortcode' );

if ( ! function_exists( 'mybag_vc_terms' ) ) :

	function mybag_vc_terms( $atts, $content = null ){

		$atts = shortcode_atts( array(
			'taxonomy'     => 'category',
			'orderby'      => 'name',
			'order'        => 'ASC',
			'hide_empty'   => 0,
			'include'      => '',
			'exclude'      => '',
			'number'       => 0,
			'offset'       => 0,
			'name'         => '',
			'slug'         => '',
			'hierarchical' => true,
			'child_of'     => 0,
			'parent'       => ''
		), $atts, 'mybag_terms' );

		// Unset empty optional args
		$optional_args = array( 'include', 'exclude', 'name', 'slug', 'parent' );

		foreach( $optional_args as $optional_arg ) {
			if ( empty ( $atts[ $optional_arg ] ) ) {
				unset( $atts[ $optional_arg ] );
			}
		}

		// Check for comma separated and convert into arrays
		$comma_separated_args = array( 'taxonomy', 'include', 'exclude', 'name', 'slug' );

		foreach ( $comma_separated_args as $comma_separated_arg ) {
			if ( !empty( $atts[ $comma_separated_arg ] ) ) {
				$atts[$comma_separated_arg] = explode( ',', $atts[$comma_separated_arg] );
			}
		}

		//Cast int or number
		$int_args = array( 'hide_empty', 'number', 'offset', 'hierarchical', 'child_of', 'parent' );

		foreach ( $int_args as $int_arg ) {
			if ( !empty( $atts[ $int_arg ] ) ) {
				$atts[ $int_arg ] = (int) $atts[ $int_arg ];
			}
		}

		$terms = get_terms( $atts );

		$html = '';

		foreach ( $terms as $term ) {
			$html .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
		}

		if ( ! empty( $html ) ) {
			$html = '<ul>' . $html . '</ul>';
		}

	    return $html;
	}

	add_shortcode( 'mybag_terms' , 'mybag_vc_terms' );

endif;