	function structCurrentDate(){
		var current = new Date();
		var now = new Array();				
		now['Day'] = current.getDate();
		now['Month'] = current.getMonth() + 1; 
		now['Year']  = current.getFullYear();
		if(now['Day'] < 10){
			now['Day'] = "0"+now['Day'];
		}
		if(now['Month'] < 10){
			now['Month'] = "0"+now['Month'];
		}	
		
		var currentDateNum = now['Year']+""+now['Month']+""+now['Day'];
		
		return currentDateNum;
	}
	
	function datesNum(date)
	{		
		var dateArray = date.split("/");		
		var dateNum = dateArray[2]+""+ dateArray[1]+""+ dateArray[0];
		
		return dateNum;
	}


function isValidDate(date) {
	var valid = true;

	var arrayDate = date.split('/');

	var day = arrayDate[0];
	var month   = arrayDate[1];
	var year  = arrayDate[2];
	var other = arrayDate[3];
	
	if (typeof other != 'undefined'){ valid = false;}
	else if ((isNaN(day)) || (isNaN(month)) || (isNaN(year))) valid = false;
	else if((month < 1) || (month > 12)) valid = false;
	else if((day < 1) || (day > 31)) valid = false;
	else if(((month == 4) || (month == 6) || (month == 9) || (month == 11)) && (day > 30)) valid = false;
	else if((month == 2) && (((year % 400) == 0) || ((year % 4) == 0)) && ((year % 100) != 0) && (day > 29)) valid = false;
	else if((month == 2) && ((year % 100) == 0) && (day > 29)) valid = false;

	return valid;
}	
	
	
function struck_choices(response){
	var counter = 0;
	var ul = ~~(response.length / 5 );
	var rest_li = response.length % 5 ;
	exams = '';
	for (i=0; i<ul; i++){
		exams = exams+"<table class='choices_list'>"
		for (j=0; i<5; i++){
			exams = exams+"<tr><td>"+response[counter].Name+"</td><td><input type='checkbox' class='exams_checkbox' name='"+response[counter].Name+"' value='"+response[counter].Id+"'></td></tr>";
			counter++;			
		}
		exams = exams+"</table>";
	} 
	if (rest_li > 0){
		exams = exams+"<table class='choices_list'>"
		for (i=0; i<rest_li; i++){
			exams = exams+"<tr><td>"+response[counter].Name+"</td><td><input type='checkbox' class='exams_checkbox' name='"+response[counter].Name+"' value='"+response[counter].Id+"'></td></tr>";
			counter++;
		}
		exams = exams+"</table>";												
	}
	exams = exams+"</div>";			

	$('#exams_checkboxes').html(exams);
														
}	


function categories(){
	$.ajax({
		type: "POST",
		url: "server_processes/load_categories.php",
		dataType: "json",	  					
		success: function(response){ struck_choices(response); }					
	});	
}			
	
	
function struck_tables(response, items, moreBool){ 

	var exams='';
	var tables = response.length;
	var more_results = true;
	if (response == "NO NEW EXAMS"){
		more = '<p data-inter="noNewResults"> </p>';
		more_results = false;
	}else if (response == "NO MORE EXAMS"){
		more = '<p data-inter="noMoreResults"> </p>';
		more_results = false;			
	}else{
		var type = 'data-inter="type"';
		var doctor = 'data-inter="doctor"';
		var date = 'data-inter="date"';
		var time = 'data-inter="time"';
		var resultsTitle = 'data-inter="resultsTitle"';
		for (i=0; i<tables; i++){
			exams = exams + '<table class ="results_table"><tr id="title"><td '+type+' ></td><td '+doctor+'></td><td '+date+'></td><td '+time+'></td></tr>';
			exams = exams + '<tr id="res"><td>'+response[i].Examination_name+'</td><td>'+response[i].Doctor_name+" "+response[i].Doctor_surname+'</td><td>'+response[i].Date+'</td><td>'+response[i].Time+'</td></tr>';
			exams = exams + '<tr id="title"><td '+resultsTitle+' colspan="4">Results:</td></tr>';
			exams = exams + '<tr id="res"><td colspan="4"> <div id="h_results"> '+response[i].Results+'</div></td></tr></table></br></br>';							
		}
		if (tables == items){
			var more = '<p data-inter="more"></p>';
		}else{
			var more = '<p data-inter="noMoreResults"></p>'
			more_results = false;
		}
	}
		
	setTimeout(function(){
		if (moreBool === true){
			$('#height_specify').append(exams);
		}else{
			$('#height_specify').html(exams);	
		}
		changeLang(defaultLang);
			
		var extra_height = $('#height_specify').height();	

		$('.content').animate({		//brings content to the right height	
						height: 240 + extra_height
					}, 400);		
		$('#results').animate({		//brings results to the right height	
						height: extra_height
					}, 400, function(){ 
								if (moreBool === true){
									$('#results').append(exams);
								}else{
									$('#results').html(exams);	
								}
								if (!more_results){
									$('#more_results').removeClass('yes').addClass('no');
									$('#more_results').html('<p data-inter="noMoreResults"></p>');									
								}else{
									$('#more_results').addClass('yes');
									$('#more_results').html('<p data-inter="more"></p>');
								}			
								changeLang(defaultLang);
						});	
	},700);	

}
	
	
function ajax_call(limit1, more, exams_list, fromDate, toDate){	
	var items = 3;
	if (limit1 === undefined) {
		var limit1 = 0;
	}
	if (exams_list === undefined) {
		var exams_list = 'default';
		var fromDate = 'default';
		var toDate = 'default';
	}
	$.ajax({
		type: "POST",
		url: "server_processes/exams_history.php",
		dataType: "json",	
		data:{
			  exams_types: exams_list,
			  from: fromDate,
			  to: toDate,
			  limit1: limit1,
			  limit2: items
			 },			
		success: function(response){ 
					struck_tables(response, items, more);  
					$("#filter_loading").fadeTo('slow', 0);
				}
	});	
	limit1 = limit1 + items;
	return limit1;
}		
	
