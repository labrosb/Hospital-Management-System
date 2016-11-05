//General pages handler..

$(document).ready(function()
{	
	//Session check (just for UI)
	
	var loading = true;

	var rights = session_checker();		// Checks session (if exists) to identify the user's rights
								
								
	// This is used only for the UI effect and can be bypassed,									
	// therefore security function is added in the main files	
	
	if (rights == 'false')				// If user has no rights (guest)
	{			
		var page = 'index';				// Corresponding content implementation		
		var nav = 'nav';
		var path = 'content/guest/';
	}									
	else if (rights == 'is_patient')	// If user has patient's rights 
	{				
		var page = 'news';				// Corresponding content implementation						
		var nav = 'nav_patient';
		var path = 'content/patient/';
	}
	else if (rights == 'is_doctor')		// If user has doctor's rights
	{
		var page = 'news';				// Corresponding content implementation		
		var nav = 'nav_staff';
		var path = 'content/doctor/';		
	}
	else if (rights == 'is_manager')	// If user has manager's rights
	{
		var page = 'news';				// Corresponding content implementation		
		var nav = 'nav_manager';
		var path = 'content/manager/';			
	}	
	
	var retain_height;	
	$('.'+page+'_content').load(path+''+ page +'.php',			// Page to be loaded
		function()
		{							
			setTimeout(function() 
			{															
				retain_height = $('.'+page+'_content').height();								
				$('.content').animate(
				{												// Loading page Animation
					height: retain_height 						// Brings content to the right height	
				}, 800, function()
						{ 
							$('.'+page+'_content').animate(		// Content visualization
							{ 		
								opacity: 1,
								 height: 'toggle'
								}, 800, function()
										{
											prev_page = page;
											loading = false;											
										}); 
						}
				);
			},400);								
		});
												

	///////PAGES SWITCH///////	
	
	var proccess = false;
	$('ul#'+nav+' li a').click(function(e) 
	{
		if (proccess == false && loading == false && $(this).attr('href') != '#') 
		{
				page = $(this).attr('href');	
				e.stopPropagation();
				proccess = true;			
				var rights = session_checker();
				
		// If session expired					
				if ($('ul').attr('id') == 'nav_patient' && rights != 'is_patient' ||
					$('ul').attr('id') == 'nav_manager' && rights != 'is_manager' ||
					$('ul').attr('id') == 'nav_staff' && rights != 'is_doctor')
					{		
						session_expired();			// No security here,
						return false;				// security is added in the main files
					}	
						
		// Pages switch
					retain_height = $('.'+prev_page+'_content').height();
					$('.content').height(retain_height);
					$('.'+prev_page+'_content').animate(
					{
						opacity: 0,
						height: 'toggle',
						display: 'none'
					}, 600, function() 
							{
								$('.'+ prev_page +'_content').html("");
								$('.'+ page +'_content').load(path+''+ page +'.php', 
								function()
								{
									retain_height = $('.'+page+'_content').height();
									$('.content').animate(
									{
										height: retain_height
									}, 400, function()
											{
												$('.'+page+'_content').animate(
												{
													opacity: 1,
													height: 'toggle'
												}, 600, function()
														{
															prev_page = page;
															proccess = false;
														}); 
											}); 		
								});
							}
					);
			return false;			
		}
		else{return false;}	
	});
	
	
	/////LOGOUT/////
	
	$(".logout_button").click(
		function()
		{
			retain_height = $('.'+prev_page+'_content').height();
			$('.content').height(retain_height);
			$('.'+prev_page+'_content').animate(
			{
				opacity: 0,
				height: 'toggle',
				display: 'none'
			}, 800, function() 
					{
						$('.'+ prev_page +'_content').html(""); 
					}
			);	
			$.getScript("client_processes/general_functions/logout.js");
		});	
							
					
				
	/////DROP DOWN MENUS/////

    $('#nav li, #nav_patient li, #nav_staff li,  #nav_manager li').append('<div class="hover"></div>');
		
					
	$('ul#nav li, ul#nav_patient li, ul#nav_staff li, ul#nav_manager li').hover(
		function(e) 
		{
			e.stopPropagation();
			if ($(this).attr('class') != 'logout')
			{
				$(this).children('div').not('#notif_all_p, #notif_results_p').fadeIn(600);   
			}
			$('ul', this).animate(
			{
				opacity: 1,
				height: 'toggle',
			},'fast');
										 
		}, function() 
			{												
				$(this).children('div').not('#notif_all_p, #notif_results_p').fadeOut(600);  

				$('ul', this).animate(
				{
					opacity: 0,
					height: 'toggle',
				},'fast');
			}																					
	);
										
});
