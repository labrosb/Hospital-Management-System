$(document).ready(function() {
	$.ajax({
		type: "POST",
		url: "server_processes/staff_notifications.php",
		dataType: "json",	  					
		success: function(response){
			if (response > 0){
				$("#notif_all_p").html('<p>'+response+'</p>').show();
				if (response > 0){
					$("#notif_results_p").html('<p>'+response+'</p>').show();
				}
			}			
			else{
			$("#notif_all_p").html('<p>'+response+'</p>').hide();
				$("#notif_results_p").html('<p>'+response+'</p>').hide();									
			}									
		}
	});	
});

var staff_notif = setInterval(function(){	
							$.ajax({
								type: "POST",
								url: "server_processes/staff_notifications.php",
								dataType: "json",	  					
								success: function(response){
									if (response > 0){
										$("#notif_all_p").html('<p>'+response+'</p>').show();
										if (response > 0){
											$("#notif_results_p").html('<p>'+response+'</p>').show();
										}
									}	
									else{
										$("#notif_all_p").html('<p>'+response+'</p>').hide();
										$("#notif_results_p").html('<p>'+response+'</p>').hide();					
									}
														
								}
							});	
						}, 10000); // Every 10 seconds.