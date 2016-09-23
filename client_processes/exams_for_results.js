$(function() {

	function struck_tables(response, items){ 
		
		var exams='';
		var tables = response.length;
		var more_results = true;
		if (response == "NO NEW EXAMS"){
			more = '<p data-inter="noNewExams"> </p>';
			more_results = false;
		}else if (response == "NO MORE EXAMS"){
			more = '<p data-inter="noMoreExams"> </p>';
			more_results = false;			
		}else{
			var type = 'data-inter="type"';
			var patient = 'data-inter="patient"';
			var date = 'data-inter="date"';
			var time = 'data-inter="time"';
			var resultsTitle = 'data-inter="resultsTitle"';
			var resultsMsg = 'data-inter="resultsMsg"';			
			for (i=0; i<tables; i++){
				exams = exams + '<table id="'+response[i].Exam_id+'" class ="results_table hovTable"><tr id="title"><td '+type+'> </td><td '+patient+'></td><td '+date+'> </td><td '+time+'> </td></tr>';
				exams = exams + '<tr id="res"><td>'+response[i].Examination_name+'</td><td>'+response[i].patient_name+" "+response[i].patient_surname+'</td><td>'+response[i].Date+'</td><td>'+response[i].Time+'</td></tr>';
				exams = exams + '<tr id="title"><td colspan="4" '+resultsTitle+'> </td></tr>';
				exams = exams + '<tr id="res"><td colspan="4" '+resultsMsg+'> </i></td></tr></table>';							
			}
			if (tables == items){
				var more = '<p data-inter="more"> </p>';
			}else{
				var more = '<p data-inter="noMoreExams"> </p>';
				more_results = false;
			}
			
		}
		tableEvents();	
		setTimeout(function(){
			$('#height_specify').html(exams);
			changeLang(defaultLang);
			var height = $('#intro').height();			
			var extra_height = $('#height_specify').height();	

			$('.content').animate({		//brings contentto the right height	
							height: height + extra_height
						}, 400, function(){ 
								$('#results').append(exams);
									$('#more_results').html(more);
									if (!more_results){
										$('#more_results').removeClass('yes').addClass('no');
										$('#more_results').html('<p data-inter="noMoreExams"></p>');
									}else{
										$('#more_results').addClass('yes');
										$('#more_results').html('<p data-inter="more"></p>');
									}
									changeLang(defaultLang);									
							});	
		},700);		

	}

	var previous = ajax_call();		
	
	//function events(){
		$('#more_results').click(function(){
			if ($(this).hasClass('yes')){
				$('#more_results').html("<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />");
				previous = ajax_call(previous);
			}
		});	

	
//});
	function ajax_call(limit1){	
		var items = 3;
		if (limit1 === undefined) {
			var limit1 = 0;
		}
		$.ajax({
			type: "POST",
			url: "server_processes/exams_for_results.php",
			dataType: "json",	
			data:{
				  limit1: limit1,
				  limit2: items
				 },				
			success: function(response){struck_tables(response,items);}
		});	
		limit1 = limit1 + items;
		return limit1;
	}	
	function tableEvents(){
	//alert('ok');
		$(".results_table").live('click', function(){
			var tableId = $(this).attr("id");
		
			$('#results_input').bPopup({
				 zIndex: 9998
				,onOpen: function(){}
				,onClose: function(){   
						$('#res_form textarea').val("");
				},modalClose: true			
			});
			
			$('.submit_result').click(function(){
				var text = $('#res_form textarea').val();
				if(text != ""){
					insert_results(tableId, text);
					$('#results_input').bPopup().close();
					$('#center_table #results table#'+tableId).fadeOut('slow');
				}else{
					return false;
				}
			});			

		});
	}
	

	
	function insert_results(tableId, text){
		$.ajax({
			type: "POST",
			url: "server_processes/exams_results_insertion.php",
			dataType: "json",
			data:{
				tableId: tableId,
				text: text
				},		
			success: function(response){}
		});
	}
		
});