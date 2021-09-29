<?php
/**
 * WPBakery Visual Composer Shortcodes settings
 *
 * @package mybag
 *
 */

if ( function_exists( 'vc_map' ) ) :

	#-----------------------------------------------------------------
	# MyBag Terms
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name'        => esc_html__( 'MyBag Terms', 'mybag-extensions' ),
			'base'        => 'mybag_terms',
			'description' => esc_html__( 'Adds a shortcode for get_terms. Used to get terms including categories, product categories, etc.', 'mybag-extensions' ),
			'class'		  => '',
			'controls'    => 'full',
			'icon'        => '',
			'category'    => esc_html__( 'MyBag Elements', 'mybag-extensions' ),
			'params'      => array(
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Taxonomy', 'mybag-extensions' ),
					'param_name'   => 'taxonomy',
					'description'  => esc_html( 'Taxonomy name, or comma-separated taxonomies, to which results should be limited.', 'mybag-extensions' ),
					'value'        => 'category',
					'holder'       => 'div'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Order By', 'mybag-extensions' ),
					'param_name'   => 'orderby',
					'description'  => esc_html( 'Field(s) to order terms by. Accepts term fields (\'name\', \'slug\', \'term_group\', \'term_id\', \'id\', \'description\'). Defaults to \'name\'.', 'mybag-extensions' ),
					'value'        => 'name'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Order', 'mybag-extensions' ),
					'param_name'   => 'order',
					'description'  => esc_html( 'Whether to order terms in ascending or descending order. Accepts \'ASC\' (ascending) or \'DESC\' (descending). Default \'ASC\'.', 'mybag-extensions' ),
					'value'        => 'ASC'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Hide Empty ?', 'mybag-extensions' ),
					'param_name'   => 'hide_empty',
					'description'  => esc_html( 'Whether to hide terms not assigned to any posts. Accepts 1 or 0. Default 0.', 'mybag-extensions' ),
					'value'        => '0'
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Include IDs', 'mybag-extensions' ),
					'param_name'   => 'include',
					'description'  => esc_html( 'Comma-separated string of term ids to include.', 'mybag-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Exclude IDs', 'mybag-extensions' ),
					'param_name'   => 'exclude',
					'description'  => esc_html( 'Comma-separated string of term ids to exclude. If Include is non-empty, Exclude is ignored.', 'mybag-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Number', 'mybag-extensions' ),
					'param_name'   => 'number',
					'description'  => esc_html( 'Maximum number of terms to return. Accepts 0 (all) or any positive number. Default 0 (all).', 'mybag-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Offset', 'mybag-extensions' ),
					'param_name'   => 'offset',
					'description'  => esc_html( 'The number by which to offset the terms query.', 'mybag-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Name', 'mybag-extensions' ),
					'param_name'   => 'name',
					'description'  => esc_html( 'Name or comma-separated string of names to return term(s) for.', 'mybag-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Slug', 'mybag-extensions' ),
					'param_name'   => 'slug',
					'description'  => esc_html( 'Slug or comma-separated string of slugs to return term(s) for.', 'mybag-extensions' ),
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Hierarchical', 'mybag-extensions' ),
					'param_name'   => 'hierarchical',
					'description'  => esc_html( 'Whether to include terms that have non-empty descendants. Accepts 1 (true) or 0 (false). Default 1 (true)', 'mybag-extensions' ),
					'value'        => '1',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Child Of', 'mybag-extensions' ),
					'param_name'   => 'child_of',
					'description'  => esc_html( 'Term ID to retrieve child terms of. If multiple taxonomies are passed, child_of is ignored. Default 0.', 'mybag-extensions' ),
					'value'        => '0',
				),
				array(
					'type'         => 'textfield',
					'heading'      => esc_html( 'Parent', 'mybag-extensions' ),
					'param_name'   => 'parent',
					'description'  => esc_html( 'Parent term ID to retrieve direct-child terms of.', 'mybag-extensions' ),
					'value'        => '',
				)	
			)
		)
	);

	#-----------------------------------------------------------------
	# MyBag Blog Element
	#-----------------------------------------------------------------
	vc_map(	
		array(
			'name' => esc_html__( 'MyBag Blog', 'mybag-extensions' ),
			'base' => 'mybag_vc_blog',
			'description' => esc_html__( 'Add blog posts to your page.', 'mybag-extensions' ),
			'class'		=> '',
			'controls' => 'full',
			'icon' => '',
			'category' => esc_html__( 'MyBag Elements', 'mybag-extensions' ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Enter title', 'mybag-extensions' ),
					'param_name' => 'title',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Number of Blog to display', 'mybag-extensions' ),
					'param_name' => 'limit',
					'holder' => 'div'
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order by', 'mybag-extensions' ),
					'param_name' => 'orderby',
					'description' => esc_html__( ' Sort retrieved posts by parameter. Defaults to \'date\'. One or more options can be passed', 'mybag-extensions' ),
					'value' => 'date',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Order', 'mybag-extensions' ),
					'param_name' => 'order',
					'description' => esc_html__( 'Designates the ascending or descending order of the \'orderby\' parameter. Defaults to \'DESC\'.', 'mybag-extensions' ),
					'value' => 'DESC',
				),
			)
		)
	);

	if( class_exists( 'WooCommerce' ) ) :

		#-----------------------------------------------------------------
		# MyBag Product Element
		#-----------------------------------------------------------------
		vc_map(
			array(
				'name'			=> esc_html__( 'Products Element', 'mybag-extensions' ),
				'base'  		=> 'mybag_products',
				'description'	=> esc_html__( 'Add Products to your page.', 'mybag-extensions' ),
				'category'		=> esc_html__( 'MyBag Elements', 'mybag-extensions' ),
				'icon' 			=> '',
				'params' 		=> array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Title', 'mybag-extensions' ),
						'param_name' => 'title',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Pre Title', 'mybag-extensions' ),
						'param_name' => 'pre_title',
						'holder' => 'div'
					),

					array(
						'type'			=> 'dropdown',
						'heading'		=> esc_html__( 'Product Content', 'mybag-extensions' ),
						'param_name'	=> 'product_content',
						'value'			=> array(
							esc_html__( 'Select', 'mybag-extensions' ) 					=> '',
							esc_html__( 'Featured Products', 'mybag-extensions' )		=> 'featured_products' ,
							esc_html__( 'On Sale Products', 'mybag-extensions' )		=> 'sale_products' 	,
							esc_html__( 'Top Rated Products', 'mybag-extensions' )		=> 'top_rated_products' ,
							esc_html__( 'Recent Products', 'mybag-extensions' )			=> 'recent_products' 	,
							esc_html__( 'Best Selling Products', 'mybag-extensions' )	=> 'best_selling_products',
						),
					),

					array(
						'type' => 'textfield',
						'class' => '',
						'heading' => __( 'Extra Class', 'mybag-extensions' ),
						'param_name' => 'el_class',
						'description' => __( 'Add your extra classes here.', 'mybag-extensions')
					)
				),
			)
		);

		#-----------------------------------------------------------------
		# MyBag Product with Image Element
		#-----------------------------------------------------------------
		vc_map(
			array(
				'name'			=> esc_html__( 'Products with Image Element', 'mybag-extensions' ),
				'base'  		=> 'mybag_products_with_image',
				'description'	=> esc_html__( 'Add Products to your page.', 'mybag-extensions' ),
				'category'		=> esc_html__( 'MyBag Elements', 'mybag-extensions' ),
				'icon' 			=> '',
				'params' 		=> array(
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Limit', 'mybag-extensions' ),
						'param_name' => 'limit',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Columns', 'mybag-extensions' ),
						'param_name' => 'columns',
						'holder' => 'div'
					),

					array(
						'type'			=> 'dropdown',
						'heading'		=> esc_html__( 'Product Content', 'mybag-extensions' ),
						'param_name'	=> 'product_content',
						'value'			=> array(
							esc_html__( 'Select', 'mybag-extensions' ) 					=> '',
							esc_html__( 'Featured Products', 'mybag-extensions' )		=> 'featured_products' ,
							esc_html__( 'On Sale Products', 'mybag-extensions' )		=> 'sale_products' 	,
							esc_html__( 'Top Rated Products', 'mybag-extensions' )		=> 'top_rated_products' ,
							esc_html__( 'Recent Products', 'mybag-extensions' )			=> 'recent_products' 	,
							esc_html__( 'Best Selling Products', 'mybag-extensions' )	=> 'best_selling_products',
						),
					),

					array(
						'type' => 'attach_image',
						'heading' => __( 'Image', 'mybag-extensions' ),
						'param_name' => 'image',
					)
				),
			)
		);

		#-----------------------------------------------------------------
		# MyBag Products Carousel Element
		#-----------------------------------------------------------------
		vc_map(
			array(
				'name'			=> esc_html__( 'Products Carousel Element', 'mybag-extensions' ),
				'base'  		=> 'mybag_products_carousel',
				'description'	=> esc_html__( 'Add Products Carousel to your page.', 'mybag-extensions' ),
				'category'		=> esc_html__( 'MyBag Elements', 'mybag-extensions' ),
				'icon' 			=> '',
				'params' 		=> array(
					
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Limit', 'mybag-extensions' ),
						'param_name' => 'limit',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Orderby', 'mybag-extensions' ),
						'param_name' => 'orderby',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Order', 'mybag-extensions' ),
						'param_name' => 'order',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Product IDs', 'mybag-extensions' ),
						'param_name' => 'include',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Category', 'mybag-extensions' ),
						'param_name' => 'category',
						'holder' => 'div'
					),

					array(
						'type' => 'attach_image',
						'heading' => __( 'Background Image', 'mybag-extensions' ),
						'param_name' => 'image',
					)
				),
			)
		);

		#-----------------------------------------------------------------
		# MyBag Product Categories Element
		#-----------------------------------------------------------------
		vc_map(
			array(
				'name'			=> esc_html__( 'Product Categories Element', 'mybag-extensions' ),
				'base'  		=> 'mybag_product_categories',
				'description'	=> esc_html__( 'Add Product Categories to your page.', 'mybag-extensions' ),
				'category'		=> esc_html__( 'MyBag Elements', 'mybag-extensions' ),
				'icon' 			=> '',
				'params' 		=> array(
					
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Orderby', 'mybag-extensions' ),
						'param_name' => 'orderby',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Order', 'mybag-extensions' ),
						'param_name' => 'order',
						'holder' => 'div'
					),

					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Category Slug', 'mybag-extensions' ),
						'param_name' => 'slug',
						'holder' => 'div'
					)
				),
			)
		);

		#-----------------------------------------------------------------
		# MyBag Product Tabs Element
		#-----------------------------------------------------------------
		vc_map(
			array(
				'name'			=> esc_html__( 'Product Tabs', 'mybag-extensions' ),
				'base'  		=> 'mybag_product_tabs',
				'description'	=> esc_html__( 'Add Product Tabs to your page.', 'mybag-extensions' ),
				'category'		=> esc_html__( 'MyBag Elements', 'mybag-extensions' ),
				'icon' 			=> '',
				'params' 		=> array(
					array(
						'type'			=> 'textfield',
						'heading'		=> esc_html__('Tab #1 title', 'mybag-extensions' ),
						'param_name'	=> 'tab_title_1',
					),

					array(
						'type'			=> 'dropdown',
						'heading'		=> esc_html__( 'Tab #1 Content, Show :', 'mybag-extensions' ),
						'param_name'	=> 'tab_content_1',
						'value'			=> array(
							esc_html__( 'Select', 'mybag-extensions' )				=> '',
							esc_html__( 'Featured Products', 'mybag-extensions' )		=> 'featured_products' ,
							esc_html__( 'On Sale Products', 'mybag-extensions' )		=> 'sale_products' 	,
							esc_html__( 'Top Rated Products', 'mybag-extensions' )	=> 'top_rated_products' ,
							esc_html__( 'Recent Products', 'mybag-extensions' )		=> 'recent_products' 	,
							esc_html__( 'Best Selling Products', 'mybag-extensions' )	=> 'best_selling_products',
						),
					),

					array(
						'type'			=> 'textfield',
						'heading'		=> esc_html__('Tab #2 title', 'mybag-extensions' ),
						'param_name'	=> 'tab_title_2',
					),

					array(
						'type'			=> 'dropdown',
						'heading'		=> esc_html__( 'Tab #2 Content, Show :', 'mybag-extensions' ),
						'param_name'	=> 'tab_content_2',
						'value'			=> array(
							esc_html__( 'Select', 'mybag-extensions' ) 				=> '',
							esc_html__( 'Featured Products', 'mybag-extensions' )		=> 'featured_products' ,
							esc_html__( 'On Sale Products', 'mybag-extensions' )		=> 'sale_products' 	,
							esc_html__( 'Top Rated Products', 'mybag-extensions' )	=> 'top_rated_products' ,
							esc_html__( 'Recent Products', 'mybag-extensions' )		=> 'recent_products' 	,
							esc_html__( 'Best Selling Products', 'mybag-extensions' )	=> 'best_selling_products',
						),
					),

					array(
						'type'			=> 'textfield',
						'heading'		=> esc_html__('Tab #3 title', 'mybag-extensions' ),
						'param_name'	=> 'tab_title_3',
					),

					array(
						'type'			=> 'dropdown',
						'heading'		=> esc_html__( 'Tab #3 Content, Show :', 'mybag-extensions' ),
						'param_name'	=> 'tab_content_3',
						'value'			=> array(
							esc_html__( 'Select', 'mybag-extensions' ) 				=> '',
							esc_html__( 'Featured Products', 'mybag-extensions' )		=> 'featured_products' ,
							esc_html__( 'On Sale Products', 'mybag-extensions' )		=> 'sale_products' 	,
							esc_html__( 'Top Rated Products', 'mybag-extensions' )	=> 'top_rated_products' ,
							esc_html__( 'Recent Products', 'mybag-extensions' )		=> 'recent_products' 	,
							esc_html__( 'Best Selling Products', 'mybag-extensions' )	=> 'best_selling_products',
						),
					),

					array(
						'type' => 'textfield',
				        'heading' => esc_html__( 'Enter Product Items', 'mybag-extensions' ),
				        'param_name' => 'product_items',
				        'holder' => 'div'
			      	),

			      	array(
						'type' => 'textfield',
				        'heading' => esc_html__( 'Enter Product Columns', 'mybag-extensions' ),
				        'param_name' => 'product_columns',
				        'holder' => 'div'
			      	),
				),
			)
		);
		
	endif;

endif;