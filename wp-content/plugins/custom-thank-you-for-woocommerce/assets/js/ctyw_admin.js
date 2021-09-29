jQuery(document).ready( function() {
   jQuery(document).on("click", "#custom_thankyou_woo_notice .notice-dismiss", function () {
      var data = {
         action: 'ctyw_notice_dismiss',
         dismissed_final: 0,
         security: jQuery('#ctyw_ajax_nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
      });
   });

   jQuery(document).on("click", "#custom_thankyou_woo_notice a", function () {
      var data = {
         action: 'ctyw_notice_dismiss',
         dismissed_final: 1,
         security: jQuery('#ctyw_ajax_nonce').val()
      };

      jQuery.post(ajaxurl, data, function (response) {
         jQuery("#custom_thankyou_woo_notice").hide();
      });
   });   
}); 