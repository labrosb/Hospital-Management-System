// Real-time notifications to be placed on the menu 

$(document).ready(function() 
{
	$.ajax(
	{
		type: "POST",
		url: "server_processes/patient_functions/notifications.php",
		dataType: "json",	  					
		success: function(response){			// Retrieves info to be notified for
			create_notifications(response);		// Actions to be executed				
		}
	});
});

var patient_notif = setInterval(function()
{	
	$.ajax(
	{
		type: "POST",
		url: "server_processes/patient_functions/notifications.php",
		dataType: "json",	  					
		success: function(response)				// Retrieves info to be notified for
		{
			if(response == "EXPIRED")			 // If session has expired
			{
				clearInterval(patient_notif);	// Stops the callback
				session_expired();				// Calls function to notify and logout						
			}
			else if (response == "ACCESS DENIED")// If session is destroyed
			{
				clearInterval(patient_notif);
			}
			else {
				create_notifications(response);	// Actions to be executed					
			}
		}																						
	});	
}, 10000); // Every 10 seconds.
	
function create_notifications($value)
{	
	if ($value > 0)			// If info exists
	{
		$("#notif_all_p").html('<p>'+$value+'</p>').show();		// Include the counter value 
		$("#notif_results_p").html('<p>'+$value+'</p>').show();	// to the message and show it			
	}			
	else{					// If doesnt exist
		$("#notif_all_p").html('<p>'+$value+'</p>').hide();		// Replace all previous values
		$("#notif_results_p").html('<p>'+$value+'</p>').hide();	// with 0 and hide the message
	}				
}	