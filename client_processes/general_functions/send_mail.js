// Contact page functions

$(function()
{
	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' textarea ');
	
	inputFields.each(function()
	{		
		$(this).focus(function() 						// On focus
		{
			if($(this).val() == langPack[this.id]) 		// If current value is the default one
			{											
				$(this).val("");						// Sets it to blank
			}
		});
		
		$(this).focusout(function() 					// On focus out
		{
			if($(this).val() == "") 					// Reverses the above action
			{
				$(this).val(langPack[this.id]);
			}
		});			
	});
	
    $('#sendMailBtn').click(function()					// On send mail button
	{
        //remove classes	
        $('#myform2 input').removeClass('error').removeClass('valid');

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;	// E-mail pattern will be used to check mail validity
        var fields = $('#contact_form input[type=text]');
        var error = 0;
       
	   fields.each(function()
		{
           var value = $(this).val();
		   var field_id = $(this).attr('id');
		   
           if((field_id =='name' && value == langPack['name']) || 				// Ifone or more fields have the default value
			  (field_id =='email' && value == "* E-mail") ||
			  (field_id =='sendMailBtn' && value == langPack['message'])) 
			{
 				apply_error_events(this, 'formError5');  						// Error classes messages and animations             
				error++;			// ( msg: This field is mandatory )
            }
			else if(( field_id =='email' && !emailPattern.test(value) ) ) 		// If e-mail not in the correct pattern
			{
				apply_error_events(this, 'formError6'); 						// Error classes messages and animations   				
                error++;			// ( msg: Invalid E-mail value )
            } 
			else {
                $(this).addClass('valid');										// If all tests passed, sets class valid
				$("label[for='"+field_id +"']").fadeTo('slow', 0);
            }
        });

		if($('#text').val() == langPack['message']) 
		{
			apply_error_events(this, 'formError5');      						// Error classes messages and animations      
            error++;				// ( msg: This field is mandatory )	
		}
		
        if(!error)					// Forwards data to the server to be sent
		{
			$('#send_msg').html("<img id='loading_img' src='styles/images/loading2.gif' />")
			$('#send_msg').fadeIn('fast');
			$.ajax(
			{
				type: "POST",
				url: "server_processes/email_functions/general_mail.php",
				data:{
					name : $('#name').val(),
					email : $('#email').val(),
					text : $('#message').val()
					},
				success: function(response)
				{
					if(response == 'EXPIRED')			// If session is expired
					{
						session_expired();				// Calls function to alert and logout
					}
					else if(response == 'OK')			// If mail is sent (response is ok)
					{
						$('#send_msg').css('color', '#7f7e7e');		// Succession message
						$('#send_msg').html(langPack['sendSucceed']);
						$("#send_msg").removeAttr('data-inter');
						$('#send_msg').attr( 'data-inter', 'sendSucceed' );
					}
					else{								// If failed
						$('#send_msg').css('color', '#ff0000');		// Error messageS
						$('#send_msg').html(langPack['ERROR']);												
						$("#send_msg").removeAttr('data-inter');
						$('#send_msg').attr( 'data-inter', 'ERROR');
					}
				}
			});				
        } 
    });	
});