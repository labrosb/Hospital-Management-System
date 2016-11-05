// Exams history functions

$(document).ready(function() 
{	
	var previous = ajax_call();				// On page load, loads a number of new exams and returns
											// the number of the result that the next call will start from
	categories();
	
	$('#exams').focus(function()			// On exams filter focus
	{										// Calls function to retrieve exam categories
		$('#exams_checkboxes').fadeIn();	// Show exam choices checklist
	});

	$('#exams_checkboxes , #exams').click(function(event)
	{
		event.stopPropagation();
	});

	$('html').click(function() 				// Clicking elsewhere but the checklists field
	{
		$('#exams_checkboxes').fadeOut();	// Hide checklists field
	});
	
	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' input[type=password]');
	
	inputFields.each(function()
	{		
		$(this).focus(function() 						// On focus
		{
			if($(this).val() == langPack[this.id]) 		// If current value is the default one
			{											
				$(this).val("");						// Sets it to blank
			}
		});
		
		$(this).focusout(function() 					// On focus out
		{
			if($(this).val() == "") 					// Reverses the above action
			{
				$(this).val(langPack[this.id]);
			}
		});	
	
	});
												
    $('form').submit(function(){ return false; });
	
    $('#submit_filter').click(function()				// Filter ok button click
	{
        var fields = $('#filter input[type=text]');
        var checkboxFields = $('#filter input[type=checkbox]');
        var error = 0;
		var fromDate = $('#from').val();
		var fromDateValidation = isValidDate(fromDate);  
		var toDate = $('#to').val();
		var toDateValidation = isValidDate(toDate);  
		var currentDateNum = structCurrentDate();
		var fromDateNum = datesNum(fromDate);
		var toDateNum = datesNum(toDate);
		
		if( fromDate != 'From' && toDate != langPack['to'] && 		// If start date > end date
			fromDateNum > toDateNum )
		{
			$("#filter_error_msg").html(langPack['formError16']); 	// (msg: Invalid dates)
			$("#filter_loading").fadeTo('slow', 0);					// Loading animation hide
			$("#filter_error_msg").fadeTo('slow', 1);				// Error message show
			error++;
		}
		
        fields.each(function()
		{
			var value = $(this).val();	
			var field_id = $(this).attr('id');		
			
			if ( field_id == 'from' && value!=langPack['from'] && 		// If start date or end date is not valid
				fromDateValidation == false || field_id == 'to' && 
				value!=langPack['to'] && toDateValidation == false )
			{ 			    
				$("#filter_error_msg").html(langPack['formError17']); 	// (msg: Invalid date)
				$("#filter_loading").fadeTo('slow', 0);					// Loading animation hide
				$("#filter_error_msg").fadeTo('slow', 1);				// Error message show
                error++;
			}
			else if ( field_id == 'from' && value!=langPack['from'] &&	// it start date > current date
					 fromDateNum > currentDateNum )
			{ 			    
				$("#filter_error_msg").html(langPack['formError18']); 	// (msg: Invalid start date)
				$("#filter_loading").fadeTo('slow', 0); 				// Loading animation hide
				$("#filter_error_msg").fadeTo('slow', 1);				// Error message show
                error++;
			}
			else if ( field_id == 'to' && value!=langPack['to'] && 		// it end date < current date
					 toDateNum > currentDateNum )
			{ 			    
				$("#filter_error_msg").html(langPack['formError19']); 	// (msg: Invalid end date)
				$("#filter_loading").fadeTo('slow', 0); 				// Loading animation hide
				$("#filter_error_msg").fadeTo('slow', 1);				// Error message show
                error++;
			}
        });  
		var cnt = 0;
		var exams_list = new Array();
		
		checkboxFields.each(function()
		{
			value = $(this).val();
			
			if($(this).is(":checked"))				// If checkbox is checked
			{
				exams_list[cnt] = $(this).val();		// Saves choice to variable							
				cnt++;
			}
			
		});
		
        if(!error) 
		{
			$('#more_results').unbind('click');
			$('#more_results').removeClass('unfiltered').addClass('fltered');	// Sets filtered value to more results button
			$("#filter_error_msg").fadeTo('slow', 0);							// Hide error messages
			$("#filter_loading").fadeTo('slow', 1);								// Show loading animation
			
			previous = ajax_call(0, false, exams_list, fromDate, toDate);		// Calls function to retrieve filtered results
			
			$('#more_results').click(function()							// More results button for already filtered resultes									
			{
				if ($(this).hasClass('yes'))							// If button is functional
				{
					$('#more_results').html("<img id='loading_img2'src='styles/images/loading2.gif' height='30' width='30' />");
					
					previous = ajax_call(previous, true, exams_list, fromDate, toDate);	// Calls function to recieve more results
				}
			});	

        }              
	
	});
		
	$('#more_results').click(function()									// More results button click
	{
		if ($(this).hasClass('yes') && $(this).hasClass('unfiltered'))	// If is set as button for unfiltered results
		{
			$('#more_results').html("<img id='loading_img2' src='styles/images/loading2.gif' height='30' width='30' />");
			
			previous = ajax_call(previous, true);						// Call funtion to retrieve more results
		}
	});	

	$('#from').datepicker({dateFormat:'dd/mm/yy', showAnim:'fadeIn'});	// Date calendars
	$('#to').datepicker({dateFormat:'dd/mm/yy', showAnim:'fadeIn'});
	
});

