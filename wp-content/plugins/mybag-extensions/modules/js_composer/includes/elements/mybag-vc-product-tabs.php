<?php
if ( !function_exists( 'mybag_vc_product_tabs' ) ):

function mybag_vc_product_tabs( $atts, $content = null ){

	extract(shortcode_atts(array(
		'tab_title_1'		=> '',
		'tab_content_1'		=> '',
		'tab_title_2'		=> '',
		'tab_content_2'		=> '',
		'tab_title_3'		=> '',
		'tab_content_3'		=> '',
		'product_items'		=> 12,
		'product_columns'	=> 4
    ), $atts));

	$tabs = array(
		array(
			'shortcode'		=> $tab_content_1,
			'title'			=> $tab_title_1,
		),
		array(
			'shortcode'		=> $tab_content_2,
			'title'			=> $tab_title_2,
		),
		array(
			'shortcode'		=> $tab_content_3,
			'title'			=> $tab_title_3,
		),
	);

    $html = '';
    if( function_exists( 'mybag_product_tabs' ) ) {
		ob_start();
		mybag_product_tabs( $tabs, $product_items, $product_columns );
		$html = ob_get_clean();
    }

    return $html;
}

add_shortcode( 'mybag_product_tabs' , 'mybag_vc_product_tabs' );

endif;