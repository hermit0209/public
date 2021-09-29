<?php

if ( ! function_exists( 'mybag_vc_products_carousel_element' ) ) :

	function mybag_vc_products_carousel_element( $atts, $content = null ){

		extract( shortcode_atts( array(
			'limit'		=> '12',
			'orderby'	=> 'date',
			'order'		=> 'desc',
			'include'	=> '',
			'category'	=> '',
			'image'	=> ''
		), $atts ) );

		$args = array(
			'per_page'	=> $limit,
			'orderby'	=> $orderby,
			'order'		=> $order,
			'include'	=> $include,
			'category'	=> $category
		);

		$html = '';
		if( function_exists( 'mybag_products_carousel' ) ) {
			ob_start();
			mybag_products_carousel( $args, $image );
			$html = ob_get_clean();
		}

		return $html;
	}

	add_shortcode( 'mybag_products_carousel' , 'mybag_vc_products_carousel_element' );

endif;