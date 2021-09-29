<?php

if ( ! function_exists( 'mybag_vc_blog_element' ) ):

	function mybag_vc_blog_element( $atts, $content = null ){

		extract(shortcode_atts(array(
			'title'				=> '',
			'limit'				=> '',
			'orderby' 			=> 'date',
			'order' 			=> 'desc',
		), $atts));

		$html = '';
		if( function_exists( 'mybag_blog_element' ) ) {
			ob_start();
			mybag_blog_element( $title, $limit, $orderby, $order );
			$html = ob_get_clean();
		}

	    return $html;
	}

	add_shortcode( 'mybag_vc_blog' , 'mybag_vc_blog_element' );

endif;