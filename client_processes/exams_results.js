$(function() {

	function struck_tables(response, items){ 
		
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
				exams = exams + '<tr id="title"><td colspan="4">Results:</td></tr>';
				exams = exams + '<tr id="res"><td colspan="4">'+response[i].Results+'</td></tr></table></br></br>';							
			}
			if (tables == items){
				var more = '<p data-inter="more"></p>';
			}else{
				var more = '<p data-inter="noMoreResults"></p>';
				more_results = false;
			}
			
		}
		
		setTimeout(function(){
			$('#height_specify').html(exams);
			changeLang(defaultLang);
			var height = $('#intro').height();			
			var extra_height = $('#height_specify').height();	

			$('.content').animate({		//Φέρνει το content στο σωστο height	
							height: height + extra_height
						}, 400, function(){ 
								$('#results').append(exams);
									$('#more_results').html(more);
									if (!more_results){
										$('#more_results').removeClass('yes').addClass('no');
										
									}else{
										$('#more_results').addClass('yes');
										

									}		
									changeLang(defaultLang);									
							});	
		},700);				
	}

	ajax_call();	
	
	function ajax_call(){	
		var items = 3;
		$.ajax({
			type: "POST",
			url: "server_processes/exams_results.php",
			dataType: "json",			
			success: function(response){struck_tables(response,items); }
		});	
		
	}	
	
	//function events(){
		$('#more_results').click(function(){
			if ($(this).hasClass('yes')){
				$('#more_results').html("<img id='loading_img' src='styles/images/loading2.gif' height='30' width='30' />");
				ajax_call();
			}
		});
	//}
	
});