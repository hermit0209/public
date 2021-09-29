<?php
/**
 * Settings class for WC Custom Thank You settings
 * @since 1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
   exit;
}

/**
 * WC_Settings Class
 * @since 1.0
 */
class CTYW_Settings {

   public function __construct() {

      //add custom page settings under woocommerce->advanced.
      add_filter('woocommerce_settings_pages', array($this, 'add_custom_wc_setting'), 10, 1);
      
      //shortcodes
      add_shortcode('ctyw_order_review', array($this, 'ctyw_order_review_shortcode'));
      add_shortcode('ctyw_socialbox', array($this, 'ctyw_socialbox_shortcode'));

      //display custom thank you page content.
      add_filter('the_content', array($this, 'custom_page_content'));

      //redirects the template 
      add_action('woocommerce_thankyou', array($this, 'redirect_custom_page_after_checkout'));

      //Returns true when viewing the order received page.
      add_filter('woocommerce_is_order_received_page', array($this, 'custom_wc_order_received_page'));

      //add custom page thank you setting in single product.
      add_action('woocommerce_product_options_general_product_data', array($this, 'add_wc_custom_thanks_redirect_settings'));

      //save the custom page setting in the simgale product.
      add_action('woocommerce_process_product_meta', array($this, 'wc_custom_thanks_redirect_save_settings'));

      add_action("admin_notices", array($this, "admin_notice"));
      add_action("wp_ajax_ctyw_notice_dismiss", array($this, "dismiss_notice"));      
   }

   /**
    * Add the thank you page dropdown in Settings > Advanced
    * @return array
    * @since 1.0
    */
   public function add_custom_wc_setting($settings) {
      $settings[] = array(
         'title' => esc_html__('Custom Thank You', 'ctyw'),
         'type' => 'title',
         'desc' => esc_html__('This option allows you to define a specific custom thank you page for your customers.', 'ctyw'),
         'id' => 'ctyw_options');

      $settings[] = array(
         'title' => esc_html__('Select the Thank You Page', 'ctyw'),
         'id' => 'ctyw_page_id',
         'desc' => esc_html__('Select the page which you want to use as Custom Thank You Page', 'ctyw'),
         'type' => 'single_select_page',
         'default' => '',
         'class' => 'wc-enhanced-select-nostd',
         'css' => 'min-width:300px;',
         'desc_tip' => true,
      );

      $settings[] = array(
         'title' => __('Enable Social Box', 'ctyw'),
         'desc' => __('Check this option to show the Facebook share tab.', 'ctyw'),
         'id' => 'ctyw_enable_fb_social_box',
         'default' => 'yes',
         'type' => 'checkbox',
         'checkboxgroup' => 'start'
      );

      $settings[] = array(
         'desc' => __('Check this option to show the Twitter share tab.', 'ctyw'),
         'id' => 'ctyw_enable_twitter_social_box',
         'default' => 'yes',
         'type' => 'checkbox',
         'checkboxgroup' => '',
         'show_if_checked' => 'yes',
         'autoload' => false,
      );

      $settings[] = array(
         'desc' => __('Check this option to show the Pinterest share tab.', 'ctyw'),
         'id' => 'ctyw_enable_pinterest_social_box',
         'default' => 'yes',
         'type' => 'checkbox',
         'checkboxgroup' => 'end',
         'show_if_checked' => 'yes',
         'autoload' => false,
      );

      $settings[] = array('type' => 'sectionend', 'id' => 'ctyw_options');

      return $settings;
   }
   
