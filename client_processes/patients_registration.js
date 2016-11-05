// Patient registration section functions

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

    $('#progress').css('width','0');					// Resets progress bar
   	$('#progress_text').html(langPack['Completed_0']);
	$('#progress_text').removeAttr("data-inter");
	$('#progress_text').attr( 'data-inter', 'Completed_0' );
	$('#registration_container').height("603px");
	
	
    //// First_step ////
	
    $('form').submit(function(){ return false; });
	
    $('#submit_first').click(function()
	{
        $('#first_step input').removeClass('error').removeClass('valid');// Removes submit error class and adds valid class

        var fields = $('#first_step input[type=text], #first_step select[type=text]');
        var error = 0;
		
        fields.each(function()											// For each input field 
		{
			var date = $('#birthDate').val();
			var dateValidation = isValidDate(date);  					// Validates the date
            var value = $(this).val();
			var field_id = $(this).attr('id');
			
            if( value == langPack[field_id] ) 							// If value is the default value
			{
				apply_error_events(this, 'formError5');					// Error classes messages and animations
                error++;	// (msg: This field is mandatory)
			}
			else if (value.length < 3)									// If value is < 3 characters
			{
				apply_error_events(this, 'formError8');					// Error classes messages and animations
                error++;	// (msg: Enter value with more than 3 characters)
            }
			else if (field_id == 'birthDate' && 						// If birth date is a valid date
					 dateValidation == false)
			{ 			    
				apply_error_events(this, 'formError9');					// Error classes messages and animations
                error++;	// (msg: Insert a valid date (dd/mm/yyyy))
            } 
			else{
                $(this).addClass('valid');								// Adds 'valid' class
				$("label[for='"+field_id+"']").fadeTo('slow', 0);		// Removes error messages
            }		
        });        
        
        if(!error) 
		{
			$('.content').animate(
			{
				height: 773
			}, 800, function()
					{														// Updates status bar
						$('#progress_text').html(langPack['Completed_33']);			
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_33' );	
						$('#progress').css('width','113px');
						$('#registration_container').height("703px");
						//slide steps
						$('#first_step').slideUp();
						$('#second_step').slideDown();	
					}		
			); 						
        }               
    });

	//// Second step ////
	 
    $('#submit_second').click(function()
	{
        $('#second_step input').removeClass('error').removeClass('valid');	// Removes submit error class and adds valid class

        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;  // E-mail pattern
        var fields = $('#second_step input[type=text]');
        var error = 0;
		
        fields.each(function()
		{
            var value = $(this).val();
			var field_id = $(this).attr('id');
			var phone = $('#phone').val();
			var cellPhone = $('#cellPhone').val();
			
			if( field_id !='phone' && field_id !='cellPhone' && 			// If it's not a phone number
				field_id !='postCode' && value == langPack[field_id] ) 		// and has the default value
			{
				apply_error_events(this, 'formError5');						// Error classes messages and animations
                error++;	// (msg: This field is mandatory)
			}
			else if( (field_id =='phone' || field_id =='cellPhone' || 		// If it's a phone number
					  field_id =='postCode') && value.length<3)				// and the value is < 3
			{
				apply_error_events(this, 'formError8');						// Error classes messages and animations
                error++;	// (msg: Enter value with more than 3 characters)
            }
			else if((field_id =='phone' || field_id=='cellPhone') && 		// If it's a phone number and none has a value
					 phone==langPack['phone'] && cellPhone==langPack['cellPhone'] ) 
			{
				apply_error_events(this, 'formError10');					// Error classes messages and animations
                error++;	// (msg: Insert at least 1 phone number)
            }
			else if(( field_id =='email' && !emailPattern.test(value) ) || 	// All checks for invalid values
					(value != langPack[field_id] && field_id =='postCode' && 
					(isNaN( $('#postCode').val() ) ) ) || ( value != langPack[field_id] && 
					field_id =='phone' && (isNaN( $('#phone').val() ) ) ) || 
					( value != langPack[field_id] && field_id =='cellPhone' && 
					(isNaN( $('#cellPhone').val() ) ) ) ) 
			{
				apply_error_events(this, 'formError11');					// Error classes messages and animations
                error++;	// (msg: Invalid value)
			}
			else if(field_id =='email' && (mailChecker(value) == 'YES'))	// If e-mail already exists
			{		
				apply_error_events(this, 'formError12');					// Error classes messages and animations
                error++;	// (msg: The e-mail address is already used!)		
            }
			else {
                $(this).addClass('valid');									// Adds 'valid' class
				$("label[for='"+field_id+"']").fadeTo('slow', 0);			// Removes error messages
            }
        });

        if(!error)
		{   
			$('.content').animate(
			{
				height: 773
			}, 800, function()
					{														// Updates status bar
						$('#progress_text').html(langPack['Completed_66']);
						$('#progress_text').removeAttr("data-inter");
						$('#progress_text').attr( 'data-inter', 'Completed_66' );
						$('#progress').css('width','226px');
						//slide steps
						$('#second_step').slideUp();
						$('#third_step').slideDown();  
					}
			); 
				
        } else return false;

    });
	
	$('#insuranceCode').val('-') ;
	$('#insurer').change(function() 	// Insurance option handling
	{			
		if ($('#insurer').val() != langPack['insuranceOption1']) 
		{	
			$('#insuranceCode').val();			
			$('#insuranceCode').removeAttr('disabled');	
		}
		else{
			$("#insuranceCode").attr('disabled','disabled');		
			$('#insuranceCode').val('-') ;		
		}
	});	

	 //// Third step ////
	 
    $('#submit_fourth').click(function()
	{
        $('#third_step input').removeClass('error').removeClass('valid');	// Removes submit error class and adds valid class

        var fields = $('#third_step input[type=text], #third_step input[type=password]');
        var error = 0;
		
        fields.each(function()
		{
            var value = $(this).val();
			var field_id = $(this).attr('id');
			
            if( value == langPack[field_id] ) 								// If value is the default value						
			{
				apply_error_events(this, 'formError5');						// Error classes messages and animations
                error++;		// (msg: This field is mandatory)
			}
			else if ( field_id =='password' && value.length<5 )				// If password < 5 characters
			{
				apply_error_events(this, 'formError13');					// Error classes messages and animations
                error++;		// (msg: Insert a password with at least 5 characters)
			}
			else if (( field_id =='password' || field_id =='cpassword') &&	// If password is different than the 
						$('#password').val() != $('#cpassword').val()) 		// confirmation
			{
				apply_error_events(this, 'formError14');					// Error classes messages and animations
                error++;		// (msg: The password and the confirmation are not the same)
            } 
			else {
                $(this).addClass('valid');									// Adds 'valid' class
				$("label[for='"+field_id+"']").fadeTo('slow', 0);			// Removes error messages		

            } 
        });        
		       
        if(!error) 
		{				
			inputFields.each(function()
			{		
				if($(this).val() == langPack[this.id])				// Sets inputs that have the defaul value
				{													// to blank before sending them to the server
					$(this).val("");
				}
			});			
			
			$.ajax(
			{
				type: "POST",
				url: "server_processes/patient_registration.php",
				data:{
						name: $('#name').val(),						// Inputs
						surname: $('#surname').val(),
						fathersName: $('#fathersName').val(),
						sex: $('#sex').val(),
						birthDate: $('#birthDate').val(),
						address: $('#address').val(),
						city: $('#city').val(),
						postCode: $('#postCode').val(),
						phone: $('#phone').val(),
						cellPhone: $('#cellPhone').val(),
						email: $('#email').val(),
						insurer: $('#insurer').val(),
						insuranceCode: $('#insuranceCode').val(),
						password: $('#password').val()
						},
				success: function(response)
				{
					$('#third_step').fadeOut();
					$('.content').animate({
						height: 250
					}, 800, function()
							{
								$('#fourth_step').fadeIn();  
								if ( response == "succeed")											// If registration succeed
								{									
									$('#progress_text').html(langPack['Completed_100']);			// Updates status bar			
									$('#progress_text').removeAttr("data-inter");					
									$('#progress_text').attr( 'data-inter', 'Completed_100' );
									$('#progress').css('width','339px');
									
									$('#fourth_step').html("<div id='message'></div>");				
									$('#message').html("<img id='checkmark' src='styles/images/check.png' />")
									.append('<h2 id = "success_msg1">'+langPack["regCompleted"]+'</h2>')
									.hide();													
																								// Pefrorms a number of animations
									$("message").removeAttr("data-inter");						// to show the success page
									$("message").attr( 'data-inter', 'regCompleted' );	
									$('#message').fadeIn(1500, function() 
									{
										$('<p id = "success_msg2">'+langPack["regSubMsg"]+'</p>')
										.hide().appendTo("#message").fadeIn(1000);
										
										$("success_msg2").removeAttr("data-inter");
										$("success_msg2").attr( 'data-inter', 'regSubMsg' );	
									});
								}
								else{															// If registration failed								
									$('#progress_text').html(langPack['error']);				// Pefrorms a number of animations
									$('#progress_text').removeAttr("data-inter");				// to show the error page
									$('#progress_text').attr( 'data-inter', 'error' );
									$('#progress').css('width','339px');
									$('#fourth_step').html("<div id='message'></div>");
									$('#message').html("<img id='checkmark' src='styles/images/error.gif' />")
									.append('<h2 id = "success_msg1">'+langPack["regFailed"]+'</h2>')
									.hide();
											
									$("message").removeAttr("data-inter");
									$("message").attr( 'data-inter', 'regFailed' );												
									$('#message').fadeIn(1500, function() 
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
	
	$('#back_second').click(function()				// Animation effects and move to the previous step
	{
		$('.content').animate(
		{
		height: 653
		}, 800, function()
				{
					$('#second_step').slideUp();
					$('#first_step').slideDown();
				}
		); 

	});
	
	$('#back_third').click(function()				// Animation effects and move to the previous step
	{
		$('.content').animate(
		{
		height: 753
		}, 800, function()
				{
					$('#third_step').slideUp();
					$('#second_step').slideDown();
				}
		); 
	});

});