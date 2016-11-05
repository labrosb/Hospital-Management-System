// Doctors registration section functions

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
	
									// Resets progress bar
    $('#progress').css('width','0');
    $('#progress_text').html(langPack['Completed_0']);
	$('#progress_text').removeAttr("data-inter");
	$('#progress_text').attr( 'data-inter', 'Completed_0' );
	$('#registration_container').height("603px");
		
	$.ajax(							// Retrieves doctor spexialities on page load
	{
		type: "POST",
		url: "server_processes/general_functions/exams_specialities_units.php",	
		data: {"get_specialities": null, "lang": defaultLang},
		dataType: "json",  					
		success: function(response){				
			create_list(response);
			}					
	});		
			
			
    //// First step ////
	
    $('form').submit(function(){ return false; });	
	
    $('#submit_first').click(function()
	{
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');			// Removes error and valid classes if are set

        var fields = $('#first_step input[type=text], #first_step select[type=text]');
        var error = 0;
		
        fields.each(function()
		{
			var date = $('#birthDate').val();
			var dateValidation = isValidDate(date);  
            var value = $(this).val();
			var field_id = $(this).attr('id');
			
            if( value == langPack[field_id ] && field_id !="mothersName") 			// If a fields except mother's name 
			{																		// has default value
				apply_error_events(this, 'formError5');								// Error classes messages and animations
                error++;		// (msg: This field is mandatory)
			}
			else if (value.length < 3)												// If value has < 3 characters
			{
				apply_error_events(this, 'formError8');								// Error classes messages and animations
                error++;		// (msg: Enter value with more than 3 characters)
            }
			else if (field_id  == 'birthDate' && dateValidation == false)			// If date is invalid
			{ 			    
				apply_error_events(this, 'formError9');								// Error classes messages and animations
                error++;		// (msg: Insert a valid date (dd/mm/yyyy))
            } 
			else{
                $(this).addClass('valid');
				$("label[for='"+field_id +"']").fadeTo('slow', 0);
            }		
        });        
        
        if(!error) 
		{
			$('.content').animate(
			{
				height: 873
			}, 800, function()						// Status bar update
					{
						$('#progress_text').html(langPack['Completed_33']);
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_33' );	
						$('#progress').css('width','113px');
						$('#myform').height("750px");					
						//slide steps
						$('#first_step').slideUp();
						$('#second_step').slideDown();	
					}
			); 
							
        }               

    });

    $('#submit_second').click(function()
{
        $('#second_step input').removeClass('error').removeClass('valid');			// Removes error and valid classes if are set		

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/; 		// E-mail pattern 
        var fields = $('#second_step input[type=text]');
        var error = 0;
		
        fields.each(function()
		{
            var value = $(this).val();
			var field_id = $(this).attr('id');
			var phone = $('#phone').val();
			var cellPhone = $('#cellPhone').val();

			if( field_id !='phone' && field_id !='cellPhone' && 					// If current input is NOT phone or postcode, 
				field_id !='postCode' && value==langPack[field_id ] ) 				// and value is the default value
			{
				apply_error_events(this, 'formError5');								// Error classes messages and animations
                error++;		// (msg: This field is mandatory)
			}
			else if(value.length<3)
			{
				apply_error_events(this, 'formError8');								// Error classes messages and animations
                error++;		// (msg: Enter value with more than 3 characters)
            }
			else if((field_id =='phone' || field_id =='cellPhone') && 				// If none of the phone fields is filled
					phone==langPack['phone'] && cellPhone==langPack['cellPhone'] ) 
			{
				apply_error_events(this, 'formError10');							// Error classes messages and animations
                error++;		// (msg: Insert at least 1 phone number)
            }
			else if(( field_id =='email' && !emailPattern.test(value) ) || 			// Invalid values
					( value!=langPack[field_id ] && field_id =='postCode' && 
					(isNaN( $('#postCode').val() ) ) ) || ( value!=langPack[field_id ] && 
					field_id =='phone' && (isNaN( $('#phone').val() ) ) ) || 
					( value!=langPack[field_id ] && field_id =='cellPhone' && 
					(isNaN( $('#cellPhone').val() ) ) ) || ( value!=langPack[field_id ] && 
					field_id =='workPhoneStaff' && (isNaN( $('#workPhoneStaff').val() ) ) ) ) 
			{
				apply_error_events(this, 'formError11');							// Error classes messages and animations
                error++;		// (msg: Invalid value)
			} 
			else {
                $(this).addClass('valid');											// Adds 'valid' class
				$("label[for='"+field_id+"']").fadeTo('slow', 0);					// Removes error messages
            }
        });

        if(!error) 						
		{   
			$('.content').animate(
			{
				height: 780
			}, 800, function()				// Status bar update
					{
						$('#progress_text').html(langPack['Completed_66']);
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_66' );
						$('#progress').css('width','226px');
						$('#progress_bar').css('margin-top','75px');
						$('#second_step#myform').height("710px");							
						//slide steps
						$('#second_step').slideUp();
						$('#third_step').slideDown();  
					}
			); 
				
        } else return false;

    });


    $('#submit_fourth').click(function()
	{
        $('#third_step input').removeClass('error').removeClass('valid');			// Removes error and valid classes if are set

        var fields = $('#third_step input[type=text], #third_step input[type=password]');
        var error = 0;
		
        fields.each(function()
		{
            var value = $(this).val();
			var field_id = $(this).attr('id');
			
            if( value==langPack[field_id ] ) 										// If value is the default one
			{
				apply_error_events(this, 'formError5');								// Error classes messages and animations
                error++;		// (msg: This field is mandatory)
			}
			else if ( field_id =='password' && value.length<5 )						// If password < 5 characters
			{
				apply_error_events(this, 'formError13');							// Error classes messages and animations
                error++;		// (msg: Insert a password with at least 5 characters)
			}
			else if ( ( field_id =='password' || field_id =='cpassword') && 		// If password <> confirmation
					 $('#password').val() != $('#cpassword').val() ) 
			{
				apply_error_events(this, 'formError14');							// Error classes messages and animations
                error++;		// (msg: The password and the confirmation are not the same)
            } 
			else {
                $(this).addClass('valid');
				$("label[for='"+field_id +"']").fadeTo('slow', 0);
            } 
        });        
		
        
        if(!error) 													// If no error 
		{  
			inputFields.each(function()
			{		
				if($(this).val() == langPack[this.id]) 				// Replaces default value with empty text
				{													// to be inserted to the database
					$(this).val("");
				}
			});
			
			$.ajax(													// Send data to server
			{
				type: "POST",
				url: "server_processes/manager_functions/doctor_registration.php",
				data:{
						name: $('#name').val(),
						surname: $('#surname').val(),
						fathersName: $('#fathersName').val(),
						mothersName: $('#mothersName').val(),
						sex: $('#sex').val(),
						birthDate: $('#birthDate').val(),
						address: $('#address').val(),
						city: $('#city').val(),
						postCode: $('#postCode').val(),
						phone: $('#phone').val(),
						cellPhone: $('#cellPhone').val(),
						workPhone: $('#workPhoneStaff').val(),
						email: $('#email').val(),
						specialty: $('#specialty').val(),
						biog: $('#biog').val(),
						password: $('#password').val()
						},
				success: function(response)
				{
						$('#third_step').fadeOut();					// Animates content
						$('.content').animate(
						{
							height: 250
						}, 800, function()
								{
									$('#myform').height("150px");
									$('#progress_bar').css('margin-top','-50px');
									$('#fourth_step').fadeIn();  
									
									if(response == "EXPIRED")		// If session is expired
									{
										session_expired();
									}
									else if ( response == "success")							// If succeed 
									{										
										$('#progress_text').html(langPack['Completed_100']);	// Progress bar update	
										$('#progress_text').removeAttr("data-inter");
										$('#progress_text').attr( 'data-inter', 'Completed_100' );
										$('#progress').css('width','339px');
										$('#fourth_step').html("<div id='message'></div>");		// Success message
										
										$('#message').html("<img id='checkmark' src='styles/images/check.png' />")
										.append('<h2 id = "success_msg1">'+langPack["docRegCompleted"]+'</h2>')
										.hide();
										$("message").removeAttr("data-inter");
										$("message").attr( 'data-inter', 'docRegCompleted' );
										$('#message').fadeIn(1500);												
									}
									else{										
										$('#progress_text').html(langPack['error']);			// Progress bar update (to error)
										$('#progress_text').removeAttr("data-inter");
										$('#progress_text').attr( 'data-inter', 'error' );
										$('#progress').css('width','339px');
										$('#fourth_step').html("<div id='message'></div>");		// Error message
										
										$('#message').html("<img id='checkmark' src='styles/images/error.gif' />")
										.append('<h2 id = "success_msg1">'+langPack["regFailed"]+'</h2>')
										.hide();
										
										$("message").removeAttr("data-inter");
										$("message").attr( 'data-inter', 'regFailed' );												
										$('#message').fadeIn(1500, 
										function() 												// Error message
										{
											$('<p id = "success_msg2">'+langPack["regSubFaied"]+'</p>')
											.hide().appendTo("#message").fadeIn(1000);
											$("success_msg2").removeAttr("data-inter");
											$("success_msg2").attr( 'data-inter', 'regSubFaied' );
										});	
									}
								}
						);
				}
			});	
				            
        } 
    });
	
	$('#back_second').click(function()
	{
		$('.content').animate(
		{
		height: 700
		}, 800, function()
				{
					$('#second_step').slideUp();
					$('#first_step').slideDown();
					$('#progress_bar').css('margin-top','0');
					$('#myform').height("630px");							
				}
		); 
	});
	
	$('#back_third').click(function()
	{
		$('.content').animate(
		{
			height: 873
		}, 800, function()
				{
					$('#second_step#myform').height("710px");							
					$('#third_step').slideUp();
					$('#second_step').slideDown();
				}
		); 
	});

	function create_list(response)
	{
		for(var i=0; i<response.length; i++)
		{
			$('.categories_select').append('<option data-inter="'+response[i]['data-inter']+'"value="'+response[i].id+'"> </option>');	
		}
		changeLang(defaultLang);
	}	
		
});