   /**
    * Shortcode content for displaying woocommerce order details
    * @return html
    * @since 1.0
    */
   public function ctyw_order_review_shortcode() {
      if ( isset( $_GET['key'] ) || isset( $_GET['order'] ) ) {
         $order = false;
         $order_id = absint( apply_filters( 'woocommerce_thankyou_order_id', absint( $_GET['order'] ) ) );
         $order_key = wc_clean( apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] )  ) );

         if ( $order_id > 0 ) {
            $order = wc_get_order( $order_id );
            if ( $order->get_order_key() !== $order_key ) {
               $order = false;
            }
         }

         if ( false === $order || $order->get_id() !== $order_id || $order->get_order_key() !== $order_key ) {
            return '<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">' . apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'ctyw' ), null ) . '</p>';
         }
         //Process an order that does require payment.
         unset( WC()->session->order_awaiting_payment );
         // clears the cart when called.
         wc_empty_cart();

         ob_start();
         ?>
         <div class="ctyw_post_content">
         <?php   
         wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
         ?>
         </div>   
         <?php
         $value = ob_get_contents();
         ob_end_clean();
         return $value;
      }
   }
   
   /**
    * Shortcode content for displaying social boxes
    * @return html
    * @since 1.0
    */
   public function ctyw_socialbox_shortcode() {
      $is_facebook_share_enable = get_option('ctyw_enable_fb_social_box');
      $is_twiter_share_enable = get_option('ctyw_enable_twitter_social_box');
      $is_pintirest_share_enable = get_option('ctyw_enable_pinterest_social_box');
      
      $order = false;
      
      if ( isset( $_GET['key'] ) || isset( $_GET['order'] ) ) {         
         $order_id = absint( apply_filters( 'woocommerce_thankyou_order_id', absint( $_GET['order'] ) ) );
         $order_key = wc_clean( apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( $_GET['key'] )  ) );

         if ( $order_id > 0 ) {
            $order = wc_get_order( $order_id );
            if ( $order->get_order_key() !== $order_key ) {
               $order = false;
            }
         }
      }
      
      if( $order === false ) {
         return "";
      }
      
      ob_start();

      /* socials tabs */
   ?>
      <div id = "ctyw-social-box" class="ctyw-social-tabs">
         <div class="ctyw-social-tabs-nav">
            <a href="#" class="ctyw-social-tabs-nav__link is-active" style="display:flex;align-items: center;">
               <img alt="email" src="<?php echo CTYW_URL ?>/assets/images/mail.svg">
               <span class = "icon_title"><?php _e('Share in Email', 'ctyw'); ?></span>
            </a>
            <?php if ($is_facebook_share_enable == 'yes') { ?>
               <a href="#" class="ctyw-social-tabs-nav__link"style="display:flex;align-items: center;">
               <img alt="facebook" src="<?php echo CTYW_URL ?>/assets/images/facebook.svg">
                  <span class = "icon_title"><?php _e('Share On Facebook', 'ctyw'); ?></span>
               </a>
               <?php
            }
            if ($is_twiter_share_enable == 'yes') {
               ?>
               <a href="#" class="ctyw-social-tabs-nav__link"style="display:flex;align-items: center;">
               <img alt="twitter" src="<?php echo CTYW_URL ?>/assets/images/twitter.svg">
                  <span class = "icon_title"><?php _e('Tweet This Purchase', 'ctyw'); ?></span>
               </a>
               <?php
            }
            if ($is_pintirest_share_enable == 'yes') {
               ?>
               <a href="#" class="ctyw-social-tabs-nav__link"style="display:flex;align-items: center;">
               <img alt="pinterest" src="<?php echo CTYW_URL ?>/assets/images/pinterest.svg">
                  <span class = "icon_title"><?php _e('Pin This Purchase', 'ctyw'); ?></span>
               </a>
               <?php
            }
            ?>
         </div>

         <div class="ctyw-social-tab is-active">
            <div class="ctyw-social-tabs__content">
               <div id="ctyw-social-slider" class="ctyw_email">
                  <?php
                  //print the header only when there is more than one product.
                  if (count($order->get_items()) > 1) :
                     ?>
                     <div class="ctyw-social_nav_container">
                        <p class="ctyw-social_navigation ctyw-slider_prev"><img src="<?php echo CTYW_URL . 'assets/images/prev.png'; ?>" /></p>
                        <p class="ctyw-social_navigation_message"><?php _e('Click either the left or right arrows to select the purchased item you want to share', 'ctyw'); ?></p>
                        <p class="ctyw-social_navigation ctyw-slider_next"><img src="<?php echo CTYW_URL . 'assets/images/next.png'; ?>" /></p>
                     </div>
                     <?php
                  endif;

                  //slider for each product
                  foreach ($order->get_items() as $item) {
                     //var_dump($item);
                    // $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
                     $_product = $item->get_product();

                     //var_dump($_product);
                     ?>
                     <div class="ctyw-social-slider_container">
                        <div id="ctyw-tab_sharing_product">
                           <div class="ctyw-tab_sharing_product_thumb">
                              <?php echo $_product->get_image(); ?>
                           </div>
                           <div id="ctyw_email_p_id_<?php echo $_product->get_id(); ?>" class="ctyw-tab_sharing_product_info">
                              <?php
                              //getting the image url
                              if (has_post_thumbnail($_product->get_id())) {
                                 $att_id[0] = get_post_thumbnail_id($_product->get_id());
                                 $att_url = wp_get_attachment_image_src($att_id[0], 'full');
                              } else {
                                 $att_url[0] = wc_placeholder_img_src();
                              }

                              $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                              ?>
                              <input class="ctyw_image_field" type="hidden" value="<?php echo rawurlencode($att_url[0]) ?>" />
                              <input class="ctyw_url_field" type="hidden" value="<?php echo $p_url; ?>" />

                              <input class="ctyw_sharer_field" type="hidden" value=mailto:?subject=<?php echo rawurlencode(html_entity_decode(the_title_attribute([ 'echo' => false]), ENT_COMPAT, 'UTF-8')); ?>&body=ctyw_url%0A%20ctyw_title%0A%20ctyw_description" target="_blank">

                              <input class="ctyw_title_field" ctyw_default_title="<?php echo apply_filters('ctyw_just_purchased_string', __('I\'ve just purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" type="text" value="<?php echo apply_filters('yctpw_just_purchased_string', __('I have purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" />
                              <?php
                              $description = '';
                              if ($_product instanceof WC_Product_Variation) {
                                 $tp = get_post($_product->get_parent_id());
                                 $post_content = $tp->post_content;
                                 $description = substr($post_content, 0, 300);
                              } else {
                                 $description = substr($_product->get_description(), 0, 300);
                              }
                              $description = substr(strip_tags(strip_shortcodes($description)), 0, 250);
                              ?>
                              <textarea class="ctyw_excerpt" ctyw_default_description="<?php echo $description . '...'; ?>"><?php echo $description . '...'; ?></textarea>
                           </div>
                           <div class="ctyw_share_it"><a  href="javascript:void(0);" onclick="ctyw_socialize('ctyw_email_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Share', 'ctyw'); ?></a></div>
                           <div style="clear:both"></div>
                        </div>
                        <div style="clear:both"></div>
                     </div>
                     <?php
                  }//end for
                  ?>
               </div> <?php // end slider  ?>
            </div>
         </div>
         <?php
         /* Social Box FACEBOOK TAB */
         if ($is_facebook_share_enable == 'yes') :
            ?>
            <div class="ctyw-social-tab">
               <div class="ctyw-social-tabs__content">
                  <div id="ctyw-social-slider" class="ctyw_facebook">
         <?php
         //print the header only when there is more than one product.
         if (count($order->get_items()) > 1) :
            ?>
                        <div class="ctyw-social_nav_container">
                           <p class="ctyw-social_navigation ctyw-slider_prev"><img src="<?php echo CTYW_URL . 'assets/images/prev.png'; ?>" /></p>
                           <p class="ctyw-social_navigation_message"><?php _e('Click either the left or right arrows to select the purchased item you want to share', 'ctyw'); ?></p>
                           <p class="ctyw-social_navigation ctyw-slider_next"><img src="<?php echo CTYW_URL . 'assets/images/next.png'; ?>" /></p>
                        </div>
                        <?php
                     endif;

                     //slider for each product
                     foreach ($order->get_items() as $item) {
                       // $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
                        $_product =$item->get_product();
                        ?>
                        <div class="ctyw-social-slider_container">
                           <div id="ctyw-tab_sharing_product">
                              <div class="ctyw-tab_sharing_product_thumb">
                                 <?php echo $_product->get_image(); ?>
                              </div>
                              <div id="ctyw_facebook_p_id_<?php echo $_product->get_id(); ?>" class="ctyw-tab_sharing_product_info">
                                 <?php
                                 //getting the image url
                                 if (has_post_thumbnail($_product->get_id())) {
                                    $att_id[0] = get_post_thumbnail_id($_product->get_id());
                                    $att_url = wp_get_attachment_image_src($att_id[0], 'full');
                                 } else {
                                    $att_url[0] = wc_placeholder_img_src();
                                 }

                                 $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                 ?>
                                 <input class="ctyw_image_field" type="hidden" value="<?php echo rawurlencode($att_url[0]) ?>" />
                                 <input class="ctyw_url_field" type="hidden" value="<?php echo $p_url; ?>" />
                                 <input class="ctyw_sharer_field" type="hidden" value="https://www.facebook.com/sharer/sharer.php?u=ctyw_url&picture=ctyw_img&title=ctyw_title&description=ctyw_description" />
                                 <input class="ctyw_title_field" ctyw_default_title="<?php echo apply_filters('ctyw_just_purchased_string', __('I\'ve just purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" type="text" value="<?php echo apply_filters('yctpw_just_purchased_string', __('I have purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" />
                                 <?php
                                 $description = '';
                                 if ($_product instanceof WC_Product_Variation) {
                                    $tp = get_post($_product->get_parent_id());
                                    $post_content = $tp->post_content;
                                    $description = substr($post_content, 0, 300);
                                 } else {
                                    $description = substr($_product->get_description(), 0, 300);
                                 }
                                 $description = substr(strip_tags(strip_shortcodes($description)), 0, 250);
                                 ?>
                                 <textarea class="ctyw_excerpt" ctyw_default_description="<?php echo $description . '...'; ?>"><?php echo $description . '...'; ?></textarea>
                              </div>
                              <div class="ctyw_share_it"><a  href="javascript:void(0);" onclick="ctyw_socialize('ctyw_facebook_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Share', 'ctyw'); ?></a></div>
                              <div style="clear:both"></div>
                           </div>
                           <div style="clear:both"></div>
                        </div>
            <?php
         }//end for
         ?>
                  </div> <?php // end slider  ?>
               </div>
            </div>
         <?php
      endif;
      /* Social Box TWITTER TAB */
      if ($is_twiter_share_enable == 'yes') :
         ?>
            <div class="ctyw-social-tab">
               <div class="ctyw-social-tabs__content">
                  <div id="ctyw-social-slider" class="ctyw_twitter">
         <?php
         ///print the header only when there is more than one product.
         if (count($order->get_items()) > 1) :
            ?>
                        <div class="ctyw-social_nav_container">
                           <p class="ctyw-social_navigation ctyw-slider_prev"><img
                                 src="<?php echo CTYW_URL . 'assets/images/prev.png'; ?>"/>
                           </p>
                           <p class="ctyw-social_navigation_message"><?php _e('Click either the left or right arrows to select the purchased item you want to share', 'ctyw'); ?></p>
                           <p class="ctyw-social_navigation ctyw-slider_next"><img
                                 src="<?php echo CTYW_URL . 'assets/images/next.png'; ?>"/>
                           </p>
                        </div>
            <?php
         endif;
         // slider for each product
         foreach ($order->get_items() as $item) {
            //$_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
            $_product = $item->get_product();
            ?>
                        <div class="ctyw-social-slider_container">
                           <div id="ctyw-tab_sharing_product">
                              <div class="ctyw-tab_sharing_product_thumb">
                                 <?php echo $_product->get_image(); ?>
                              </div>
                              <div id="ctyw_twitter_p_id_<?php echo $_product->get_id(); ?>" class="ctyw-tab_sharing_product_info">
                                 <?php
                                 //getting the image url
                                 if (has_post_thumbnail($_product->get_id())) {
                                    $att_id[0] = get_post_thumbnail_id($_product->get_id());
                                    $att_url = wp_get_attachment_image_src($att_id[0], 'full');
                                 } else {
                                    $att_url[0] = wc_placeholder_img_src();
                                 }
                                 $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                 ?>
                                 <input class="ctyw_image_field" type="hidden" value="" />
                                 <input class="ctyw_url_field" type="hidden" value="<?php echo $p_url; ?>" />
                                 <input class="ctyw_sharer_field" type="hidden" value="https://twitter.com/share?url=ctyw_url&text=ctyw_description" />
                                 <input class="ctyw_title_field" ctyw_default_title="<?php echo apply_filters('yctpw_just_purchased_string', __('I\'ve just purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" type="text" value="<?php echo apply_filters('yctpw_just_purchased_string', __('I have purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" />
                                 <?php
                                 $description = '';
                                 if ($_product instanceof WC_Product_Variation) {
                                    $tp = get_post($_product->get_parent_id());
                                    $post_content = $tp->post_content;
                                    $description = substr($post_content, 0, 300);
                                 } else {
                                    $description = substr($_product->get_description(), 0, 300);
                                 }
                                 $description = substr(strip_tags(strip_shortcodes($description)), 0, 250);
                                 ?>
                                 <textarea class="ctyw_excerpt" ctyw_default_description="<?php echo $description . '...'; ?>"><?php echo $description . '...'; ?></textarea>
                                 <p id="twit_c_counter" style="display: none;"><?php _e('characters left', 'ctyw'); ?> <span></span></p>
                              </div>
                              <div class="ctyw_share_it"><a  href="javascript:void(0);" onclick="ctyw_socialize('ctyw_twitter_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Tweet', 'ctyw'); ?></a></div>
                              <div style="clear:both"></div>
                           </div>
                           <div style="clear:both"></div>
                        </div>
               <?php
            }//end for
            ?>
                  </div><?php //end slider     ?>
               </div>
            </div>
                     <?php
                  endif;
                  /*  Social Box PINTEREST  TAB */
                  if ($is_pintirest_share_enable == 'yes') :
                     ?>
            <div class="ctyw-social-tab">
               <div class="ctyw-social-tabs__content">
                  <div id="ctyw-social-slider" class="ctyw_pinterest">
                     <?php
                     //print the header only when there is more than one product.
                     if (count($order->get_items()) > 1) :
                        ?>
                        <div class="ctyw-social_nav_container">
                           <p class="ctyw-social_navigation ctyw-slider_prev"><img src="<?php echo CTYW_URL . 'assets/images/prev.png'; ?>" /></p>
                           <p class="ctyw-social_navigation_message"><?php _e('Click either the left or right arrows to select the purchased item you want to share', 'ctyw'); ?></p>
                           <p class="ctyw-social_navigation ctyw-slider_next"><img src="<?php echo CTYW_URL . 'assets/images/next.png'; ?>" /></p>
                        </div>
            <?php
         endif;
         //slider for each product
         foreach ($order->get_items() as $item) {
          //  $_product = apply_filters('woocommerce_order_item_product', $order->get_product_from_item($item), $item);
            $_product = $item->get_product();
            ?>
                        <div class="ctyw-social-slider_container">
                           <div id="ctyw-tab_sharing_product">
                              <div class="ctyw-tab_sharing_product_thumb">
                                 <?php echo $_product->get_image(); ?>
                              </div>
                              <div id="ctyw_pinterest_p_id_<?php echo $_product->get_id(); ?>" class="ctyw-tab_sharing_product_info">
                                 <?php
                                 if (has_post_thumbnail($_product->get_id())) {
                                    $att_id[0] = get_post_thumbnail_id($_product->get_id());
                                    $att_url = wp_get_attachment_image_src($att_id[0], 'full');
                                 } else {
                                    $att_url[0] = wc_placeholder_img_src();
                                 }
                                 $p_url = rawurlencode(apply_filters('yith_ctwp_social_url', $_product->get_permalink()));
                                 ?>
                                 <input type="hidden" value="<?php echo rawurlencode($att_url[0]) ?>" />
                                 <input type="hidden" value="<?php echo $p_url ?>" />
                                 <input type="hidden" value="http://pinterest.com/pin/create/button/?url=ctyw_url&media=ctyw_img&description=ctyw_title - ctyw_description" />
                                 <input class="ctyw_title_field" ctyw_default_title="<?php echo apply_filters('yctpw_just_purchased_string', __('I\'ve just purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" type="text" value="<?php echo apply_filters('yctpw_just_purchased_string', __('I have purchased: ', 'ctyw') . '\'' . $_product->get_title() . '\'', $_product); ?>" />
                                 <?php
                                 $description = '';
                                 if ($_product instanceof WC_Product_Variation) {
                                    $tp = get_post($_product->get_parent_id());
                                    $post_content = $tp->post_content;
                                    $description = substr($post_content, 0, 300);
                                 } else {
                                    $description = substr($_product->get_description(), 0, 300);
                                 }
                                 $description = substr(strip_tags(strip_shortcodes($description)), 0, 250);
                                 ?>
                                 <textarea class="ctyw_excerpt" ctpw_default_description="<?php echo $description . '...'; ?>"><?php echo $description . '...'; ?></textarea>
                              </div>
                              <div class="ctyw_share_it"><a  href="javascript:void(0);" onclick="ctyw_socialize('ctyw_pinterest_p_id_<?php echo $_product->get_id(); ?>')"><?php _e('Pin it', 'ctyw'); ?></a></div>
                              <div style="clear:both"></div>
                           </div>
                           <div style="clear:both"></div>
                        </div>
            <?php
         }//end for
         ?>
                  </div> <?php // end slider     ?>
               </div>
            </div>
         <?php
      endif;
      ?>
      </div>
   <?php
      $value = ob_get_contents();
      ob_end_clean();
      return $value;
   }

   /**
    * Display the custom thank you page content
    * @param  string $content the content of the custom thank you page.
    * @since 1.0
    */
   public function custom_page_content($content) {
      // Check if the order ID exists.
      if (empty($_GET['key']) || empty($_GET['order'])) {
         return $content;
      }
      
      if( $content === "" ) {
         $content .= do_shortcode( '[ctyw_order_review]' );
         $content .= do_shortcode( '[ctyw_socialbox]' );
      }
      return $content;
   }

   /**
    * redirect the selected page after checkout
    * @return array
    * @since 1.0
    */
   public function redirect_custom_page_after_checkout($order) {
      global $wp;

      //setting starting pages
      $global_url = '';

      // get global setting URL
      $global_page_id = get_option('ctyw_page_id');
      if ( $global_page_id ) {
         $global_url = get_permalink($global_page_id);
      }

      if (is_checkout() && (!empty($wp->query_vars['order-received']) )) {

         $order_id = absint($wp->query_vars['order-received']);
         $order = wc_get_order($order_id);
         $items = $order->get_items();

         $count_items = count($items);

         // If only single item in the cart then redirect to per product set URL
         if ($count_items == 1) {
            // TODO
            $product_id = 0;
            foreach ($items as $item) {
               $product_id = $item->get_product_id();
            }
            $per_product_url = get_post_meta($product_id, 'wc_custom_thanks_redirect', true);
            if (!empty($per_product_url)) {
               $redirect_url = $per_product_url;
            } else {
               // redirect to global set URL
               $redirect_url = $global_url;
            }
         } else {
            // redirect to global set URL
            $redirect_url = $global_url;
         }

         // If redirect url is set then only redirect
         if ($redirect_url !== "") {
            $order_id = absint($wp->query_vars['order-received']);
            $order_key = wc_clean($_GET['key']);
            $redirect = add_query_arg(array(
               'order' => $order_id,
               'key' => $order_key,
                    ), $redirect_url);

            wp_safe_redirect($redirect);
         }
      }
   }

   /**
    * Checks if the page shows is the Custom Thank you page or not and returns true in case.
    * @param  bool $is_order_received_page Original value from the filter.
    * @since 1.0
    */
   public function custom_wc_order_received_page($is_order_received_page) {
      $page_id = get_option('ctyw_page_id');
      if (is_page($page_id)) {
         return true;
      }
      return $is_order_received_page;
   }

   /**
    * add custom page setting under edit product->general.
    * @since 1.0
    */
   public function add_wc_custom_thanks_redirect_settings() {
      global $woocommerce, $post;
      echo '<div class="options_group">';
      woocommerce_wp_text_input(
              array(
                 'id' => 'wc_custom_thanks_redirect',
                 'label' => __('Thank You URL:', 'ctyw'),
                 'placeholder' => '',
                 'desc_tip' => 'true',
                 'description' => __('Enter Valid URL for Custom Thank You Page.', 'ctyw'),
                 'type' => 'text'
              )
      );
      echo '</div>';
   }

   /**
    * save the value of Custom Page Thank You in the single product.
    * @since 1.0
    */
   public function wc_custom_thanks_redirect_save_settings() {
      // save custom fields
      global $post;

      if ($post->post_type == 'product' && !empty($_POST['woocommerce_meta_nonce']) && wp_verify_nonce($_POST['woocommerce_meta_nonce'], 'woocommerce_save_data')) {
         $field_name = 'wc_custom_thanks_redirect';

         if (!empty($_POST[$field_name])) {
            $code = sanitize_text_field($_POST[$field_name]);
            if (!add_post_meta($post->ID, $field_name, $code, true))
               update_post_meta($post->ID, $field_name, $code);
         } else {
            delete_post_meta($post->ID, $field_name);
         }
      }
      return;
   }

   public function admin_notice() {
      $last_dismissed_notice = get_option("ctyw_notice_dismiss");

      if ( $last_dismissed_notice && current_time('timestamp') >= strtotime($last_dismissed_notice)) {
         $ajax_nonce = wp_create_nonce( "ctyw_ajax_nonce" );
         $notice_text = '<div class="notice notice-info is-dismissible" id="custom_thankyou_woo_notice">';
         $notice_text .= '<p>' . __( 'How do you like <strong>Custom Thank You For WooCommerce</strong>? Your feedback assures the continued maintenance of this plugin!', 'ctyw' ) . '&nbsp;<a class="button button-primary" href="' . esc_url('http://wordpress.org/plugins/custom-thank-you-for-woocommerce').'" target="_blank">' . esc_html__('Leave Feedback','ctyw') . '</a></p>';
         $notice_text .= '<input type="hidden" name="ctyw_ajax_nonce" id="ctyw_ajax_nonce" value="' . $ajax_nonce . '" />';
         $notice_text .= '</div>';
         
         echo $notice_text;
      }
   }

   public function dismiss_notice() {
      // check nonce
      check_ajax_referer( 'ctyw_ajax_nonce', 'security' );
      // sanitize incoming data
      $decision = intval( $_POST["dismissed_final"] );
      if ( $decision == 1 ) {
         update_option("ctyw_notice_dismiss", null);
      } else {
         update_option("ctyw_notice_dismiss", date('Y-m-d', strtotime('+30 days')));
      }

      wp_send_json(array("status" => true));
   }

}

$ctyw_settings = new CTYW_Settings();