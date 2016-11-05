$(function()
{
	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' input[type=password]');
	
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
	
    $('form').submit(function(){ return false; });
	
	$('#loginBtn').click(function()
	{
        //remove classes
        $('#myform input').removeClass('error').removeClass('valid');		// Removes submit error class and adds valid class

        //check if inputs aren't empty
        var fields = $('#myform input[type=text], #myform input[type=password]');
        var error = 0;
		
        fields.each(function()
		{
            var value = $(this).val();
			var field_id = $(this).attr('id')
			
            if ( field_id == 'username' && value.length < 3) 					// If username is < 3 characters				
			{
				apply_error_events(this,'formError1');							// Error classes messages and animations                
				error++;				
			} 
			else if ( field_id == 'username' && value == langPack['username'])	// If username has the default value
			{
				$(this).addClass('error');										// Error classes messages and animations
				apply_error_events(this,'formError2');                
				error++;				
			} 
			else if ( field_id == 'password' && value.length < 5) 				// If password is < 5 characters
			{				
				apply_error_events(this,'formError3');							// Error classes messages and animations
				error++;					
			} 
			else if ( field_id == 'password' && value == langPack['password'])	// If password 	has the default value
			{
				apply_error_events(this,'formError4');							// Error classes messages and animations
                error++;				
            } 
			else {
                $(this).addClass('valid');
				$("label[for='"+field_id+"']").fadeTo('slow', 0);

            }		
        });    
		   
        if(!error) 											// If no error occurs
		{
			var general;
			$("#login_loading").ajaxStart(function () 
			{
				$('#login_msg').fadeOut(500);			// Removes all error messages
				$('#login_error').fadeOut(500);
				$(this).fadeIn(500);
			});

			$("#login_loading").ajaxStop(function () 
			{
				$(this).fadeOut(500);
			});		
				
			$.ajax(
			{					
				type: "POST",
				url: "server_processes/system_access_functions/login_validation.php",
				async: false,
				data:{
					  username: $('#username').val(),
					  password: $('#password').val()  
					 },
				success: function(response)				// Used only for the UI, security is added in the main files
				{ 
					if ( response == "is_patient")			// If user gains patient rights, loads the corresponding content
					{
						$('.content').load('content/patient/content.php');
						$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
						$('#banner_container_patient').load('content/patient/banner.php').css('display', 'inline-block').hide();
						$('#banner_container_patient').fadeIn('slow');
						$('#header').fadeOut('slow', function(){$('#header').html("");});
						$('#header_patient').load('content/patient/menu.php').css('display', 'inline-block').hide();
						$('#header_patient').fadeIn('slow', general = function(){$.getScript("client_processes/general_functions/pages_handler.js");});
					}
					else if (response == "is_doctor")		// If user gains doctor rights, loads the corresponding content
					{							
						$('.content').load('content/doctor/content.php');
						$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
						$('#banner_container_staff').load('content/doctor/banner.php').css('display', 'inline-block').hide();
						$('#banner_container_staff').fadeIn('slow');							
						$('#header').fadeOut('slow', function(){$('#header').html("");});
						$('#header_staff').load('content/doctor/menu.php').css('display', 'inline-block').hide();
						$('#header_staff').fadeIn('slow', function(){$.getScript("client_processes/general_functions/pages_handler.js");});
					}
					else if (response == "is_manager")		// If user manager patient rights, load the corresponding content
					{
						$('.content').load('content/manager/content.php');
						$('#banner_container').fadeOut('slow', function(){$('#banner_container').html("");});
						$('#banner_container_manager').load('content/manager/banner.php').css('display', 'inline-block').hide();
						$('#banner_container_manager').fadeIn('slow');							
						$('#header').fadeOut('slow', function(){$('#header').html("");});
						$('#header_manager').load('content/manager/menu.php').css('display', 'inline-block').hide();
						$('#header_manager').fadeIn('slow', general = function(){$.getScript("client_processes/general_functions/pages_handler.js");});						
					}
					else if (response == "false")		// If wrong password
					{
						$('#login_error').fadeIn(500);
					}
				}
			});	
				
        }                  

    });
	
 });