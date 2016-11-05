// Takes care of the staff and unit management actions

$(document).ready(function() 
{	
	$selectUnit = "null";

	$('.staff_program').click(function()					// Menu choice click
	{ 													
		$.msgBox({ type: "prompt",
			title: "Insert doctor's code!",					// Doctor insertion code popup used to
			inputs: [										// access his schedule page
			{ header: "", type: "text", name: "code" }],
			buttons: [
			{ value: "OK" }, {value:"Cancel"}],
			success: function (values, result) 
			{
				if(values == "Cancel"){
					return false;
				}
				else{
					var id = result[0].value;
					var res = CheckIfExists("doctor", id);			// Calls function to check if doctor exists

					if(res == "EXPIRED")							// If session is expired
					{
						session_expired();							// Calls function to alert and logout
					}
					else if (res == "EXISTS")						// If doctor found
					{
						window.open("content/calendar/manager_doc.php?id="+id+"");	 	
					}												// Opens the selected doctor's schedule page
					else if (res == "NOT EXISTS")
					{												
						$.msgBox(
						{											// Message if doctor doesn't exist
							title:"Fail",
							content:"Invalid doctor's code!"
						});				
					}
					else{
						$.msgBox(
						{											// Unknown errors handling
							title:"Fail",
							content:"An error came up. Please try again!"
						});				
					}
				}
			}
		});	
	});		
	
	$('.search_staff_click').click(function()					// Menu choice click
	{ 
		$.msgBox({ type: "prompt",
			title: "Insert doctor's code!",						// Staff insertion code popup used to
			inputs: [											// access doctor's info card
			{ header: "", type: "text", name: "code" }],
			buttons: [
			{ value: "OK" }, {value:"Cancel"}],
			success: function (values, result) 
			{
				if(values == "Cancel"){
					return false;
				}
				else{
					var id = result[0].value;
					var res = CheckIfExists("doctor", id);	// Calls function to check if doctor exists
					
					if(res == "EXPIRED")					// If session is expired
					{
						session_expired();					// Calls function to alert and logout
					}
					else if (res == "EXISTS")
					{										// If doctor exists, show profile card
						show_profile(id);
					}
					else if (res == "NOT EXISTS")
					{
						$.msgBox(
						{									// Message if doctor doesn't exist
							title:"Fail",
							content:"Invalid doctor's code!"
						});				
					}
					else{
						$.msgBox(
						{									// Unknown error handling
							title:"Fail",
							content:"An error came up. Please try again!"
						});				
					}
				}
			}
		});	
	});		

	function create_units_list()							// Retrieves and creates the choices for the
	{														// unit selection menu
		$.ajax({
			type: "POST",
			url: "server_processes/general_functions/exams_specialities_units.php",	
			data: {"get_units": null, "lang": defaultLang},
			dataType: "json",
			async: false,
			success: function(response)
			{
					$selectUnit = "<select class='units_select'>";
					
					for(var i in response)					// Loop to create the options of the select
					{
						$selectUnit = $selectUnit + '<option value="'+response[i].id+'">'+response[i][defaultLang]+'</option>';
					}
					$selectUnit = $selectUnit + "</select>";
			}					
		});
	}
		
	$('.units_program').click(function()
	{
		create_units_list();
		$.msgBox({
			type: "custom",									// Unit list popup used to access 
			title: "Select Unit",							// the unit's schedule	
			content: $selectUnit,
			buttons: [{ value: "OK" }, { value: "Cancel" }],
			success: function (myresult) 
			{
				if(myresult == "OK")
				{
					var unitId = $('.units_select option:selected').val();
					window.open("content/calendar/manager_units.php?id="+unitId+"");	
				}											// Opens the units schedule page	
			}
		});	
	});			
	
	function CheckIfExists($who, $id)						// Calls the server function to check if entities
	{														// exist before performing further actions
		var result = $.ajax(
		{
			type: "POST",
			url: "server_processes/general_functions/existence_checker.php",
			data:{
				  who: $who,
				  id: $id
				 },	
			 async: false,						
			success:function(response){return response;}			 
		}).responseText;	
		
		return result;
	}	
	
	function show_profile($id)								//  Retrieves data and creates doctor's info card
	{	
		$.ajax({
			type: "POST",
			url: "server_processes/manager_functions/load_staff_card.php",
			data:{
			  id: $id
			},	
			async: false,					
			dataType: "json",	  					
			success: function(response)						// Full doctor's info retrieved from the server
					{
						struct_card(response);				// Calls function to create the card
					}					
		});		
		
		$('#external_field_staff').bPopup(					// Popup message
		{ 
				position:[442,20]
				,modal :false
				,onClose: function()						// On popup close, clears the form values
						{ 
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
	}
	
	$('.del_btn').click(function()							// Delete doctor button
	{														// existing in the doctor's info card
		$('.bClose').click();								// If clicked, closes the doctor's card			
		$.msgBox(
		{									
			title: "Question",								// Confirmation message
			content: "Are you sure?",
			type: "confirm",
			buttons: [{ value: "Yes" }, { value: "No" }],
			success: function (myresult) {
				if (myresult == "Yes") 						// If yes is clicked
				{
					delete_staff();							// Calls function to delete the card
				}
				$('.del_btn').val("");						// Clears the delete button which 
			}												// corresponds to the doctor's id
		});					
	});
	
	function struct_card($staff)							// Doctors info card creation
	{ 
		$('#doc_photo_content').append('<img id="doc_photo" src="styles/images/profile_images/doctors/'+$staff[0].Photo+'.png" height="200" width="200">');
		$('.name2').append($staff[0].Name);
		$('.surname').append($staff[0].Surname);
		$('.sex').append($staff[0].Sex);							// Inserting foctor's info to the fields
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
		$('.biog').append($staff[0].Resume);	
		$('.del_btn').val($staff[0].Id);							// Setting delete button value (doctor's id)

		$(".specialty").attr("data-inter", $staff[0].Specialty);	// Speciality needs to be added with 
		changeLang(defaultLang);									// multy language support
	}	
	
	function delete_staff()									// Delete staff function
	{
		$.ajax({
			type: "POST",
			url: "server_processes/manager_functions/delete_staff.php",
			data:{
				 id: $('.del_btn').val()					// The doctor's id to be deleted exists 
			},												// in the delete button value
			async: false,					
			success: function(response)
			{						
					if(response == "EXPIRED")				// If session is expired
					{
						session_expired();					// Calls function to alert and logout
					}
					else if(response =='ok')
					{										// Success message
							$.msgBox(
							{
								title:"Success",
								content:"The doctor deleted successfully!!"
							});	
					}
					else{
						$.msgBox(
						{									// Unknown error handling
							title:"Fail",
							content:"An error came up!!"
						});							
					}							
			}						
		});				
	}	
});