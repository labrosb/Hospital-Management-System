$(document).ready(function() 
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
	$('#submit_exam').click(function()
	{
        $('#first_step input').removeClass('error').removeClass('valid');		// Removes error and valid classes if are set	

        var fields = $('#first_step input[type=text]');
        var error = 0;
		var date = $('#date').val();
		var time = $('#time').val();
		var dateValidation = isValidDate(date);
		var timeValidation = IsValidTime(time);
		
        fields.each(function()
		{
            var value = $(this).val();	
			var field_id = $(this).attr('id');
			
			if(value==langPack[field_id ] && value!=langPack['doctor']) 		// If input has the default value
			{																	// (excluding the doctor's field) 
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+field_id+"']").html(langPack['formError5']);   // msg : This field is mandatory
				$("label[for='"+field_id+"']").fadeTo('slow', 1);
                error++;	
			}
			else if (value.length<3)											// If input value < 3 characters
			{
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+field_id+"']").html(langPack['formError1']);	// msg : The username must be longer 
				$("label[for='"+field_id+"']").fadeTo('slow', 1);				// than 3 characters
                error++;					   
            }
			else if (field_id  == "date" && dateValidation == false)			// If date is invalid
			{ 			    
                $('#date').addClass('error');
				$('#date').effect("shake", { times:3 }, 50); 
				$("label[for='"+field_id+"']").html(langPack['formError9']); 	// msg : Insert a valid date (dd/mm/yyyy)        	
				$("label[for='"+field_id+"']").fadeTo('slow', 1);
				error++;
            }
			else if (field_id  == "time" && timeValidation == false)			// If time is invalid
			{ 			    
                $('#time').addClass('error');
                $('#time').effect("shake", { times:3 }, 50);
				$("label[for='"+field_id+"']").html(langPack['formError15']);	// msg : Insert a valid time (hh:mm)
				$("label[for='"+field_id+"']").fadeTo('slow', 1);
                error++;				
            } 
			else {
                $(this).addClass('valid');										// Adds 'valid' class
				$("label[for='"+field_id+"']").fadeTo('slow', 0);				// Removes error messages
            }		
        }); 
		
       if(!error) 
	   {  
			$('#form_loading').fadeIn(500);
			$('#form_msg').fadeOut(500);
			$('#error_msg').fadeOut(500).promise().done(function()
			{
				$.ajax(															// Sends data to database
				{
					type: "POST",
					url: "server_processes/patient_functions/exam_insertion.php",
					async: false,
					data:{
						  exams_type: $('#exams_type').val(),
						  date: $('#date').val(),
						  time: $('#time').val(),
						  doctor: $('#thisDoctor').text(),
						  insurance: $('#insurance_checkbox').is(':checked'),
						  lang: defaultLang
						  },
					success: function(response)									 
					{
						if(response == "EXPIRED")								// If session has expired
						{
							session_expired();									// Calls function to alert user and logout
						}
						else if (response == 'DONE')							// If appointment booked successfully
						{	
							$("#form_loading").fadeOut('slow').promise().done(function()
							{							
								$('#first_step').fadeOut('slow');				// Creates a number of syncronized animations
								$('.content').animate({							// and messages to inform that appoinment booked
								height: 250	
								}, 800, function()
										{								
											$('#second_step').html("<div id='message'></div>").fadeIn('slow')
											$('#message').html("<img id='checkmark' src='styles/images/check.png' />")
											.append('<h2 id = "success_msg1">'+langPack["appointmentComplete"]+'</h2>')
											.hide(); 										// (msg: Your appointment is succesfully submitted!)
											$("message").removeAttr("data-inter");
											$("message").attr( 'data-inter', 'appointmentComplete' );	
											$('#message').fadeIn(2000,
											function() 
											{							// (msg: You will soon recieve an e-mail with your appointement details!! )
												$('<p id = "success_msg2">'+langPack["appointmentCompleteSub"]+'</p>')
												.hide().appendTo("#message").fadeIn(1000);  
												$("success_msg2").removeAttr("data-inter");
												$("success_msg2").attr('data-inter', 'appointmentCompleteSub');	
											});	
										}
								); 
							});								
						}												/// Error handling: Different cases	///									
						else if (response == 'EXAM TYPE ERROR')				
						{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58"/>'+
							'</br><h1 data-inter="examWarning1"> '+langPack['examWarning1']+'</h1>'+
							'<p data-inter="examWarningSub1"> '+langPack['examWarningSub1']+'</p>')
							.fadeIn('slow');					// (msg1: Invalid examination type!)
						}										// (msg2: Choose a type from the list.)
						else if (response == 'DOCTOR ERROR')
						{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58" />'+
							'</br><h1 data-inter="examWarning2"> '+langPack['examWarning2']+'</h1>'+
							'<p data-inter="examWarningSub2"> '+langPack['examWarningSub2']+'</p>')
							.fadeIn('slow');					// (msg1: Invalid doctor!)
						}										// (msg2: Choose a doctor from the list.)
						else if (response == 'AVAILABILITY ERROR')
						{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58" />'+
							'</br><h1 data-inter="examWarning3"> '+langPack['examWarning3']+' </h1>'+
							'<p data-inter="examWarning2"> '+langPack['examWarning2']+'</p>')
							.fadeIn('slow');					// (msg1: The doctor is no loner available!)
						}										// (msg2: Invalid doctor!)
						else if (response == 'DOCTORS AVAILABILITY ERROR')
						{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58" />'+
							'</br><h1 data-inter="examWarning4"> '+langPack['examWarning4']+'</h1>'+
							'<p data-inter="examWarningSub4> '+langPack['examWarningSub4']+'</p>')
							.fadeIn('slow');					// (msg1: We are sorry! There is no available doctor!)
						}										// (msg2: Choose different date or time.)
						else if (response == 'WARD AVAILABILITY ERROR')
						{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58"/>'+
							'</br><h1 data-inter="examWarning5">'+langPack['examWarning5']+'</h1>'+
							'<p data-inter="examWarningSub4> '+langPack['examWarningSub4']+'</p>')
							.fadeIn('slow');					// (msg1: We are sorry! There is no available ward!)
						}										// (msg2: Choose different date or time.)
						else{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html(
							'<img id="checkmark" src="styles/images/error.gif" height="58" width="58"/>'+
							'</br><h1 data-inter="examWarning6">'+langPack['examWarning6']+'</h1>')
							.fadeIn('slow');					// (msg: CONNECTION ERROR)
						}	
					}
				});	
			});		
        }   
		
    });

    var dateMin = new Date();
    var weekDays = AddWeekDays(3);

    dateMin.setDate(dateMin.getDate() + weekDays-4);
											
    var natDays = [
		[1, 1, 'uk'],
        [12, 25, 'uk'],
        [12, 26, 'uk']
     ];

    $('#date').datepicker(					// Date picker calendar
    {
        inline: true,
        beforeShowDay: noWeekendsOrHolidays,
        showOn: "both",
		buttonImage:'styles/images/forms/calendar.png',
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        changeFirstDay: false,
        minDate: dateMin
    });
											// Time picker 
	$('#time').timepicker({ minutes: { interval: 15 }, showOn: 'both', button: '.timeButton'});	
	
	$('#exams_type').on('input',function(e)
	{
		if( $('#exams_type').val().length == 1 )
		{
			$('#exams_type').val("");
		}
	});
	
	$('#date').on('input',function(e)
	{
		if( $('#date').val().length == 1 )
		{
			$('#date').val("");
		}
	});
	
	$('#time').on('input',function(e)
	{
		if( $('#time').val().length == 1 )
		{
			$('#time').val("");
		}
	});
	
	$('#doctor').on('input',function(e)
	{
		if( $('#doctor').val().length == 1 )
		{
			$('#doctor').val("");
		}
	});		 
	 
	//// Date picker calendar restrictions ////
	
    function noWeekendsOrHolidays(date) 
	{
        var noWeekend = $.datepicker.noWeekends(date);
		
        if (noWeekend[0]) 
		{
            return nationalDays(date);
        } 
		else {
			return noWeekend;
        }
    }
	
    function nationalDays(date) 
	{
        for (i = 0; i < natDays.length; i++) 
		{
            if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) 
			{
                return [false, natDays[i][2] + '_day'];
            }
		}
        return [true, ''];
    }
	
    function AddWeekDays(weekDaysToAdd) 
	{
        var daysToAdd = 0
        var mydate = new Date()
        var day = mydate.getDay()
        weekDaysToAdd = weekDaysToAdd - (5 - day)
		
        if ((5 - day) < weekDaysToAdd || weekDaysToAdd == 1) 
		{
            daysToAdd = (5 - day) + 2 + daysToAdd
        } 
		else { // (5-day) >= weekDaysToAdd
            daysToAdd = (5 - day) + daysToAdd
        }
		
        while (weekDaysToAdd != 0) 
		{
            var week = weekDaysToAdd - 5
            if (week > 0) 
			{
                daysToAdd = 7 + daysToAdd
                weekDaysToAdd = weekDaysToAdd - 5
            } 
			else { // week < 0
                daysToAdd = (5 + week) + daysToAdd
                weekDaysToAdd = weekDaysToAdd - (5 + week)
            }
        }
		
        return daysToAdd;
    }
	
 });