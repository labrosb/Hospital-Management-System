	// Available doctors retrieval and presentation
	
	;(function($) 
	{ 
		// Popup contents	
		
		$('.available_doctors_choice').bind('click', function(e) 		// Click forms button to show available doctors
		{
			if(!document.getElementById('doctors_popup'))
			{
				$('#first_step').append("<div id='external_field_doc'><div id='doctors_popup'></div></div>");  
			}
			$('#external_field_doc').bPopup(							// Sets the popup
			{ 
				zIndex: 9998
				,content: 'ajax'
				,position:[450,'auto']
				,onOpen: function()
				{
					$.ajax(
					{
						type: "POST",
						url: "server_processes/patient_functions/load_doctors.php",
						data:{
							date: $('#date').val(),
							time: $('#time').val(),
							exams_type: $('#exams_type').val(),
							lang: defaultLang
							},
						dataType: "json",	  					
						success: function(response)					// Retrieves available doctors
						{											// Opens the popup showing loading animation
								
							$('#doctors_popup').html('<div class="button bClose"><span>X</span>'+
													'</div><div id="popup_loading"><img id="loading_img"'+ 
													'src="styles/images/loading_icon.gif" /></div>');
								
							create_lists(response);					// Calls function to create the doctors list						
						}					
					});	
				},onClose: function()	
				{   
					$('#doctors_popup').html('<div class="button bClose"><span>X</span>'+
											'</div><div id="popup_loading"><img id="loading_img"'+
											'src="styles/images/loading_icon.gif" /></div>');
												
					$('#doctors_popup').css({'width':'auto','height':'auto','marginLeft':'0','paddingRight':'0'});
					$('#external_field_doc').css({'width':'auto','height':'auto'});
					$('#all_list').show();
					$('#resume_content').hide();
					$('#resume_content').heigth('auto');
					$('#doctors_dimensions').empty();						
				},modalClose: true
					
			});	
				
		});
		
		//	Doctors list
		
		function create_lists(response)
		{    
			var counter = 0;
			var ul = ~~(response.length / 5 );
			var rest_li = response.length % 5 ;
			var doctors;
													// Errors handling : make popup with corresponding message
			if(response == "EXPIRED")				// If session has expired
			{
				doctors ="<div class='button bClose'><span>X</span></div> </br> <div id='all_lists'>"+ 
						 "<p data-inter='sessionExpired' id='null_available'>"+langPack['sessionExpired']+
						 "<br><span data-inter='examWarning2'> "+langPack['loginAgain']+"</span></p></div>";						
			}																	// (msg: Login again to continue...)
			else if (response == "EXAMS ERROR")		// If session has expired
			{
				doctors ="<div class='button bClose'><span>X</span></div> </br> <div id='all_lists'>"+
						 "<p data-inter='warningPopup1' id='null_available'>"+langPack['warningPopup1']+"</p></div>";
			}																	// (msg: Please choose valid examination category...)						
			else if (response == "DATE ERROR")		// If date is not set or is invalid
			{
				doctors ="<div class='button bClose'><span>X</span></div> </br> <div id='all_lists'>"+
						 "<p data-inter='warningPopup2' id='null_available'>"+langPack['warningPopup2']+"</p></div>";
			}																	// (msg: Please choose </br> date and time first...)
			else if (response == "NULL")			// If no doctor available
			{
				doctors ="<div class='button bClose'><span>X</span></div> </br> <div id='all_lists'>"+
						 "<p data-inter='warningPopup3' id='null_available'>"+langPack['warningPopup3']+"</p></div>";
			}																	// (msg: Sorry! There is no available doctor!
																								//Choose another date or time...)
			else if (response == "cannot connect to database")	// If connection error
			{
				doctors ="<div class='button bClose'><span>X</span></div> </br> <div id='all_lists'>"+ 
						 "<p data-inter='examWarning6' id='null_available'>"+langPack['examWarning6']+"</p></div>";			
			}																	// (msg: CONNECTION ERROR)
			else{
				doctors = "<div class='button bClose'><span>X</span></div> </br> <div id='results_content'>"+
						  "<table><tr><td id='profile'></td> <td id='lists'><div id='all_lists' style='width:182px;'>"+
						  "<p data-inter='availableDoctors' id='null_available'> "+langPack['availableDoctors']+":</p>";
																				// (msg: Available doctors)
																				
				for (i=0; i<ul; i++)							// Creates  available doctors list
				{
					doctors = doctors+"<table class='av_doctors_list'>"
					
					for (j=0; j<5; j++)							// Structs table with 5 doctors per row
					{ 
						doctors = doctors+"<tr><td>"+response[counter].Name+" "+response[counter].Surname+"</td></tr>";
						counter++;
					}
					doctors = doctors+"</table>";
				} 
				if (rest_li > 0)								// Remaining categories
				{
					doctors = doctors+"<table class='av_doctors_list'>"
					for (i=0; i<rest_li; i++)
					{
						doctors = doctors+"<tr><td onclick =$('#thisDoctor').html("+response[counter].Id+");>"+
								  response[counter].Name+" "+response[counter].Surname+"</td></tr>";
						counter++;
					}
					doctors = doctors+"</table>";	
				}	
				doctors = doctors+"</div><div id='resume_content'><div<td></tr></table><div>";	 //includes the info for the resume
			}
			
			$('#doctors_dimensions').html(doctors);
			var height = $('#doctors_dimensions').height();
			var width = $('#doctors_dimensions').width();
			var results_field_width = $('#doctors_dimensions table #all_lists').width();
			$('#doctors_dimensions table #all_lists').width(results_field_width);
			$('#doctors_popup').animate(
			{		
				width: width + 5 							//brings doctors_popup to the right width	
			}, 400, function()
					{ 
						$('#doctors_popup').animate({ 		//brings doctors_popup to the right height
							height: height
						}, 400, function()
								{ 
									$('#doctors_popup').html(doctors);
									$('#doctors_popup table #all_lists').width(results_field_width);
									popup_events();
								}); 
					});	
										
									
			// Doctor info popup
			
			function popup_events()
			{
				$('.av_doctors_list td').click(function()				// If a doctor is clicked
				{ 
					var name = $(this).text();							
					$('#doctor').val(name);								// Passes his name in the doctor's input
					$('#external_field_doc').bPopup().close();			// and close the popup
				});
						
				$('.av_doctors_list td').hover(function(event)			// Hovering on a doctor's name (shows doctor's info)
				{
					$('#doctors_popup').stop(false,false);
					
					var labelName = langPack['cardName'];				// Labels in multilanguage
					var labelSurname = langPack['cardSurname'];
					var labelAge = langPack['AgeLabel'];
					var labelSex= langPack['Sex'];					
					var labelSpeciality = langPack['specialtyLabel'];
					var labelBiog= langPack['moreInfoLabel'];
					
					var fullName = $(this).text();						// Re-structs name-surname
					var doctorsArray = fullName.split(" ");
					var doctorsArraySize = doctorsArray.length;

					var name;	
					var surname = doctorsArray[doctorsArraySize-1];	
					
					for (var i = 0; i <= doctorsArraySize-2; i++) 
					{
						if(name === undefined)
						{
							name = doctorsArray[i];
						}
						else{
							name = name+" "+doctorsArray[i];
						}	
					}
					
					for (var i = 0; i < response.length; i++) 			// Retrieves and saves doctors data
					{
						if( response[i].Name == name && response[i].Surname == surname )
						{
							var photo = response[i].Photo;
							var specialty = response[i].Specialty;
							var sex = response[i].Sex;
							var age = response[i].Age;
							var resume = response[i].resume;
							break;
						}
					}

					// Available doctors popup html
					
					$('#doctors_dimensions table tr #profile').html(
					"<div id='profile_content'> <p>Profile</p> <table> <tr> <td id='profile_photo_content' ROWSPAN = '7'>"+ 
					"<div id='photo_loading'><img id='loading_prof_img' src='styles/images/loading2.gif'/></div> </td"+
					"</tr> <tr> <td class ='prof_details'>"+labelName+": "+name+"</td> </tr> <tr> <td class ='prof_details'>"+
					labelSurname+" "+surname+"</td> <tr> <td class ='prof_details'>"+labelSex+": "+sex+"</td> </tr> <tr>"+
					"<td class ='prof_details'>"+labelAge+": "+age+"</td> </tr> <tr> <td class ='prof_details'>"+labelSpeciality+": "+specialty+
					"</td>	</tr> <tr>	<td <td class ='prof_details'><span class='resume_button'>More info</span></td> </tr>"+
					"</table> </div>");
					$('#photo_loading').show();
					
					var profile_width = $('#doctors_dimensions table tr #profile').width();	
					var new_width = $('#doctors_dimensions').width();
					var new_height = $('#doctors_dimensions').height();
					
					$('#doctors_popup').animate(				// Brings doctors_popup to the right width	
					{		
						width: new_width,
						marginLeft: profile_width - 2 * profile_width,
						height: new_height,
					}, 400, function()
							{ 									// Creates the doctor's info table
							
								$('table tr #profile').css({'border-right':'1px #566c71 solid'});
								
								$('#results_content table tr #profile').html(
								"<div id='profile_content'> <p>Profile</p> <table> <tr> <td id='profile_photo_content' ROWSPAN = '7'>"+
								"<div id='photo_loading'><img id='loading_prof_img' src='styles/images/loading2.gif'/></div> </td> </tr>"+
								"<tr> <td class ='prof_details'>"+labelName+": "+name+"</td> </tr> <tr> <td class ='prof_details'>"+labelSurname+
								": "+surname+"</td> <tr> <td class ='prof_details'>"+labelSex+": "+sex+"</td> </tr> <tr><td class ='prof_details'>"+
								labelAge+": "+age+"</td> </tr> <tr> <td class ='prof_details'>"+labelSpeciality+": "+specialty+"</td>	</tr> <tr>"+	
								"<td <td class ='prof_details'><span class='resume_button'>More info</span></td> </tr> </table> </div>");
								
								$('#results_content table tr #profile #profile_photo_content')
								.append("<div id='doc_photo_content'>"+
								"<img id='doc_photo' src='styles/images/profile_images/doctors/"+photo+".png' height='120' width='120' /></div>");												
													
								$('#doc_photo').attr('src', 'styles/images/profile_images/doctors/12345678.png')
								.load(function() 
								{  
									$('#results_content table tr #profile #profile_photo_content #photo_loading').hide();													
									$('#results_content table tr #profile #profile_photo_content #doc_photo_content').fadeIn();
								});
													
								$('#results_content table tr .resume_button')	// If more info is clicked	
								.click(function(e)
								{ 	
									e.stopPropagation();
									var popup_width = $('#doctors_popup').width();
									var lists_td_width = $('#results_content table tr #lists').width();
									
									$('#results_content table tr #lists').width(lists_td_width);													
									$('#results_content table tr td #all_lists').fadeOut();	
									$('#results_content table tr td #resume_content').html(
									"<img id='back_to_list' src='styles/images/grey_arrow_left.png' height='35' width='45' />"+
									"<p>More info</p>"+resume);					// Includes resume
									
									$('#doctors_popup').animate(				// Brings doctors_popup to the right width	
									{		
										height: 300,
										paddingRight: 250
									}, 400, function()
											{ 									// Increases popup content
												$('#results_content table tr td #resume_content').width(lists_td_width+200);
												$('#results_content table tr td #resume_content').height(280);
												$('#results_content table tr td #resume_content').slideDown();
												arrowEvent();
											}	
									);	
														
								});	
													
								function arrowEvent() 						// If back arrow is clicked		
								{	
									$('#results_content table tr td div #back_to_list')
									.click(function(e){ 
										e.stopPropagation();
										
										var original_height = $('#doctors_dimensions').height();
										$('#results_content table tr td #resume_content').slideUp();														
										$('#doctors_popup').animate(		// Brings doctors_popup to the right width	
										{		
											height: original_height,
											paddingRight: 0
										}, 400, function()
												{ 
													$('#results_content table tr td #all_lists').fadeIn();	
													$('#results_content table tr td #resume_content').height('auto');
												}	
										);																
									});
								}	

												
							}
						);
					
				});
				
			}
		}

	})(jQuery);