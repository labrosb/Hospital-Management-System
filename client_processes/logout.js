$(function(){
	$.ajax({
		type: "POST",
		async: false,
		url: "server_processes/logout.php",
		success: function(response){
			if ( response == "ok"){	
				if (typeof patient_notif !== 'undefined') {
					clearInterval(patient_notif);
				}
				else if (typeof staff_notif !== 'undefined') {
					clearInterval(staff_notif);
				}
				
				$('.content').load('content/content.php');
				
				$('#banner_container_patient').fadeOut('fast', function(){$('#banner_container_patient').html("");});
				$('#banner_container_staff').fadeOut('fast', function(){$('#banner_container_staff').html("");});
				$('#banner_container_manager').fadeOut('fast', function(){$('#banner_container_manager').html("");});

				$('#header_patient').fadeOut('fast', function(){$('#header_patient').html("");});
				$('#header_staff').fadeOut('fast', function(){$('#header_staff').html("");});				
				$('#header_manager').fadeOut('fast', function(){$('#header_manager').html("");});	

				$('#banner_container').load('content/banner.php').fadeIn('slow');
				//$('#banner_container').fadeIn('slow');				
				
				$('#header').load('content/menu.php').css('display', 'inline-block').hide();
				$('#header').fadeIn('slow', function(){$.getScript("client_processes/general.js");});
			}
		}
	});	
});