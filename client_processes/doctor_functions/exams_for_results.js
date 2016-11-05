$(function() 
{
	var previous = ajax_call();					// On page load, loads a number of new exams and returns
												// the number of the result that the next call will start from
												
	$('#more_results').click(function()			// Clicking on the more results button
	{
		if ($(this).hasClass('yes'))			// If button is functional
		{
			$('#more_results').html("<img id='loading_img2' src='styles/images/loading2.gif'/>");
												// Adds loading animation
			previous = ajax_call(previous);		// Calls function to retrieve more exams
		}										
	});	

	$('.submit_result').click(function()			// Clicking on the submit button
	{
		var tableId = $('.submit_result').val();	
		var text = $('#res_form textarea').val();	
		
		if(text != "")								// If diagnosis input is not empty
		{
			insert_results(tableId, text);			// Calls function to send the diagnosis to the server		
		}
		else{
			return false;
		}
	});
	
	// Creates examination tables
	
	function create_tables(response, items)
	{ 	
		var exams='';
		var tables = response.length;
		var more_results = true;
		
		if(response == "EXPIRED")						// If session is expired
		{
			session_expired();							// Calls function to inform user and logout
		}
		else if (response == "NO NEW EXAMS")			// If there are no new exams available for diagnosis
		{
			more = '<p data-inter="noNewExams"> </p>';	// Show corresponding message
			more_results = false;						// Dectivate button
		}
		else if (response == "NO MORE EXAMS")			// If some results shown before but no more are available
		{
			more = '<p data-inter="noMoreExams"> </p>';	// Show corresponding message
			more_results = false;						// Dectivate button		
		}
		else{											// If new resuls found
			var type = 'data-inter="type"';
			var patient = 'data-inter="patient"';
			var date = 'data-inter="date"';
			var time = 'data-inter="time"';
			var resultsTitle = 'data-inter="resultsTitle"';
			var resultsMsg = 'data-inter="resultsMsg"';		
			
			for (i=0; i<tables; i++)						// Creates tables
			{												// Generated HTML content
				exams = exams + '<table id="'+response[i].Exam_id+'" class ="results_table hovTable">'+
								'<tr id="title"><td '+type+'> </td><td '+patient+'></td><td '+date+'></td>'+
								'<td '+time+'> </td></tr> <tr id="res"><td data-inter='+response[i].data_inter+'></td>'+
								'<td>'+response[i].patient_name+" "+response[i].patient_surname+'</td>'+
								'<td>'+response[i].Date+'</td> <td>'+response[i].Time+'</td> </tr>'+
								'<tr id="title"><td colspan="4" '+resultsTitle+'> </td></tr>'+
								'<tr id="res"><td colspan="4" '+resultsMsg+'> </i></td></tr></table>';							
			}
			if (tables == items)							// If tables created are the same as the results requested
			{
				var more = '<p data-inter="more"> </p>';	// Label button as 'more'
			}
			else											// else
			{
				var more = '<p data-inter="noMoreExams"> </p>';	// Label button as 'No more exams'
				more_results = false;						// Deactivate button
			}
			
		}
		tableEvents();										// Calls function to enable clicking events
															// for the new gererated tables
		setTimeout(function()								// Creates the content
		{
			$('#height_specify').html(exams);
			changeLang(defaultLang);
			var height = $('#intro').height();			
			var extra_height = $('#height_specify').height();	

			$('.content').animate({								// Brings content to the right height	
							height: height + extra_height
						}, 400, function()
								{ 									
									$('#results').append(exams);	// Adds the new tables to the existing content 
									$('#more_results').html(more);	// Re-constructs the bottom button
										
									if (!more_results)				// If no more results available
									{
										$('#more_results').removeClass('yes').addClass('no');
										$('#more_results').html('<p data-inter="noMoreExams"></p>');
									}
									else{
										$('#more_results').addClass('yes');
										$('#more_results').html('<p data-inter="more"></p>');
									}
									changeLang(defaultLang);									
								}
			);	
		},700);		

	}

	// Function retrieves examinations for diagnosis
	
	function ajax_call(limit1)
	{	
		var items = 3;
		
		if (limit1 === undefined) 	// If number of first selecter result has not been set
		{
			var limit1 = 0;			// Sets it to zero (starts from the first result)
		}
		$.ajax({
			type: "POST",
			url: "server_processes/doctor_functions/exams_for_results.php",
			dataType: "json",	
			data:{
				  limit1: limit1,	// First result to be retrieved
				  limit2: items		// Last result to be retrieved
				 },				
			success: function(response){create_tables(response,items);}
		});	
		
		limit1 = limit1 + items;	// Updates the var of the first result to be retrieved
									// for the next function call
		return limit1;				// Returns the var
	}	
	
	// Function enables the on click event for new elements
	
	function tableEvents()
	{
		$(".results_table").live('click', function()
		{			
			var tableId = $(this).attr("id");
			$('.submit_result').val(tableId);		// Sets the exam id to the submit button's value
			
			$('#results_input').bPopup(
			{
				 zIndex: 9998
				,onOpen: function(){}
				,onClose: function()				// On popup's close, clears the diagnosis form
				{   
						$('#res_form textarea').val("");
						$('.submit_result').val("");
				},modalClose: true			
			});
					
		});
	}		
		
	// Function sends the diagnosis to server
	
	function insert_results(tableId, text)
	{
		$.ajax({
			type: "POST",
			url: "server_processes/doctor_functions/exam_results_insertion.php",
			data:{
				tableId: tableId,	// Exam id
				text: text			// Diagnosis
				},		
			success: function(response)
			{
				if(response == "EXPIRED")					// If session is expired
				{
					$('#results_input').bPopup().close();	
					session_expired();						// Calls function to alert user and logout
				}
				else if (response == "OK")
				{
					$('#results_input').bPopup().close();							// Closes popup 
					$('#center_table #results table#'+tableId).fadeOut('slow');		// Hides the exam					
				}
			}
		});
	}		
});