$(document).ready(function(){	
//ελεγχος sessions
var loading = true;

	var rights = $.ajax({
					type: "POST",
					async: false,
					global: false,
					url: "server_processes/session_checker.php",
					success: function(response){
								return response;
							}
				}).responseText;
//δημιουργία αντιστοιχου content

	if (rights == 'false'){
		var page = 'index';
		var nav = 'nav';
		var path = 'content/';
	}else if (rights == 'asth'){
		var page = 'news';
		var nav = 'nav_patient';
		var path = 'content/patient/';
	}else if (rights == 'staff'){
		var page = 'news';
		var nav = 'nav_staff';
		var path = 'content/staff/';		
	}else if (rights == 'manager'){
		var page = 'news';
		var nav = 'nav_manager';
		var path = 'content/manager/';			
	}	
	var retain_height;	
	$('.'+page+'_content').load(path+''+ page +'.php'
						,function(){
							setTimeout(function() {

								retain_height = $('.'+page+'_content').height();
								//$('.content').height(retain_height);
								$('.content').animate({		//Φέρνει το content στο σωστο height	
												height: retain_height 
											}, 800, function(){ 
														$('.'+page+'_content').animate({ //εμφάνιση περιεχομενου
																				 opacity: 1,
																				 height: 'toggle'
																			 }, 800, function(){
																						prev_page = page;
																						loading = false;											
																				}); 
															});
								},400);
								
							}
						
						);
							
						

///////ΕΝΑΛΛΑΓΗ ΣΕΛΙΔΩΝ///////	
	var proccess = false;
	$('ul#'+nav+' li a').click(function(e) {
		if (proccess == false && loading == false && $(this).attr('href') != '#') {
				page = $(this).attr('href');	
				e.stopPropagation();
				proccess = true;
				var rights = $.ajax({
								type: "POST",
								async: false,
								global: false,
								url: "server_processes/session_checker.php",
								success: function(response){
											return response;
										}
							}).responseText;
		//αν ληξει το session					
				if ($('ul').attr('id') == 'nav_patient' && rights != 'asth' ||
					$('ul').attr('id') == 'nav_manager' && rights != 'manager' ||
					$('ul').attr('id') == 'nav_staff' && rights != 'staff'){		
					$.getScript("client_processes/logout.js");
				return false;
				}	
						
		//εναλλαγη σελιδων
					retain_height = $('.'+prev_page+'_content').height();
					$('.content').height(retain_height);
					$('.'+prev_page+'_content').animate({
						opacity: 0,
						height: 'toggle',
						display: 'none'
					}, 600, function() {
								$('.'+ prev_page +'_content').html("");
								$('.'+ page +'_content').load(path+''+ page +'.php', function(){
																							retain_height = $('.'+page+'_content').height();
																							$('.content').animate({
																								height: retain_height
																							}, 400, function(){
																							$('.'+page+'_content').animate({
																								opacity: 1,
																								height: 'toggle'
																							}, 600, function(){
																										prev_page = page;
																										proccess = false;
																									}); 
																							}); 		
																					});
						}
					);
				return false;			
		}else{return false;}	
	});
	
	
/////LOGOUT/////
	$(".logout_button").click(function(){
								retain_height = $('.'+prev_page+'_content').height();
								$('.content').height(retain_height);
								$('.'+prev_page+'_content').animate({
									opacity: 0,
									height: 'toggle',
									display: 'none'
								}, 800, function() {
											$('.'+ prev_page +'_content').html(""); 
								});	
											$.getScript("client_processes/logout.js");

							});	
							
					
				
/////DROP DOWN MENUS/////

    $('#nav li, #nav_patient li, #nav_staff li,  #nav_manager li').append('<div class="hover"></div>');
		
					
	$('ul#nav li, ul#nav_patient li, ul#nav_staff li, ul#nav_manager li').hover(
											function(e) {
												e.stopPropagation();
												if ($(this).attr('class') != 'logout'){
													$(this).children('div').not('#notif_all_p, #notif_results_p').fadeIn(600);   
												}
												$('ul', this).animate({
																opacity: 1,
																height: 'toggle',
																},'fast');
										 
											}, 
											function() {												
												$(this).children('div').not('#notif_all_p, #notif_results_p').fadeOut(600);  

												$('ul', this).animate({
																opacity: 0,
																height: 'toggle',
																},'fast');
											}
											

											
										);
										
									
});