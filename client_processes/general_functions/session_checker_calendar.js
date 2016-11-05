// Checking session every 30 minutes and logs out if is expired or destroyed

var session_check_cal = setInterval(function()
{	
	if (calendar_session_checker() == "false")			// 	If session is expired or destroyed
	{

		calendar_expired();				// Calls function to notify and redirect	
	}
	
}, 1000*60*10); 	// Every 10 minutes.

function calendar_expired()
{
	$.ajax(
	{
		type: "POST",
		async: false,
		url: "../../server_processes/system_access_functions/logout.php",
		success: function(response)
		{
			clearInterval(session_check_cal);
			if(response == "ok")
			{
				$.msgBox(
				{								
					title: "Session Expired",
					content: "Login again to continue..",
					success: function(myresult) 
							{
								document.location.href="/hospital";
							}
				});		
			}
		}	
	});
}

function calendar_session_checker()
{
	return rights = $.ajax(				
					{					
						type: "POST",
						async: false,
						global: false,
						url: "/hospital/server_processes/system_access_functions/session_checker.php",
						success: function(response){
									return response;
								}
					}).responseText;			
}