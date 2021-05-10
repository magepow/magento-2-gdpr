/*
* @Author: nguyen
* @Date:   2021-05-10 15:06:02
* @Last Modified by:   Alex Dong
* @Last Modified time: 2021-05-10 15:17:54
*/
define([
    'jquery',
    'jquery/jquery.cookie',
    'Magento_Ui/js/modal/modal'
    ], function($, cookie, modal){
    "use strict";

	var gdprPopupContainer = $('#magepow-gdpr-popup-container');
	var popupContent = gdprPopupContainer.html();
	var _gdprModal = gdprPopupContainer.find('#magepow-gdpr-popup');
	$("#privacyLink").click(function(event) {
	  	event.preventDefault();
	  	if(_gdprModal.length) {
	  		var url = _gdprModal.data('url');
            $.ajax({
                url:url,
                type:'POST',
                showLoader: true,
                cache:false,   
                success:function(data){
                	_gdprModal.html(data);
		            modal({
		                type: 'popup',
		                modalClass: 'modals-gdpr-popup',
		                responsive: true, 
						innerScroll: true,
						buttons: false,
		                closed: function(){
		                	$('.modals-gdpr-popup').remove();
		                	gdprPopupContainer.html(popupContent);
		                }                       	
					}, _gdprModal);
					var body = $('body');
					_gdprModal.modal('openModal');
					body.addClass('open-gdpr-popup');
					_gdprModal.trigger('contentUpdated');
					_gdprModal.on('modalclosed', function(){body.removeClass('open-gdpr-popup');});
                }
            });

        }
	});
	if ($.cookie('user_allowed_save_cookie') === null || $.cookie('user_allowed_save_cookie') === "" || $.cookie('user_allowed_save_cookie') === "null" || $.cookie('user_allowed_save_cookie') === undefined){
		$('body').addClass('cookie-message')
	}
	$('#btn-cookie-allow').click(function(){
		$('body').removeClass('cookie-message')
	})
    $('.cookie-close').on('click', function(event) {
        event.preventDefault();
        $('.magepow-gdpr-cookie-notice').addClass('disable');
    });
});