jQuery(document).ready(function(){
	if(jQuery("#container_overlay").length == 0){
		var html = '<div id="container_overlay"></div>';
		html += '<div id="container">';
		html += '<div id="wlog_notice">Enter below</div>';
		html += '<div>';
		html += '<input name="wlogin_username" id="wlogin_username" type="text" placeholder="Enter username"/>';
		html += '<input name="wlogin_password" id="wlogin_password" type="text" placeholder="Enter Password"/>'
		html += '</div>';
		html += '<div class="login_wp"><a class="button" onclick="WLOG_login_user()">Log In</a></div>';
		html += '</div>';
		jQuery("body").append(html);
	}
});

function WLOG_loggingin_user(){
	jQuery("#container_overlay").show();
	jQuery("#container").show();
}

function WLOG_login_user(){
	var username = jQuery("#wlogin_username").val();
	var password = jQuery("#wlogin_password").val();
	if(!username || !password){
		message = "Enter username and password";
		jQuery("#wlog_notice").html(message);
		jQuery("#wlog_notice").css('color','red');
		if(!username)
			jQuery("#wlogin_username").addClass('error');
		if(!password)	
			jQuery("#wlogin_password").addClass('error');
	}
	else{
		data = {
				action : 'wlog_log_user',
				username : username,
				password : password,
				security : security
		};
		jQuery.post(ajax_url,data,function(response){
			response = jQuery.trim(response);
			if(response.indexOf("invalid_username") >= 0){
				message = "Invalid username or password";
				jQuery("#wlog_notice").html(message);
			}

			else if(response.indexOf("invalid") >= 0 ){
				message = "Invalid";
				jQuery("#wlog_notice").html(message);
			}
			
			else if(response.indexOf("already_loggedin") >= 0){
				message = "You are already loggedin ..";
				jQuery("#wlog_notice").html(message);
				jQuery("#container_overlay").hide();
				jQuery("#container").hide();
			}
			else if(response.indexOf("success") >= 0){
				message = "Authenticated.....Logging In";
				jQuery("#wlog_notice").html(message);
				window.location.reload();
			}
			else if(response.indexOf("http") >= 0){
				message = "Authenticated.....Logging In";
				jQuery("#wlog_notice").html(message);
				window.location.href=response;
			}
		});
	}
}