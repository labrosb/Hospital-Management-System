$(function()
{	
	var passFields = $('#third_step input[type=password]');	
	passFields.each(function()
	{																						// Erases password valus
		$(this).val("");	
	});
	
	function apply_error_events($element, $errorMsg)
	{	
        $($element).addClass('error');														// Adds error class to the fields to apply the error options
        $($element).effect("shake", { times:3 }, 50);										// Error animation effect to input bar
		$("label[for='"+$($element).attr('id')+"']").html(langPack[$errorMsg]);				// Sets error text to the label
		$("label[for='"+$($element).attr('id')+"']").removeAttr("data-inter");				// Removes previous data-inner attribude from the label
		$("label[for='"+$($element).attr('id')+"']").attr('data-inter',$errorMsg ); 		// Adds new attribute data-inter (responsible for the language change)
		$("label[for='"+$($element).attr('id')+"']").fadeTo('slow', 1);						// Fades in the label of the field where the error occurs		
	}  	
	
	
	////// First form //////

    $('form').submit(function(){ return false; });
    $('#submit_edit_first').click(function()
	{
        $('#submit_edit_first').removeClass('error').removeClass('valid');					// Removes submit error class and adds valid class

		var fields = $('#first_step input[type=text]');
        var error = 0;
        fields.each(function()																// Loop passes through all text input fields of the first form
		{																			
			var phone = $('#phone').val();
			var cellPhone = $('#cellPhone').val();
			var value = $(this).val();
			if(  $(this).attr('id')!='phone' && $(this).attr('id')!='cellPhone' && 			// If current input is NOT phone, cell phone or postcode
				$(this).attr('id')!='postCode' && value=='' ) 								// and the input value is the same as the default value (as blank)
			{
				apply_error_events(this,'formError5');										// Error classes messages and animations
                error++;				// (msg: This field is mandatory)
			}	
			else if (value.length<3 && value != '')											// If input is smaller than 3 characters
			{				
				apply_error_events(this, 'formError8');
                error++;;				// (msg: Enter value with more than 3 characters)
			}
			else if(($(this).attr('id')=='phone' || $(this).attr('id')=='cellPhone') && 	// If no regular phone numer of cell-phone number is inserted
					phone=='' && cellPhone=='' ) 											// notifies that at least 1 of them should be added
			{
				apply_error_events(this,'formError10');										// Error classes messages and animations             
                error++;;				// (msg: Insert at least 1 phone number)
            }
			else if((value!='' && $(this).attr('id')=='postCode' && 						// Cases of non-numeric input where numeric  
					(isNaN( $('#postCode').val() ) ) ) || ( value!='' && 					// is required
					$(this).attr('id')=='phone' && (isNaN( $('#phone').val() ) ) ) || 
					( value!='' && $(this).attr('id')=='cellPhone' && 
					(isNaN( $('#cellPhone').val() ) ) ) ) 
			{
				apply_error_events(this,'formError11');										// Error classes messages and animations            
                error++;				// (msg: Invalid value)
            }else{
                $(this).addClass('valid');													// If none of the above occurs, add class "valid"
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);					// Remove all error classes (and messages) if
            }																				// appeared before
        });                     	
		
	    if(!error) 																			// If all inputs are valid
		{
			$('.error_p').fadeTo('fast', 0);												// Remove error messages if exists
			$('#form_msg1 h1').fadeTo('fast', 0);											// Remove regular labels
			$('#form_loading_p').fadeTo('slow', 1);  										// Visualize waiting animation during the delay..		
			sendData(1);																	// Procceed to the data registration
		}
    });
	

	////// Second form //////

    $('#submit_edit_second').click(function()
	{
        $('#second_step input').removeClass('error').removeClass('valid');					// Removes submit error class and adds valid class
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  
        var fields = $('#second_step input[type=text], #second_step input[type=password]');
        var error = 0;
		
        fields.each(function()
		{	
			var value = $(this).val();
					
			if(value=='' ) 																	// If value is the default value
			{
				apply_error_events(this,'formError5');										// Error classes messages and animations
                error++;			// (msg: This field is mandatory)	
			}
            else if( $(this).attr('id')=='email' && !emailPattern.test(value) ) 			// If e-mail input field not in the correct pattern
			{
				apply_error_events(this,'formError11');										// Error classes messages and animations              
                error++;			// (msg: Invalid value)
			}
			else if($(this).attr('id')=='password' && value.length<5)						// If password < 5 characters
			{
				apply_error_events(this,'formError3');										// Error classes messages and animations    
                error++;			// (msg: The password must be longer than 5 characters)							
            }
			else{
                $(this).addClass('valid');													// If none of the above occurs, add class "valid"
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);					// Remove all error classes (and messages) if
            }																				// appeared before           
        });
		
	    if(!error)  																		// If all inputs are valid
		{
			$('.error_p').fadeTo('fast', 0);												// Remove error messages if exists
			$('#form_msg2 h1').fadeTo('fast', 0);											// Remove regular labels			
			$('#form_msg2 p').fadeTo('fast', 0);  					
			$('#form_loading_p2').fadeTo('slow', 1);  										// Visualize waiting animation during the delay..	
			sendData(2);																	// Procceed to the data registration
		}
    });
	
	////// Third form //////	
	
    $('#submit_edit_third').click(function()
	{
        $('#third_step input').removeClass('error').removeClass('valid');					// Removes submit error class and adds valid class

        var fields = $('#third_step input[type=password]');
        var error = 0;
        fields.each(function()
		{
            var value = $(this).val();
            if( value=='' ) 																// If value is the default value							
			{
				apply_error_events(this,'formError5');										// Error classes messages and animations  
                error++
			}
			else if (($(this).attr('id')=='oldPassword' && value.length<5) ||				// If old pass, new pass or confirmation < 5
					  ($(this).attr('id')=='newPassword' && value.length<5) ||				
					  ($(this).attr('id')=='passwordConf' && value.length<5) )
			{
				apply_error_events(this,'formError3');										// Error classes messages and animations    
                error++;
			}
			else if ($('#newPassword').val() != $('#passwordConf').val())					// if pass is not the same as the confirmation field
			{
				apply_error_events(this,'formError14');										// Error classes messages and animations   
				error++;
            } else {
                $(this).addClass('valid');													// If none of the above occurs, add class "valid"
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);					// Remove all error classes (and messages) if
            }																				// appeared before
        });        

	    if(!error) 																			// If all inputs are valid
		{
			$('.error_p').fadeTo('fast', 0);												// Remove error messages if exists
			$('#form_msg3 h1, #form_msg3 p').fadeTo('fast', 0);								// Remove regular labels
			$('#form_loading_p3').fadeTo('slow', 1);  										// Visualize waiting animation during the delay..  			
			sendData(3);																	// Procceed to the data registration
		}		
	
    });

	//////Switch buttons //////	
												
	$('.choice_buttons tr #communication_upd').click(function()
	{	
		$('.content').animate({
			height: 690
			}, 800, function(){									// Switches the form contents
					$('#first_step').slideDown();
					$('#second_step').slideUp();
					$('#third_step').slideUp();	
				}	
		);
	});
	
	$('.choice_buttons tr #email_upd').click(function()
	{
		$('#first_step').slideUp();	
		$('#third_step').slideUp();		
		$('.content').animate({
			height: 450
			}, 800, function(){									// Switches the form contents
					$('#first_step').slideUp();	
					$('#second_step').slideDown();
					$('#third_step').slideUp();	

				}	
		);
	});	

	$('.choice_buttons tr #password_upd').click(function()
	{
		$('.content').animate({
			height: 510
			}, 800, function(){									// Switches the form contents
					$('#first_step').slideUp();
					$('#second_step').slideUp();
					$('#third_step').slideDown();
				}	
		);
	});	
	
	
	////// Sends data to server //////
	
	function sendData(step)
	{
		$.ajax(
		{
			type: "POST",
			url: "server_processes/edit_patient.php",
			data:{
				step: step,
				birthDate: $('#birthDate').val(),
				address: $('#address').val(),
				city: $('#city').val(),
				postCode: $('#postCode').val(),
				phone: $('#phone').val(),
				cellPhone: $('#cellPhone').val(),
				email: $('#email').val(),
				password: $('#password').val(),
				newPassword: $('#newPassword').val(),
				oldpass: $('#oldPassword').val(),
						},
			success: function(response)
			{
						if(response == "EXPIRED")			// If the server respons that session has expired
						{
							session_expired();				// Calls function to alert the user and logout	
						}
						else if(response == "DONE")			// If all data is succesfully inserted
						{
							if(step == 1)					// If data is from the first form
							{
								$('#form_loading_p').fadeTo('slow', 0);  						// Hide loading animation
								$('h1.succ').html(langPack['editSuccess']).fadeTo('slow', 1);	// Show success message

							}
							else if(step == 2)				// If data is from the second form
							{
								$('#form_loading_p2').fadeTo('slow', 0);   						// Hide loading animation 			
								$('h1.succ2').html(langPack['editSuccess']).fadeTo('slow', 1);	// Show success message
							}	
							else if(step == 3)				// If data is from the third form
							{
								$('#form_loading_p3').fadeTo('slow', 0);    					// Hide loading animation			
								$('h1.succ3').html(langPack['editSuccess']).fadeTo('slow', 1);	// Show success message
							}	
						}						
						else if(response =="WRONG PASS")	// If password is invalid
						{
							if(step == 2)					// If is the password of the second form
							{
								$('#form_loading_p2').fadeTo('slow', 0);      								// Hide loading animation				
								$('#form_msg2 h1.error_p').html(langPack['wrongPass']).fadeTo('slow', 1);	// Show error message
								$('#form_msg2 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);								
							}	
							else if(step == 3)				// If is the password of the third form
							{
								$('#form_loading_p3').fadeTo('slow', 0);      								// Hide loading animation  			
								$('#form_msg3 h1.error_p').html(langPack['wrongPass']).fadeTo('slow', 1);	// Show error message
								$('#form_msg3 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}
						}else{								/// Else (if something went wrong)
							if(step == 1)					// If data is from the first form
							{
								$('#form_loading_p').fadeTo('slow', 0);      								// Hide loading animation  	
								$('#form_msg1 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);	// Show error message					
							}							
							else if(step == 2)				// If data is from the second form
							{
								$('#form_loading_p2').fadeTo('slow', 0);      								// Hide loading animation  			
								$('#form_msg2 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);	// Show error message
								$('#form_msg2 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}	
							else if(step == 3)				// If data is from the third form
							{
								$('#form_loading_p3').fadeTo('slow', 0);      								// Hide loading animation  			
								$('#form_msg3 h1.error_p').html(langPack['regFailed']).fadeTo('slow', 1);	// Show error message
								$('#form_msg3 p.error_p').html(langPack['regSubFaied']).fadeTo('slow', 1);
							}											
						}
					}
		});	
				            
	} 		
		
});