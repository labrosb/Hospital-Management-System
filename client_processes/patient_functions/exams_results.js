// New examination results view section functions

$(function() 
{
	ajax_call();	// Creates first 3 tables on page load
	
	$('#more_results').click(function()		// If more results button is clicked
	{
		if ($(this).hasClass('yes'))		// Class yes implies that the button is functional
		{
			$('#more_results').html("<img id='loading_img2' src='styles/images/loading2.gif");
			
			ajax_call();					// Calls function to retrieve more exam results
		}
	});
	
	//  Creates tables of examinations with results
	
	function create_tables(response, items)
	{ 		
		var exams='';
		var tables = response.length;
		var more_results = true;
		
		if (response == "NO NEW EXAMS")			// If no new exam, show corresponding message 
		{
			more = '<p data-inter="noNewResults"> </p>';
			more_results = false;
		}else if (response == "NO MORE EXAMS")	// If no more exam, show corresponding message 
		{
			more = '<p data-inter="noMoreResults"> </p>';
			more_results = false;			
		}
		else{									// If new results found 
			var type = 'data-inter="type"';
			var doctor = 'data-inter="doctor"';
			var date = 'data-inter="date"';
			var time = 'data-inter="time"';
			var resultsTitle = 'data-inter="resultsTitle"';	
			
			for (i=0; i<tables; i++)			// Create tables with exams details
			{
				exams = exams + '<table class ="results_table"><tr id="title"><td '+type+'></td>'+
								'<td '+doctor+'></td><td '+date+'></td><td '+time+'></td></tr>'+
								'<tr id="res"><td data-inter='+response[i].Examination_data_inter+'></td>'+
								'<td>'+response[i].Doctor_name+" "+response[i].Doctor_surname+'</td>'+
								'<td>'+response[i].Date+'</td><td>'+response[i].Time+'</td></tr>'+
								'<tr id="title"><td colspan="4">Results:</td></tr>'+
								'<tr id="res"><td colspan="4">'+response[i].Results+'</td></tr></table></br></br>';							
			}
			
			if (tables == items)			// More results button
			{
				var more = '<p data-inter="more"></p>';
			}
			else{							// If all results are shown
				var more = '<p data-inter="noMoreResults"></p>';
				more_results = false;
			}
			
		}
		
		setTimeout(function()
		{
			$('#height_specify').html(exams);
			changeLang(defaultLang);
			var height = $('#intro').height();			
			var extra_height = $('#height_specify').height();	

			$('.content').animate(				//bring content to the right height	
			{		
				height: height + extra_height
			}, 400, function()
					{ 
						$('#results').append(exams);		// Visualizes the new results tables created
						$('#more_results').html(more);		// Updates more results button
							
						if (!more_results)					// If no more results exist
						{	
							$('#more_results').removeClass('yes').addClass('no');																	
						}									// Makes button non-functional
						else								// If more results exist
						{
							$('#more_results').addClass('yes');
						}									// Makes button functional
							changeLang(defaultLang);									
					});	
		},700);				
	}	
	
	function ajax_call()
	{	
		var items = 3;		// Number of additional results to appear per click
		$.ajax(
		{
			type: "POST",
			url: "server_processes/patient_functions/exams_results.php",
			dataType: "json",			
			success: function(response)
			{
				if(response == "EXPIRED")			// If session is expired
				{
					session_expired();				// Calls function to notify the user and logout
				}
				else{ 
					create_tables(response,items); 	// Calls function to create the exam results tables
				}
			}
		});		
	}	
	
});