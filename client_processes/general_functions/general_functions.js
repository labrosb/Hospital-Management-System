

//// Checks session (if exists) to identify the user's rights ////

function session_checker()
{
	return rights = $.ajax(				
					{					
						type: "POST",
						async: false,
						global: false,
						url: "server_processes/system_access_functions/session_checker.php",
						success: function(response){
									return response;
								}
					}).responseText;			
}

//// Actions taken when session expires ////

function session_expired()
{
	$.msgBox(
	{								
		title: langPack['sessionExpired'],
		content: langPack['loginAgain'],
		success: function(myresult) 
				{
					$.getScript("client_processes/general_functions/logout.js");
				}
	});
}

//// Apply error messages and animations ////

function apply_error_events($element, $errorMsg)	
{
	$($element).addClass('error');												// Adds error class to the fields to apply the error options
	$($element).effect("shake", { times:3 }, 50);								// Error animation effect to input bar
	$("label[for='"+$($element).attr('id')+"']").html(langPack[$errorMsg]);		// Sets error text to the label
	$("label[for='"+$($element).attr('id')+"']").removeAttr("data-inter");		// Removes previous data-inner attribude from the label
	$("label[for='"+$($element).attr('id')+"']").attr('data-inter',$errorMsg ); // Adds new attribute data-inter (responsible for the language change)
	$("label[for='"+$($element).attr('id')+"']").fadeTo('slow', 1);				// Fades in the label of the field where the error occurs	
}
	
	
////  Function to check if mail already exists	////

function mailChecker(mail)							
{
	var result = $.ajax(
				{
					type: "POST",
					async: false,
					url: "server_processes/general_functions/mail_check.php",
					data:{
						 email: $('#email').val(),					
						},
					success: function(response)
					{
						return response;
					}	
				}).responseText;
		return result;
	}		


//// Date validation ///// 
	
function isValidDate(date) 						
{
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

//// Time validation /////

function IsValidTime(timeStr) 
{
	var timePat = /^(\d{1,2}):(\d{2})(:(\d{2}))?(\s?(AM|am|PM|pm))?$/;

	var matchArray = timeStr.match(timePat);
		
	if (matchArray == null) {return false;}
		
	hour = matchArray[1];
	minute = matchArray[2];
		
	if (hour < 0  || hour > 23) {return false;}
	if (minute < 0 || minute > 59) {return false;}
}	
