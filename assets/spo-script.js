jQuery.noConflict();
jQuery(document).ready(function($) {

    $('.spo-offer').one('inview', function(event, isInView) {

        if (isInView) {
            /** получаем города для select */
            var offerDiv = $('.spo-offer');
            var offerID = offerDiv.data('offer-id');
            var data = {
                action: "load_sp_offer",
                nonce_code: spo_ajax.nonce,
                offer_id: offerID
            };
            $.post(spo_ajax.url, data, function(response) {
                offerDiv.html(response);
            });
        }

    });

});