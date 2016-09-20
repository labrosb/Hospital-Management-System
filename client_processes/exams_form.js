function isValidDate(date) {

	var d = new Date();
	var currentYear = d.getFullYear();
	
	var valid = true;

	var arrayDate = date.split('/');
	var day = arrayDate[0];
	var month   = arrayDate[1];
	var year  = arrayDate[2];
	var other = arrayDate[3];
	
	if (typeof other != 'undefined'){ valid = false;}
	else if ((isNaN(day)) || (isNaN(month)) || (isNaN(year))) valid = false;
	else if((month < 1) || (month > 12)) valid = false;
	else if((day < 1) || (day > 31)) valid = false;
	else if((year < 1900) || (year > currentYear)) valid = false;
	else if(((month == 4) || (month == 6) || (month == 9) || (month == 11)) && (day > 30)) valid = false;
	else if((month == 2) && (((year % 400) == 0) || ((year % 4) == 0)) && ((year % 100) != 0) && (day > 29)) valid = false;
	else if((month == 2) && ((year % 100) == 0) && (day > 29)) valid = false;

	return valid;
}

function IsValidTime(timeStr) {


	var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

	var matchArray = timeStr.match(timePat);
	if (matchArray == null) {return false;}
	
	hour = matchArray[1];
	minute = matchArray[2];
	
	if (hour < 0  || hour > 23) {return false;}
	if (minute < 0 || minute > 59) {return false;}

}


