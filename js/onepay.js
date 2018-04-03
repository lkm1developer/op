jQuery( document ).ready(function() {
    console.log( "ready!" );
	function checkLoginState() {
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
}
});