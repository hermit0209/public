/**
 * QueryLoop Plugin Script
 * http://queryloop.com
 */
var QL_VisualAttributes, qlva;

(function($){

	'use strict';

	Object.defineProperty( String.prototype, 'sanitize', {
		value: function () {
			return this.replace(/[\s!"#$%&'()*+,.\/:;<>?@^`{|}~]/g, "\\$&");
		}
	});

	QL_VisualAttributes = {

		ready: function(){
			$('.ql-visual-attributes:not(.va-show-on-loop)').each(function(){

				var $va = $(this),
					$form = $va.closest('form'),
					$vars = $form.find('.variations');

				// Attach events to visual options.
				$va.on('click', '.va-picker:not(.va-hidden)', function(e){
					e.preventDefault();
					var $self = $(this),
						attribute = $self.data('attribute').sanitize(),
						$select = $vars.find('#' + attribute );

					if ( ! $self.hasClass('va-selected') ) {
						$self.addClass('va-selected').siblings( 'a[data-attribute=' + attribute + ']' ).removeClass('va-selected');
						if ( $select.find('option[value=' + String( $self.data('term') ).sanitize() + ']').length > 0 )
							$select.find('option[value=' + String( $self.data('term') ).sanitize() + ']').prop('selected', true).trigger('change');
						else
							$select.find('option').prop('selected', false).trigger('change');
					} else if ( qlva.secondClickDeselects ) {
						$self.removeClass('va-selected').siblings('a[data-attribute=' + attribute + ']').removeClass('va-selected');
						$select.val('').trigger('change');
					}

					// Event: after this option was selected. Receives the selected link object as argument.
					$('body').trigger( 'ql_visual_attributes_option_selected', [$self] );
				});

				if ( 'reclick' === qlva.reclick ) {
					$va.addClass('va-reclick');
					// Attach event to unselected attributes (or "re-click")
					$('body').on('click', '.va-hidden', function() {
						var $picker = $(this);
						$picker.removeClass('va-hidden');
						setTimeout(function() {
							$picker.trigger('click');
						}, 250);
					});
				}

				var $jckwt = $va.closest('.product').find( '#jckWooThumbs_img_wrap' );

				// Attach events to standard select
				$va.find('select').each(function(){
					$(this).on('change', function(){
						var $self = $(this),
							$select = $vars.find('#' + $self.data('attribute').sanitize()),
							selectedOption = $self.find('option:selected').val();

						$select.find('option:selected').prop('selected', false);
						if ( $jckwt.length > 0 ) {	$jckwt.addClass('reset'); }
						if ( '' !== selectedOption ) {
							$form.trigger( 'check_variations', [ '', false ] );
							$select.find('option[value=' + String( selectedOption ).sanitize() + ']').prop('selected', true).trigger('change');
						} else {
							$select.trigger('change');
						}
						if ( $jckwt.length > 0 ) { $jckwt.removeClass('reset'); }

						// Event: after this option was selected. Receives the <select> object as argument.
						$('body').trigger( 'ql_visual_attributes_option_selected', [$self] );
					});
				});

			});
		},

		load: function(){
			$('.ql-visual-attributes:not(.va-show-on-loop)').each(function(){

				var $va = $(this),
					$form = $va.closest('form');

				// Listen to changes for variations composed of many attributes
				$form.on('update_variation_values', function(e, variations){
					var variations = $form.data( 'product_variations' );
					QL_VisualAttributes.updateVisualAttributes( $va, variations );
				});

				$('body').on('found_variation', '.variations_form', function(){
					$(this).find('.va-picker.va-selected').siblings().addClass('va-hidden');
				});

				$form.find('.reset_variations').prependTo($va).on( 'click', function(e) {
					e.preventDefault();
					$va.find('.va-selected').removeClass('va-selected');
					$va.find('select').each(function(){
						$(this).prop('selectedIndex', 0);
					});

					// Event: after options have been reset
					$('body').trigger( 'ql_visual_attributes_options_reset' );
				}).css({ 'visiblity': 'hidden', 'display': 'block' });

				// Setup dropdowns and visual attributes that are initially selected
				$va.find('.va-start-selected').each(function(){
					var $self = $(this);

					// Disable initial autonomous check that removes attributes not matching any variation
					QL_VisualAttributes.firstCheck[$va.closest('form').data('product_id')] = false;

					// Update dropdowns and visual attributes
					$form.find( '#' + $self.data('attribute') ).trigger('change');
					// Highlight initially selected attribute.
					$self.addClass( 'va-selected' );//.trigger('click').trigger('click');
				});

				$form.trigger('check_variations');

				// Finally, show visual attributes
				$va.slideDown(300);

			});

			$('.shop_attributes.ql-visual-attributes').each(function(){
				$(this).find('tr:odd').removeClass('alt');
			});

			// Event: after events are attached on window load
			$('body').trigger( 'ql_visual_attributes_load' );

			// Tooltips
			QL_VisualAttributes.refreshAllTooltips();
			// Check single tooltip when mouse is over attribute
			$('body').on( 'mouseenter', '.va-tooltip .va-picker', function(){
				QL_VisualAttributes.refreshTooltip( $(this) );
			});
			// Update tooltip placement when viewport size changes
			var didResize = false;
			$(window).resize(function() {
				didResize = true;
			});
			setInterval(function() {
				if ( didResize ) {
					didResize = false;
					QL_VisualAttributes.refreshAllTooltips();
				}
			}, 500);
		},

		firstCheck: [],

		updateVisualAttributes: function( $va, variations ) {

			var product_id = $va.closest('form').data('product_id');

			var attribs = [], dismiss = [];
			if ( 'undefined' === typeof this.firstCheck[ product_id ] ) {
				this.firstCheck[ product_id ] = true;
			}

			for ( var num in variations ) {

				if ( variations.hasOwnProperty( num ) && 'undefined' !== typeof variations[num] ) {

					var attributes = variations[num].attributes,
						path = '';
					for ( var attrib in attributes ) {
						if ( attributes.hasOwnProperty( attrib ) ) {
							path = '[data-attribute=' + attrib.replace(/attribute_/, '') + ']';
							if ( '' !== attributes[attrib] ) {
								path += '[data-term=' + attributes[attrib] + ']';
								attribs.push(path);
							} else {
								path += '[data-term]';
								dismiss.push(path);
							}
						}
					}
				}
			}

			$.each( attribs, function( i, v ) { attribs[i] = v.sanitize(); } );
			var visible = attribs.join(','),
				$visible = $va.find('.va-picker' + visible),
				$visibleOptions = $va.find('.va-option' + visible),
				donthide = '',
				donthideOptions = '';
			if ( dismiss.length > 0 ) {
				donthide = '.va-picker' + dismiss.join(',');
				donthideOptions = '.va-option' + dismiss.join(',');
			}

			// Hide attributes that don't match the variation
			if ( this.firstCheck[ product_id ] ) {
				$va.find('.va-picker:not(' + visible + ')').not(donthide).remove();
				this.firstCheck[ product_id ] = false;
			} else {
				$va.find('.va-picker:not(' + visible + ')').not(donthide).addClass('va-hidden');
			}
			
			$visible.removeClass('va-hidden');
			if ( 'reclick' !== qlva.reclick ) {
				$va.find('.va-option:not(' + visible + ')').not(donthideOptions).prop('disabled', true);
				$visibleOptions.prop('disabled', false);
			}

			// Event: after options have changed. Receives the visible options object as argument.
			$('body').trigger( 'ql_visual_attributes_options_updated', [$visible, $visibleOptions] );
		},

		refreshTooltip: function( $picker ) {
			var $tooltip = $picker.find( '.va-info' );
			if ( $tooltip.length > 0 ) {
				var $detect = false,
					tooltipW = $tooltip.outerWidth( true ),
					tooltipH = $tooltip.outerHeight( true ),
					pickerOffset = $picker.offset(),
					pickerLeft = pickerOffset.left,
					pickerTop = pickerOffset.top,
					pickerRight = pickerLeft + $picker.outerWidth(),
					parentDetectH = false,
					parentDetectV = false,
					parentDetectHR = false;

				if ( qlva.tooltipEdgeDetect ) {
					$detect = $(qlva.tooltipEdgeDetect);
				}

				if ( 'object' === typeof $detect ) {
					var detectOffset = $detect.offset();
					parentDetectH = ( pickerLeft - detectOffset.left ) <= tooltipW;
					parentDetectV = ( pickerTop - detectOffset.top ) <= tooltipH;
					parentDetectHR = ( $detect.outerWidth( true ) - pickerRight ) <= tooltipW;
				}

				// Left edge
				if ( parentDetectH || ( ( pickerLeft - $(window).scrollLeft() ) <= tooltipW ) ) {
					$picker.addClass( 'va-tooltip-left' ).removeClass( 'va-tooltip-right' );
				} else {
					$picker.removeClass( 'va-tooltip-left' );
				}

				// Right edge
				if ( parentDetectHR || ( ( $(window).width() - pickerRight ) <= tooltipW ) ) {
					$picker.addClass( 'va-tooltip-right' ).removeClass( 'va-tooltip-left' );
				} else {
					$picker.removeClass( 'va-tooltip-right' );
				}

				// Top edge
				if ( parentDetectV || ( ( pickerTop - $(window).scrollTop() ) <= tooltipH ) ) {
					$picker.addClass( 'va-tooltip-bottom' );
				} else {
					$picker.removeClass( 'va-tooltip-bottom' );
				}
			}
		},

		refreshAllTooltips: function() {
			$('.va-tooltip .va-picker').each(function(){
				QL_VisualAttributes.refreshTooltip( $(this) );
			});
		},

		parseVars: function(){
			$.each( qlva, function(i, v) {
				if ( 'false' === v || 'true' === v ) {
					qlva[i] = 'false' !== v;
				} else if ( typeof v === 'string' && ! v.match(/[a-z]/i) && parseInt(v) ) {
					qlva[i] = parseInt(v);
				} else if ( typeof v === 'string' && ! v.match(/[a-z]/i) && parseFloat(v) ) {
					qlva[i] = parseFloat(v);
				}
			});
		},

		isTouch: function() {
			return 'true' === qlva.isMobile;
		},
	};

	QL_VisualAttributes.parseVars();

	$(document).ready(function() {
		QL_VisualAttributes.ready();
	});

	$(window).load(function() {
		QL_VisualAttributes.load();
	});

}(jQuery));