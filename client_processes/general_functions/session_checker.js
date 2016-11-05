// Checking session every 30 minutes and logs out if is expired or destroyed

var session_check = setInterval(function()
{				
	if (session_checker() == "false" )			// 	If session is expired or destroyed
	{
		clearInterval(session_check);
		session_expired();						// Calls function to notify and logout	
	}	
	
}, 1000*60*10); 	// Every 10 minutes.