// Logout UI functions

$(function()
{
	$.ajax(
	{
		type: "POST",
		async: false,
		url: "server_processes/system_access_functions/logout.php",
		success: function(response)
		{
			if ( response == "ok")								// If logout succeeds
			{				
				if (typeof patient_notif !== 'undefined') 		// If patient notifications variable exists
				{
					clearInterval(patient_notif);				// Clears patient notifications repeating functions
				}				
				if (typeof staff_notif !== 'undefined')	// If doctor notifications variable exists
				{
					clearInterval(staff_notif);					// Clears doctor notifications repeating functions
				}
				if(typeof session_check !== 'undefined')
				{
					clearInterval(session_check);					// Clears session check repeating functions
				}				
				
				$('.content').load('content/guest/content.php');  // Removes current content and loads guests content
				
				$('#banner_container_patient').fadeOut('fast', function(){$('#banner_container_patient').html("");});
				$('#banner_container_staff').fadeOut('fast', function(){$('#banner_container_staff').html("");});
				$('#banner_container_manager').fadeOut('fast', function(){$('#banner_container_manager').html("");});

				$('#header_patient').fadeOut('fast', function(){$('#header_patient').html("");});
				$('#header_staff').fadeOut('fast', function(){$('#header_staff').html("");});				
				$('#header_manager').fadeOut('fast', function(){$('#header_manager').html("");});	

				$('#banner_container').load('content/guest/banner.php').fadeIn('slow');
				
				$('#header').load('content/guest/menu.php').css('display', 'inline-block').hide();
				$('#header').fadeIn('slow', function(){$.getScript("client_processes/general_functions/pages_handler.js");});
			}
		}
	});	
});