$(document).ready(function() {
	$('.general_program').click(function(){				
		window.open('schedule_processes/doctor_full.php');		
	});	
	
	$('.daily_program').click(function(){
		window.open("schedule_processes/doctor_daily.php");
	
	});		
	
	$('.on_duty_shifts').click(function(){
		window.open("schedule_processes/on_duty_swift.php");
	
	});		



	$('.staff_program').click(function(){ 
		$.msgBox({ type: "prompt",
			title: "Insert doctor's code!",
			inputs: [
			{ header: "", type: "text", name: "code" }],
			buttons: [
			{ value: "OK" }, {value:"Cancel"}],
			success: function (values, result) {
				if(values == "Cancel"){
					return false;
				}
				else{
					var id = result[0].value;
					var res = CheckIfExists("doctor_sess", id);

					if (res == "EXISTS"){
						window.open("schedule_processes/manager_doc.php?id="+id+"");
					}
					else if (res == "NOT EXISTS"){
						$.msgBox({
							title:"Fail",
							content:"Invalid doctor's code!"
						});				
					}
					else{
						$.msgBox({
							title:"Fail",
							content:"An error came up. Please try again!"
						});				
					}
				}
			}
		});	
	});		

	
	$('.search_staff_click').click(function(){ 
		$.msgBox({ type: "prompt",
			title: "Insert doctor's code!",
			inputs: [
			{ header: "", type: "text", name: "code" }],
			buttons: [
			{ value: "OK" }, {value:"Cancel"}],
			success: function (values, result) {
				if(values == "Cancel"){
					return false;
				}
				else{
					var id = result[0].value;
					var res = CheckIfExists("doctor_sess", id);

					if (res == "EXISTS"){
						show_profile(id);
					}
					else if (res == "NOT EXISTS"){
						$.msgBox({
							title:"Fail",
							content:"Invalid doctor's code!"
						});				
					}
					else{
						$.msgBox({
							title:"Fail",
							content:"An error came up. Please try again!"
						});				
					}
				}
			}
		});	
	});		
	
	
	$('.units_program').click(function(){
		$('#external_field_units').bPopup({ 
				position:[442,50]
				,modal :false
				,async: false

		});
				
		$('#cancelButton').click(function(){
			$('#external_field_units').bPopup().close(); 
		});
		
		$('#okButton').click(function(){
			$('#external_field_units').bPopup().close(); 
			var unitId = $('.units_select option:selected').val();
			window.open("schedule_processes/units.php?id="+unitId+"");
		});
		
	});			
	
	function CheckIfExists($who, $id)
	{
		var result = $.ajax({
			type: "POST",
			url: "schedule_processes/existanceChecker.php",
			data:{
				  who: $who,
				  id: $id
				 },	
			 async: false,						
			success:function(response){return response;}			 
		}).responseText;	
		
		return result;
	}	
	
	function show_profile($id)
	{	
		$.ajax({
			type: "POST",
			url: "server_processes/load_staff_card.php",
			data:{
			  id: $id
			},	
			async: false,					
			dataType: "json",	  					
			success: function(response){
					struct_card(response);
			}					
		});		
		
		$('#external_field_staff').bPopup({ 
				position:[442,20]
				,modal :false
				,onClose: function(){ 
										$('#doc_photo_content').html("");
										$('.name2').html("");
										$('.surname').html("");
										$('.specialty').html("");
										$('.sex').html("");
										$('.birthDate').html("");
										$('.fathersName').html("");
										$('.mothers_name').html("");
										$('.Work_phone').html("");
										$('.Home_Phone').html("");
										$('.Mobile_phone').html("");
										$('.Email').html("");
										$('.Address').html("");
										$('.City').html("");
										$('.Postal_code').html("");
										$('.Hire_date').html("");
										$('.biog').html("");
									}
		});	
		$('.del_btn').click(function(){
			$.ajax({
				type: "POST",
				url: "server_processes/delete_staff.php",
				data:{
				  id: $('.del_btn').val()
				},	
				async: false,					
				success: function(response){
							$('#external_field_staff').bPopup().close(); 
							if(response =='ok'){
								$.msgBox({
									title:"Success",
									content:"The doctor has deleted successfully!!"
								});	
							}else{
								$.msgBox({
									title:"Fail",
									content:"An error came up!!"
								});							
							}
							
						}
						
			});			
		});
	}
	
	function struct_card($staff)
	{ 
		$('#doc_photo_content').append('<img id="doc_photo" src="styles/images/profile_images/doctors/'+$staff[0].Photo+'.png" height="200" width="200">');
		$('.name2').append($staff[0].Name);
		$('.surname').append($staff[0].Surname);
		$('.specialty').append($staff[0].Specialty);
		$('.sex').append($staff[0].Sex);
		$('.birthDate').append($staff[0].Birth_date);
		$('.fathersName').append($staff[0].Fathers_name);
		$('.mothers_name').append($staff[0].Mothers_name);
		$('.Work_phone').append($staff[0].Work_phone);
		$('.Home_Phone').append($staff[0].Home_Phone);
		$('.Mobile_phone').append($staff[0].Mobile_phone);
		$('.Email').append($staff[0].Email);
		$('.Address').append($staff[0].Address);
		$('.City').append($staff[0].City);
		$('.Postal_code').append($staff[0].Postal_code);
		$('.Hire_date').append($staff[0].Hire_date);
		$('.biog').append($staff[0].Biography);	
		$('.del_btn').val($staff[0].Id);
	}
	
});