$(document).ready(function() {

    var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' input[type=password]');
	
	inputFields.each(function(){	
	
		$(this).focus(function() {
			if($(this).val() == langPack[this.id]) {
				$(this).val("");
			}
		});
		
		$(this).focusout(function() {
			if($(this).val() == "") {
				$(this).val(langPack[this.id]);
			}
		});	
	
	});
		
    $('form').submit(function(){ return false; });
	$('#submit_exam').click(function(){
        //remove classes
        $('#first_step input').removeClass('error').removeClass('valid');

        //ckeck if inputs aren't empty
        var fields = $('#first_step input[type=text]');
        var error = 0;
		var date = $('#date').val();
		var time = $('#time').val();
		var dateValidation = isValidDate(date);
		var timeValidation = IsValidTime(time);
        fields.each(function(){
            var value = $(this).val();			
			if(value==langPack[$(this).attr('id')] && value!=langPack['doctor']) {
                $(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError5']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;	
			}else if (value.length<3){
				$(this).addClass('error');
                $(this).effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError1']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;					   
            }else if ($(this).attr('id') == "date" && dateValidation == false){ 			    
                $('#date').removeClass('valid').addClass('error');
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError9']);
                $('#date').effect("shake", { times:3 }, 50);
                error++;	
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
            }else if ($(this).attr('id') == "time" && timeValidation == false){ 			    
                $('#time').removeClass('valid').addClass('error');
                $('#time').effect("shake", { times:3 }, 50);
				$("label[for='"+$(this).attr('id')+"']").html(langPack['formError15']);
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 1);
                error++;				
            } else {
                $(this).addClass('valid');
				$("label[for='"+$(this).attr('id')+"']").fadeTo('slow', 0);
            }		
        });    
       if(!error) {  
			$('#form_loading').fadeIn(500);
			$('#form_msg').fadeOut(500);
			$('#error_msg').fadeOut(500).promise().done(function(){
				$.ajax({
					type: "POST",
					url: "server_processes/exam_insertion.php",
					async: false,
					data:{
						  exams_type: $('#exams_type').val(),
						  date: $('#date').val(),
						  time: $('#time').val(),
						  doctor: $('#thisDoctor').text(),
						  insurance: $('#insurance_checkbox').is(':checked')
						  },
					success: function(response){
						if (response == 'DONE'){	
							$("#form_loading").fadeOut('slow').promise().done(function(){							
							$('#first_step').fadeOut('slow');
							$('.content').animate({
								height: 250	
							}, 800, function(){								
										$('#second_step').html("<div id='message'></div>").fadeIn('slow')
										$('#message').html("<img id='checkmark' src='styles/images/check.png' />")
																.append('<h2 id = "success_msg1">'+langPack["appointmentComplete"]+'</h2>')
											.hide();
											$("message").removeAttr("data-inter");
											$("message").attr( 'data-inter', 'appointmentComplete' );	
										$('#message').fadeIn(2000, function() {
																$('<p id = "success_msg2">'+langPack["appointmentCompleteSub"]+'</p>').hide().appendTo("#message").fadeIn(1000);
																$("success_msg2").removeAttr("data-inter");
																$("success_msg2").attr('data-inter', 'appointmentCompleteSub');	
															});	
								}); 
							});
								
						}else if (response == 'EXAM TYPE ERROR'){
							//$('#form_msg').fadeOut('slow');
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58"/> </br><h1 data-inter="examWarning1"> '+langPack['examWarning1']+'</h1><p data-inter="examWarningSub1"> '+langPack['examWarningSub1']+'</p>').fadeIn('slow');
						}else if (response == 'DOCTOR ERROR'){
							//$('#form_msg').fadeOut('slow');
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58" /> </br><h1 data-inter="examWarning2"> '+langPack['examWarning2']+'</h1> <p data-inter="examWarningSub2"> '+langPack['examWarningSub2']+'</p>').fadeIn('slow');								
						}else if (response == 'AVAILABILITY ERROR'){
							//$('#form_msg').fadeOut('slow');
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58" /> </br><h1 data-inter="examWarning3"> '+langPack['examWarning3']+' </h1> <p data-inter="examWarning2"> '+langPack['examWarning2']+'</p>');								
						}else if (response == 'DOCTORS AVAILABILITY ERROR'){
							//$('#form_msg').fadeOut('slow');
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58" /></br><h1 data-inter="examWarning4"> '+langPack['examWarning4']+'</h1> <p data-inter="examWarningSub4> '+langPack['examWarningSub4']+'</p>').fadeIn('slow');								
						}else if (response == 'WARD AVAILABILITY ERROR'){
							//$('#form_msg').fadeOut('slow');
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58"/></br><h1 data-inter="examWarning5">'+langPack['examWarning5']+'</h1> <p data-inter="examWarningSub4> '+langPack['examWarningSub4']+'</p>').fadeIn('slow');														
						}else{
							$("#form_loading").fadeOut(500);			
							$('#error_msg').html('<img id="checkmark" src="styles/images/error.gif" height="58" width="58" /></br><h1 data-inter="examWarning6">'+langPack['examWarning6']+'</h1>').fadeIn('slow');														
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

        function noWeekendsOrHolidays(date) {
            var noWeekend = $.datepicker.noWeekends(date);
            if (noWeekend[0]) {
                return nationalDays(date);
            } else {
                return noWeekend;
            }
        }
        function nationalDays(date) {
            for (i = 0; i < natDays.length; i++) {
                if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) {
                    return [false, natDays[i][2] + '_day'];
                }
            }
            return [true, ''];
        }
        function AddWeekDays(weekDaysToAdd) {
            var daysToAdd = 0
            var mydate = new Date()
            var day = mydate.getDay()
            weekDaysToAdd = weekDaysToAdd - (5 - day)
            if ((5 - day) < weekDaysToAdd || weekDaysToAdd == 1) {
                daysToAdd = (5 - day) + 2 + daysToAdd
            } else { // (5-day) >= weekDaysToAdd
                daysToAdd = (5 - day) + daysToAdd
            }
            while (weekDaysToAdd != 0) {
                var week = weekDaysToAdd - 5
                if (week > 0) {
                    daysToAdd = 7 + daysToAdd
                    weekDaysToAdd = weekDaysToAdd - 5
                } else { // week < 0
                    daysToAdd = (5 + week) + daysToAdd
                    weekDaysToAdd = weekDaysToAdd - (5 + week)
                }
            }

            return daysToAdd;
        }

        $('#date').datepicker(
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
    	
	//$('#date').datepicker({dateFormat:'dd/mm/yy', showAnim:'fadeIn', showOn:'both', buttonImage:'styles/images/forms/calendar.png'});
	$('#time').timepicker({ minutes: { interval: 15 }, showOn: 'both', button: '.timeButton'});	
	
	$('#exams_type').on('input',function(e){
		if( $('#exams_type').val().length == 1 ){
			$('#exams_type').val("");
		}
	});
	$('#date').on('input',function(e){
		if( $('#date').val().length == 1 ){
			$('#date').val("");
		}
	});
	$('#time').on('input',function(e){
		if( $('#time').val().length == 1 ){
			$('#time').val("");
		}
	});
	$('#doctor').on('input',function(e){
		if( $('#doctor').val().length == 1 ){
			$('#doctor').val("");
		}
	});

	
 });