//// Functions ////

function structCurrentDate()			// Current date creation
{
	var current = new Date();
	var now = new Array();		
	
	now['Day'] = current.getDate();
	now['Month'] = current.getMonth() + 1; 
	now['Year']  = current.getFullYear();
	
	if(now['Day'] < 10)
	{
		now['Day'] = "0"+now['Day'];
	}	
	
	if(now['Month'] < 10)
	{
		now['Month'] = "0"+now['Month'];
	}		
	
	var currentDateNum = now['Year']+""+now['Month']+""+now['Day'];
		
	return currentDateNum;
}
	
function datesNum(date)				// Returns date in integer form
{		
	var dateArray = date.split("/");		
	var dateNum = dateArray[2]+""+ dateArray[1]+""+ dateArray[0];
		
	return dateNum;
}
	
// Creates exam choices	checklist
	
function create_choices(response)	
{
	var counter = 0;
	var ul = ~~(response.length / 5 );
	var rest_li = response.length % 5 ;
	exams = '';
	
	for (i=0; i<ul; i++)
	{		
		exams = exams+"<table class='choices_list'>"
		
		for (j=0; i<5; i++)
		{
			exams = exams+"<tr><td data-inter="+response[counter]['data-inter']+">"+response[counter][defaultLang]+
						  "</td><td><input type='checkbox' class='exams_checkbox'"+
						  "name='"+response[counter].en+"' value='"+response[counter].id+"'>"+
						  "</td></tr>";
			counter++;			
		}
		exams = exams+"</table>";
	} 
	
	if (rest_li > 0)
	{
		exams = exams+"<table class='choices_list'>"
		
		for (i=0; i<rest_li; i++)
		{
			exams = exams+"<tr><td data-inter="+response[counter]['data-inter']+">"+response[counter][defaultLang]+"</td>"+
						  "<td><input type='checkbox' class='exams_checkbox'"+
						  "name='"+response[counter].en+"' value='"+response[counter].id+"'></td></tr>";
			counter++;
		}
		exams = exams+"</table>";												
	}
	exams = exams+"</div>";			

	$('#exams_checkboxes').html(exams);														
}	

// Retrieves examination categories

function categories()
{
	$.ajax({
		type: "POST",
		url: "server_processes/general_functions/exams_specialities_units.php",
		data: {"get_exam_types": null, "lang": defaultLang},
		dataType: "json",	
		async: false,
		success: function(response)
		{ 
			create_choices(response); 	// Calls funtion to create choices checklist table
		}					
	});	
}			
	
// Creates examination results tables