////////////Functions end/////////////


$(document).ready(function() {

	categories();
	
	$('#exams').focus(function(){
		$('#exams_checkboxes').fadeIn();
	});

	$('#exams_checkboxes , #exams').click(function(event){
		event.stopPropagation();
	});

	$('html').click(function() {
		$('#exams_checkboxes').fadeOut();
	});
	
	var formId = document.forms[0].id;
	var inputFields = $('#'+formId+' input[type=text],#'+formId+' input[type=password]');
	
	inputFields.each(function(){	
	
		$(this).focus(function() {
			if($(this).val() == langPack[this.id]) {
				$(this).val("");
			}
		});
		
		$(this).focusout(function() {
			if($(this).val() == "") {
				$(this).val(langPack[this.id]);
			}
		});	
	
	});
 
    $('form').submit(function(){ return false; });
    $('#submit_filter').click(function(){
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
		
		if( fromDate != 'From' && toDate != langPack['to'] && fromDateNum > toDateNum ){
			$("#filter_error_msg").html(langPack['formError16']);
			$("#filter_loading").fadeTo('slow', 0);
			$("#filter_error_msg").fadeTo('slow', 1);
			error++;
		}
		
        fields.each(function(){
			var value = $(this).val();		
			if ($(this).attr('id') == 'from' && value!=langPack['from'] && fromDateValidation == false  ||
				$(this).attr('id') == 'to' && value!=langPack['to'] && toDateValidation == false){ 			    
				$("#filter_error_msg").html(langPack['formError17']);
				$("#filter_loading").fadeTo('slow', 0);
				$("#filter_error_msg").fadeTo('slow', 1);
                error++;
			}else if ($(this).attr('id') == 'from' && value!=langPack['from'] && fromDateNum > currentDateNum ){ 			    
				$("#filter_error_msg").html(langPack['formError18']);
				$("#filter_loading").fadeTo('slow', 0);
				$("#filter_error_msg").fadeTo('slow', 1);
                error++;
			}else if ($(this).attr('id') == 'to' && value!=langPack['to'] && toDateNum > currentDateNum ){ 			    
				$("#filter_error_msg").html(langPack['formError19']);
				$("#filter_loading").fadeTo('slow', 0);
				$("#filter_error_msg").fadeTo('slow', 1);
                error++;
			}
        });  
		var cnt = 0;
		var exams_list = '';
		checkboxFields.each(function(){
			value = $(this).val();
			if($(this).is(":checked")){
				if (cnt == 0){
					exams_list = $(this).val();
				}else{
					exams_list = exams_list + "," + $(this).val();				
				}
				cnt++;
			}
		});
	
        if(!error) {
			$('#more_results').unbind('click');
			$('#more_results').removeClass('unfiltered').addClass('fltered');
			$("#filter_error_msg").fadeTo('slow', 0);			
			$("#filter_loading").fadeTo('slow', 1);
			previous = ajax_call(0, false, exams_list, fromDate, toDate);	
			$('#more_results').click(function(){
				if ($(this).hasClass('yes')){
					$('#more_results').html("<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />");
					previous = ajax_call(previous, true, exams_list, fromDate, toDate);
				}
			});	

        }              
	
	});
	
	var previous = ajax_call();	
		
	$('#more_results').click(function(){
		if ($(this).hasClass('yes') && $(this).hasClass('unfiltered')){
			$('#more_results').html("<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />");
			previous = ajax_call(previous, true);
		}
	});	

	$('#from').datepicker({dateFormat:'dd/mm/yy', showAnim:'fadeIn'});
	$('#to').datepicker({dateFormat:'dd/mm/yy', showAnim:'fadeIn'});
	
	
	
});