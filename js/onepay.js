
	function checkLoginState() {
      FB.getLoginStatus(function(response) {
		 
        FbLoginOnePay(response);
      });
    }
	(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.12&appId=211344289625310&autoLogAppEvents=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));
		
	function FbLoginOnePay(response)
	{
			FB.api('/me',{fields: 'name,email'},  function (response) {
		  alert('Welcome, ' + response.name + "!");
		 jQuery('.loader').show();
		  var data = {
			'action': 'onepay_merchant_reg',
			'email': response.email,
			'name':response.name
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			 jQuery('.loader').hide();
			var response=jQuery.parseJSON(response);
			if(response.success==false)
			{
				var type  = typeof response.error ;
				
				if(type=='string'){
					alert(response.error);
				}
				else{
				jQuery.each(response.error, function (i,er){
					alert(er);
				});
				}
			}
			if(response.success==true)
			{
				alert('Registration Completed');
				window.location.href=window.location.href;
			}
			
			
		});
	});
	}
jQuery( document ).ready(function() {
	jQuery('#_opsync').click(function(){
		jQuery('.loader').show();
		  var data = {
			'action': 'onepay_productsync',
			
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			 jQuery('.loader').hide();
			var response=jQuery.parseJSON(response);
			if(response.success==false)
			{
				var type  = typeof response.error ;
				
				if(type=='string'){
					alert(response.error);
				}
				else{
				jQuery.each(response.error, function (i,er){
					alert(er);
				});
				}
			}
			if(response.success==true)
			{
				alert('Registration Completed');
				window.location.href=window.location.href;
			}
			
			
		});
	});
});
