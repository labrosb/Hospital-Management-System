// Menu buttons redirecting to calendar pages

$(document).ready(function() 
{
	$('.general_program').click(function(){					// Menu choice click
		window.open('content/calendar/doctor_full.php');	// Opens the doctor schedule page		
	});	
	
	$('.daily_program').click(function(){					// Menu choice click
		window.open("content/calendar/doctor_daily.php");	// Opens the doctor daily schedule page		
	});		
	
	$('.on_duty_shifts').click(function(){					// Menu choice click
		window.open("content/calendar/on_duty_shift.php");	// Opens the call-duty/work-shifts schedule page		
	});	
});