jQuery(document).ready(function(){
	jQuery(".wlog_select_page").on("change",function(){
		var redirect = jQuery(this).next().val("");
	});
	jQuery(".redirect_link").on("focus",function(){
		var redirect = jQuery(this).prev().prop('selectedIndex',0);
	});
	jQuery( "input.redirect_link" ).focusout(function() {
		
		inputval = jQuery( this).val();
		var urlregex = new RegExp(
        "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
    	valid = urlregex.test(inputval);
    	if(!valid){
    		jQuery(this).addClass("error");
    	    alert("Invalid url !! Please enter a valid url.");
		}
		else{
			jQuery(this).removeClass("error");
		}
    });
    jQuery( "#update_settings" ).on("click",function(e){
    	validate = false;
    	jQuery('input.redirect_link').each(function(){
    		inputval = jQuery( this).val();
    		if(inputval){
	    		error  = jQuery(this).hasClass("error");    		
				var urlregex = new RegExp(
		        "^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
		    	valid = urlregex.test(inputval);
	    		if(error || !valid){
	    			jQuery(this).addClass("error");
	    			validate = true;
	    		}
	    	}
    	});
    	if(validate){
    		e.preventDefault();
    		alert("Invalid url !! Please enter a valid url.");
    	}    		
    });
});