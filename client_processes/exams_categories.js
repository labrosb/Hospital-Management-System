;(function($) {
	function struck_lists(response){
		var counter = 0;
		var ul = ~~(response.length / 5 );
		var rest_li = response.length % 5 ;
				exams ="<div class='button bClose'><span>X</span></div> </br> <table><tr><td> <div id='all_lists'> <p>Select category:</p>";
		for (i=0; i<ul; i++){
			exams = exams+"<table class='exams_list'>"
			for (j=0; i<5; i++){
				exams = exams+"<tr><td>"+response[counter].Name+"</td</tr>";
				counter++;			
			}
			exams = exams+"</table>";
		} 
		if (rest_li > 0){
			exams = exams+"<table class='exams_list'>"
			for (i=0; i<rest_li; i++){
				exams = exams+"<tr><td>"+response[counter].Name+"</td</tr>";
				counter++;
			}
			exams = exams+"</table>";												
		}
		exams = exams+"</div></td></tr></table>";			

		$('#exams_dimensions').html(exams);
		var height = $('#exams_dimensions').height();
		var width = $('#exams_dimensions').width();

		$('#exams_popup').animate({		//brings exams_popup to the right width	
								width: width + 5
							}, 400, function(){ 
										$('#exams_popup').animate({ 		//brings exams_popup to the right height
																height: height
															}, 400, function(){ 
																		$('#exams_popup').html(exams);
																		popup_events();
																	}); 
																	
									});	
														
	}
	

	function popup_events(){			
		$('.exams_list td').click(function(){
			var name = $(this).text();
			$('#exams_type').val(name);
			$('#external_field_exams').bPopup().close(); 
		});
	 }
 
 

	$(function() {		

		$('.exams_type_choice').bind('click', function(e) {
			if(!document.getElementById('external_field_exams')){
				$('#first_step').append("<div id='external_field_exams'><div id='exams_popup'></div></div>");  
			}
			$('#external_field_exams').bPopup({ 
				position:[450,'auto']
				,onOpen: function(){					
					$.ajax({
						type: "POST",
						url: "server_processes/load_categories.php",
						dataType: "json",	  					
						success: function(response){
								$('#exams_popup').html('<div class="button bClose"><span>X</span></div><div id="popup_loading"><img id="loading_img" src="styles/images/loading_icon.gif" height="50" width="50" /></div>');
								struck_lists(response);
						}					
					});	

				},onClose: function(){ 
						$('#exams_popup').html('<div class="button bClose"><span>X</span></div><div id="popup_loading"><img id="loading_img" src="styles/images/loading_icon.gif" height="50" width="50" /></div>');
						$('#exams_popup').html('<div class="button bClose"><span>X</span></div><div id="popup_loading"><img id="loading_img" src="styles/images/loading_icon.gif" height="50" width="50" /></div>');
						$('#exams_popup').css({'width':'auto','height':'auto'});
						$('#external_field_exams').css({'width':'auto','height':'auto'});												
						$('#exams_dimensions').empty();

				}});	
				
		});
			
		});
	
})(jQuery);