function create_tables(response, items, moreBool)
{ 
	var exams='';
	var tables = response.length;
	var more_results = true;
	
	if (response == "NO NEW EXAMS")						// If no new exams found
	{
		more = '<p data-inter="noNewResults"> </p>';	// Sets multilanguage attribute
		more_results = false;							// Sets false to prevent further 'more' button usage
	}
	else if (response == "NO MORE EXAMS")				// If no new exams found
	{
		more = '<p data-inter="noMoreResults"> </p>';	// Sets multilanguage attribute
		more_results = false;							// Sets false to prevent further 'more' button usage		
	}
	else
	{
		var type = 'data-inter="type"';
		var doctor = 'data-inter="doctor"';
		var date = 'data-inter="date"';
		var time = 'data-inter="time"';
		var resultsTitle = 'data-inter="resultsTitle"';
		
		for (i=0; i<tables; i++)						// Creates results tables
		{
			exams = exams + '<table class ="results_table"><tr id="title"><td '+type+' ></td>'+
							'<td '+doctor+'></td><td '+date+'></td><td '+time+'></td></tr>'+
							'<tr id="res"><td data-inter='+response[i].Examination_data_inter+'>'+
							'</td><td>'+response[i].Doctor_name+" "+response[i].Doctor_surname+
							'</td><td>'+response[i].Date+'</td><td>'+response[i].Time+'</td></tr>'+
							'<tr id="title"><td '+resultsTitle+' colspan="4">Results:</td></tr>'+
							'<tr id="res"><td colspan="4"> <div id="h_results"> '+response[i].Results+'</div>'+
							'</td></tr></table></br></br>';							
		}
		
		if (tables == items)									// If number of items equals to the max item to be shown
		{
			var more = '<p data-inter="more"></p>';				// Set more multilanguage value to 'more'
		}	
		else													// else
		{
			var more = '<p data-inter="noMoreResults"></p>';	// Set more multilanguage value to 'no more results' 
			more_results = false;								// Sets variable to false to prevent further actions
		}
	}
		
	setTimeout(function()
	{
		if (moreBool === true)						// If more boolean is true
		{
			$('#height_specify').append(exams);		// append new exams to the current results
		}
		else										// If more boolean is NOT true
		{										
			$('#height_specify').html(exams);		// Clears content and shows the results	
		}
		changeLang(defaultLang);
			
		var extra_height = $('#height_specify').height();	

		$('.content').animate({						//brings content to the right height	
						height: 240 + extra_height
					}, 400);		
		$('#results').animate({						//brings results to the right height	
						height: extra_height
					}, 400, function()
							{ 	
								if (moreBool === true)				// If more boolean is true
								{
									$('#results').append(exams);		
								}else{
									$('#results').html(exams);	
								}
								if (!more_results){
									$('#more_results').removeClass('yes').addClass('no');
									$('#more_results').html('<p data-inter="noMoreResults"></p>');									
								}else
								{
									$('#more_results').addClass('yes');
									$('#more_results').html('<p data-inter="more"></p>');
								}			
								changeLang(defaultLang);
							}
		);	
	},700);	
}
	
function ajax_call(limit1, more, exams_list, fromDate, toDate)
{	
	var items = 3;
	
	if (limit1 === undefined) 			// If limit is not set
	{
		var limit1 = 0;					// Sets it to 0
	}

	if (exams_list === undefined)		// If filter isnt defined
	{
		var exams_list = 'default';		// Sets all values to 'default'
		var fromDate = 'default';
		var toDate = 'default';
	}
	
	if (fromDate == langPack['from']) 	// If filter values have the default value
	{	
		fromDate = 'default';			// Sets them to 'default'	
	}
	
	if (toDate == langPack['to']) 
	{	
		toDate = 'default';
	}
	
	$.ajax({
		type: "POST",
		url: "server_processes/patient_functions/exams_history.php",
		dataType: "json",	
		data:{
			exams_types: exams_list,
			from: fromDate,
			to: toDate,
			limit1: limit1,		// First result to be retrieved
			limit2: items		// Last result to be retrieved
			 },			
		success: function(response)								// Retrieves examination results
		{ 
					if(response == "EXPIRED")					// If session is expired
					{
						session_expired();						// Calls function to notify user and logout
					}
					else {
						create_tables(response, items, more);	// Calls function to create results tables  
						
						$("#filter_loading").fadeTo('slow', 0);	// Hides loading animation
					}
				}
	});	
	
	limit1 = limit1 + items;		//Increases limit1 (corresponds to the first new result to be shown on the next function call)
	
	return limit1;
}		