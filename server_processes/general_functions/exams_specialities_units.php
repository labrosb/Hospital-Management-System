<?php
	function event_types()
	{
		return array(
			array( "id"=> 1,	"en"=> "Leave",				"gr"=> "Άδεια" ),
			array( "id"=> 2,	"en"=> "Leave (Sick-leave)","gr"=> "Άδεια (Αναρρωτική)" ),
			array( "id"=> 3,	"en"=> "Day off",			"gr"=> "Ρεπό" ),       
			array( "id"=> 4,	"en"=> "Examination",		"gr"=> "Εξέταση" ),	
			array( "id"=> 5,	"en"=> "Call duty",			"gr"=> "Εφημερία" ),				
			array( "id"=> 6,	"en"=> "Work shift",		"gr"=> "Βάρδια" )			
		);	
	}
	function exam_types()
	{
		return array(
			array( "data-inter"=>"radiological_exam",	 "en"=>"Radiological",  	"gr"=>"Ακτινολογικές",		"id"=> 1,	"docType"=> 1,	"unit"=> 1 ),
			array( "data-inter"=>"dermatologic_exam",	 "en"=>"Dermatologic",  	"gr"=>"Δερματολογικές",		"id"=> 2,	"docType"=> 2,	"unit"=> 2 ),
			array( "data-inter"=>"gynecological_exam",	 "en"=>"Gynecological", 	"gr"=>"Γυναικολογικές",		"id"=> 3,	"docType"=> 3,	"unit"=> 3 ),       
			array( "data-inter"=>"cardiac_exam",		 "en"=>"Cardiac", 			"gr"=>"Καρδιολογικές",		"id"=> 4,	"docType"=> 4,	"unit"=> 4 ),	
			array( "data-inter"=>"pathological_exam",	 "en"=>"Pathological",		"gr"=>"Παθολογικές",		"id"=> 5,	"docType"=> 5,	"unit"=> 5 ),				
			array( "data-inter"=>"ophthalmological_exam","en"=>"Ophthalmological",	"gr"=>"Οφθαλμολογικές",		"id"=> 6,	"docType"=> 6,	"unit"=> 6 ),	
			array( "data-inter"=>"orthopaedic_exam",	 "en"=>"Orthopaedic", 		"gr"=>"Ορθοπεδικές",		"id"=> 7,	"docType"=> 7,	"unit"=> 7 ),
			array( "data-inter"=>"endocrine_exam",		 "en"=>"Endocrine", 		"gr"=>"Ενδοκρινολογικές",	"id"=> 8,	"docType"=> 8,	"unit"=> 8 ),
			array( "data-inter"=>"neurological_exam",	 "en"=>"Neurological", 		"gr"=>"Νευρολογικές",		"id"=> 9,	"docType"=> 9,	"unit"=> 9 )	
		);
	}
	function specialities()
	{
		return array(
			array( "data-inter"=>"radiologist",		"en"=>"Radiologist",  		"gr"=>"Ακτινολόγος",			"id"=> 1,	"examType"=> 1,	 "unit"=> 1 ),
			array( "data-inter"=>"dermatologist",	"en"=>"Dermatologist",  	"gr"=>"Δερματολόγος",			"id"=> 2,	"examType"=> 2,	 "unit"=> 2 ),
			array( "data-inter"=>"gynecologist",	"en"=>"Gynecologist", 		"gr"=>"Γυναικολόγος",			"id"=> 3,	"examType"=> 3,	 "unit"=> 3 ),       
			array( "data-inter"=>"cardiologist",	"en"=>"Cardiologist", 		"gr"=>"Καρδιολόγος",			"id"=> 4,	"examType"=> 4,	 "unit"=> 4 ),	
			array( "data-inter"=>"pathologist",		"en"=>"Pathologist",		"gr"=>"Παθολόγος",				"id"=> 5,	"examType"=> 5,	 "unit"=> 5 ),
			array( "data-inter"=>"oculist",			"en"=>"Oculist",			"gr"=>"Ωτορινολαρυγγολόγος",	"id"=> 6,	"examType"=> 6,	 "unit"=> 6 ),	
			array( "data-inter"=>"orthopedist",		"en"=>"Orthopedist", 		"gr"=>"Ορθοπεδικός",			"id"=> 7,	"examType"=> 7,	 "unit"=> 7 ),
			array( "data-inter"=>"endocrinologist",	"en"=>"Endocrinologist", 	"gr"=>"Ενδοκρινολόγος",			"id"=> 8,	"examType"=> 8,	 "unit"=> 8 ),
			array( "data-inter"=>"neurologist",		"en"=>"Neurologist", 		"gr"=>"Νευρολόγος",				"id"=> 9,	"examType"=> 9,	 "unit"=> 9 )
		);
	}
	function units()
	{
		return array(  
			array( "data-inter"=>"radiology_unit", 	  "en"=>"Radiology ",  		"gr"=>"Ακτινολoγικό",		"id"=>1,	"examType"=> 1,	 "unit"=> 1,  "building"=> 1 ),
			array( "data-inter"=>"dermatology_unit",  "en"=>"Dermatology",  	"gr"=>"Δερματολoγικό",		"id"=>2,	"examType"=> 2,	 "unit"=> 2,  "building"=> 1 ),
			array( "data-inter"=>"gynecology_unit",   "en"=>"Gynecologic", 		"gr"=>"Γυναικολoγικό",		"id"=>3,	"examType"=> 3,	 "unit"=> 3,  "building"=> 1 ),
			array( "data-inter"=>"cardiology_unit",   "en"=>"Cardiology", 		"gr"=>"Καρδιολoγικό",		"id"=>4,	"examType"=> 4,	 "unit"=> 4,  "building"=> 1 ),
			array( "data-inter"=>"pathology_unit", 	  "en"=>"Pathology",		"gr"=>"Παθολoγικό",			"id"=>5,	"examType"=> 5,	 "unit"=> 5,  "building"=> 1 ),
			array( "data-inter"=>"opthalmology_unit", "en"=>"Ophthalmology",	"gr"=>"Οφθαλμολογικό",		"id"=>6,	"examType"=> 6,	 "unit"=> 6,  "building"=> 1 ),
			array( "data-inter"=>"orthopedic_unit",	  "en"=>"Orthopaedic", 		"gr"=>"Ορθοπεδικό",			"id"=>7,	"examType"=> 7,	 "unit"=> 7,  "building"=> 1 ),
			array( "data-inter"=>"endocrine_unit", 	  "en"=>"Endocrine", 		"gr"=>"Ενδοκρινολoγικό",	"id"=>8,	"examType"=> 8,	 "unit"=> 8,  "building"=> 1 ),
			array( "data-inter"=>"neurological_unit", "en"=>"Neurological", 	"gr"=>"Νευρολoγικό",		"id"=>9,	"examType"=> 9,	 "unit"=> 9,  "building"=> 1 )
		);	
	}	
	function buildings()
	{
		return array(  
			array( "id"=> 1,	"en"=>"Building A ",	"gr"=>"Κτηριο Α",	"Address"=> "Lensington 38" ),
			array( "id"=> 2,	"en"=>"Building B",		"gr"=>"Κτηριο Β",	"Address"=> "Lensington 38" )	
		);	
	}	
	
	function get_event_name_by_id($id, $lang)			// Retrieves event's name given the id
	{			
		$events = event_types();
		$event_name = searchNumArray($events, $id, 'id', $lang); 				// Searches array
		return $event_name;
	}	
	
	function get_examination_types()					// Returns all exam types in JSON format	
	{			
		$exams = exam_types();
		if (isset($_POST['lang']))						// If language is set
		{					
			usort($exams, 'compareByName');				// Sorts the multi-array according to  
		}												// the values of the chosen language
		return json_encode($exams);
	}	

	function get_specialities()							// Returns all specialities in JSON format	
	{			
		$specialities = specialities();
		if (isset($_POST['lang']))						// If language is set
		{					
			usort($specialities, 'compareByName');		// Sorts the multi-array according to  
		}												// the values of the chosen language
		return json_encode($specialities);
	}		
	
	function get_units()								// Returns all units in JSON format
	{						
		$units = units();
		if (isset($_POST['lang']))						// If language is set
		{					
			usort($units, 'compareByName');				// Sorts the multi-array according to  
		}												// the values of the chosen language
		return json_encode($units);
	}
	
	function get_examination_data_inter($id)			// Returns the data-inter exam types value 
	{													// given the id
		$exams = exam_types();
		$exam_label = searchNumArray($exams, $id, 'id', 'data-inter'); 			// Searches array
		return $exam_label;
	}
	
	function get_speciality_data_inter($id)				// Returns the data-inter specialtity value 		
	{			
		$specialities = specialities();
		$spec_label = searchNumArray($specialities, $id, 'id', 'data-inter'); 	// Searches array
		return $spec_label;
	}		

	function get_examination_name_by_id($id, $lang)		// Retrieves examination's name given the id
	{			
		$exams = exam_types();
		$exam_name = searchNumArray($exams, $id, 'id', $lang); 					// Searches array
		return $exam_name;
	}

	function get_examination_id_by_name($name, $lang)	// Retrieves examination's id given the name
	{		
		$exams = exam_types();
		$exam_id = searchArray($exams, $name, $lang, 'id'); 					// Searches array		
		return $exam_id;
	}
	
	function get_unit_name_by_id($id, $lang)			// Retrieves unit's name given the id
	{				
		$units = units();								
		$specialty_id = searchNumArray($units, $id, 'id', $lang); 				// Searches array
		return $specialty_id;	
	}	
	
	function get_building_Id_from_unit($id)				// Retrieves buildings's id given the unit's id
	{
		$units = units();								
		$building_id = searchNumArray($units, $id, 'id', 'building'); 			// Searches array
		return $building_id;
	}

	function get_speciality_id_from_exam_id($id)		// Retrieves speciality id given the exam id
	{
		$exams = exam_types();							
		$specialty_id = searchNumArray($exams, $id, 'id', 'docType'); 			// Searches array
		return $specialty_id;
	}

	function get_speciality_name_from_id($id, $lang)		// Retrieves speciality id given the name
	{
		$specialities = specialities();							
		$specialty_name = searchNumArray($specialities, $id, 'id', $lang); 		// Searches array
		return $specialty_name;
	}
	
	function get_building_Info($id)
	{
		$buildings = buildings();		
		$building_info = searchNumArrayFullInfo($buildings, $id, 'id');
		return $building_info;
	}
	
	function searchArray($array, $value, $where, $return) 
	{
		$result=null;										// Searches through a given multi-array for a string
		foreach ($array as $key => $val) 					// value in a given column and returns the corresponding 		
		{													// value of another column in the same row
			if ($val[$where] === $value) 
			{
				$result = $val[$return];
				break;
			}
		}
		return	$result;
	}
	
	function searchNumArray($array, $value, $where, $return) 
	{
		$result=null;										// Searches through a given multi-array for a numeric
		foreach ($array as $key => $val) 					// value in a given column and returns the corresponding 
		{													// value of another column in the same row
			if ($val[$where] == $value) 
			{	
				$result = $val[$return];
				break;
			}
		}
		return	$result;
	}
	
	function searchNumArrayFullInfo($array, $value, $where) 
	{
		$result=null;										// Searches through a given multi-array for a string
		foreach ($array as $key => $val) 					// value in a given column and returns the corresponding
		{													// value of another column in the same row
			if ($val[$where] == $value) 
			{
				$result = $array[$key];
				break;
			}
		}
		return	$result;
	}
	
	function compareByName($a, $b) 							// Sorts elements by name
	{
		$lang = $_POST['lang'];
		return strcmp($a[$lang], $b[$lang]);
	}
	
	function compareByNameENG($a, $b) 
	{		
		return strcmp($a['en'], $b['en']);
	}		

	
	////// Actions //////
	
	if( isset($_POST['speciality_data_inter_by_id']) ) 		// Interacts with Ajax to return the data-inter value
	{		
		print_r(get_speciality_data_inter
		($_POST['speciality_data_inter_by_id']));
	}
	
	if( isset($_POST['get_exam_types']) ) 					// Interacts with Ajax to return the units
	{						
		print_r(get_examination_types());
	}

	if( isset($_POST['get_specialities']) ) 				// Interacts with Ajax to return the specialities
	{						
		print_r(get_specialities());
	}	
	
	if( isset($_POST['get_units']) ) 						// Interacts with Ajax to return the units
	{							
		print_r(get_units());
	}
?>