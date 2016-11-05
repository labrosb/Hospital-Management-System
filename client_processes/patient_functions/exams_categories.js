// Examination categories retrieval and presentation

;(function($) 
{
	$('.exams_type_choice').bind('click', function(e) 						// Clicking the exam types button
	{
		if(!document.getElementById('external_field_exams'))				
		{
			$('#first_step').append("<div id='external_field_exams'>"+		// Creates exams popup field
									"<div id='exams_popup'></div></div>");  
		}
			
		$('#external_field_exams').bPopup(									// Exams popup
		{ 
			position:[450,'auto']
			,onOpen: function()
			{					
				$.ajax(
				{
					type: "POST",
					url: "server_processes/general_functions/exams_specialities_units.php",
					data: {"get_exam_types": null, "lang": defaultLang},
					dataType: "json",	  					
					success: function(response)								// Retrieves exam categories
					{						
						$('#exams_popup').html('<div class="button bClose"><span>X</span>'+
												'</div><div id="popup_loading"><img id="loading_img"'+
												'src="styles/images/loading_icon.gif"/></div>');
						
						create_lists(response);								// Calls function to 
					}					
				});	

			},onClose: function()
			{ 
				$('#exams_popup').html('<div class="button bClose"><span>X</span></div>'+
										'<div id="popup_loading"><img id="loading_img"'+
										'src="styles/images/loading_icon.gif" /></div>');
											
				$('#exams_popup').html('<div class="button bClose"><span>X</span></div>'+
										'<div id="popup_loading"><img id="loading_img"'+
										'src="styles/images/loading_icon.gif" /></div>');
											
				$('#exams_popup').css({'width':'auto','height':'auto'});
				$('#external_field_exams').css({'width':'auto','height':'auto'});												
				$('#exams_dimensions').empty();
			}
		});					
	});
		
	function create_lists(response)			// Structs the examination lists in table form
	{
		var counter = 0;
		var ul = ~~(response.length / 5 );
		var rest_li = response.length % 5 ;
				exams = "<div class='button bClose'><span>X</span></div> </br> <table>"+
					    "<tr><td> <div id='all_lists'> <p>"+langPack['selectCategory']+"</p>";
		
		for (i=0; i<ul; i++)
		{
			exams = exams+"<table class='exams_list'>"
			
			for (j=0; j<5; j++)				// Structs table with 5 categories per row
			{
				exams = exams+"<tr><td>"+response[counter][defaultLang]+"</td</tr>";
				counter++;			
			}
			exams = exams+"</table>";
		} 
		
		if (rest_li > 0)					// Remaining categories
		{
			exams = exams+"<table class='exams_list'>"
			for (i=0; i<rest_li; i++)
			{
				exams = exams+"<tr><td>"+response[counter][defaultLang]+"</td</tr>";
				counter++;
			}
			exams = exams+"</table>";												
		}
		
		exams = exams+"</div></td></tr></table>";			

		$('#exams_dimensions').html(exams);
		var height = $('#exams_dimensions').height();
		var width = $('#exams_dimensions').width();

		$('#exams_popup').animate(					//brings exams_popup to the right width	
		{		
			width: width + 5
		}, 400, function()
				{ 
					$('#exams_popup').animate(		//brings exams_popup to the right height
					{ 		
						height: height
					}, 400, function()
							{ 
								$('#exams_popup').html(exams);
								popup_events();
							}); 
																	
				});	
														
	}
	
	function popup_events()
	{			
		$('.exams_list td').click(function()				// If a choice is clicked
		{
			var name = $(this).text();		
			$('#exams_type').val(name);						// Fill the exams field with the choice clicked
			
			$('#external_field_exams').bPopup().close(); 	// Closes popup
		});
	}
 				
})(jQuery);