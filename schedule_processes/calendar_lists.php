<?php
	session_start();	
	
	include("../server_processes/config.inc.php");
	
	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 

	$action = $_POST['action'];
	
	if($action == "basic_lists"){
		$sql = mysql_query("SELECT * FROM reasons") or die("cannot connect to reasons");
		$cnt=0;
		while($row = mysql_fetch_assoc($sql)) {
			$reasons[$cnt]['Id'] = $row['Id'];
			$reasons[$cnt]['name'] = $row['Name'];
			if($reasons[$cnt]['Id'] == 4 || $reasons[$cnt]['Id'] == 5 || $reasons[$cnt]['Id'] == 6){
				$reasons[$cnt]['option1'] = true;
			}
			else{ 
				$reasons[$cnt]['option1'] = false;
			}
			if($reasons[$cnt]['Id'] == 5 || $reasons[$cnt]['Id'] == 6 ){
				$reasons[$cnt]['option2'] = true;
			}
			else{
				$reasons[$cnt]['option2'] = false;
			}
			if($reasons[$cnt]['Id'] == 4){
				$reasons[$cnt]['patient'] = true;
				$reasons[$cnt]['ward_details'] = true;				
			}
			else{
				$reasons[$cnt]['patient'] = false;
			}
			$reasons[$cnt]['parentId'] = 0;
			$reasons[$cnt]['label'] = 'Event';
			$cnt++;
		}

		$choicelist['parent'] = $reasons;

		$sql = mysql_query("SELECT Id, Staff_category_id, Name FROM examination_types") or die("cannot connect to examination_types");
		$cnt=0;
		while($row = mysql_fetch_assoc($sql)) {
			$examination_types[$cnt]['Id'] = $row['Id'];
			$examination_types[$cnt]['name'] = $row['Name'];
			$examination_types[$cnt]['parentId'] = 4;	
			$examination_types[$cnt]['label'] = 'Exam type';						
			$cnt++;
		}
		$choicelist['child'] = $examination_types;	

		
		$sql = mysql_query("SELECT Id, Name, Building_id FROM units") or die("cannot connect to units");
		$cnt2=0;
		while($row = mysql_fetch_assoc($sql)) {
			$units[$cnt]['Id'] = $row['Id'];
			$units[$cnt]['name'] = $row['Name'];
			$units[$cnt]['parentId'] = 5;	
			$units[$cnt]['label'] = 'Unit';
			$buildings[0]['building_id'] = 1;
			$buildings[0]['parentId'] = 5;
			$buildings[0]['name'] = "Building Α";
			$buildings[0]['label'] = 'Building';
			$cnt++;
		}
		
		$choicelist['child'] = $choicelist['child'] + $units;
		$choicelist['child2'] = $buildings;

		
		$sql = mysql_query("SELECT Id, Name, Building_id FROM units") or die("cannot connect to units");
		$cnt2=0;
		while($row = mysql_fetch_assoc($sql)) {
			$units[$cnt]['Id'] = $row['Id'];
			$units[$cnt]['name'] = $row['Name'];
			$units[$cnt]['parentId'] = 6;	
			$units[$cnt]['label'] = 'Unit';
			$buildings[1]['building_id'] = 1;
			$buildings[1]['parentId'] = 6;
			$buildings[1]['name'] = "Building Α";
			$buildings[1]['label'] = 'Building';
			$cnt++;
		}
			
		$choicelist['child'] = $choicelist['child'] + $units;
		$choicelist['child2'] = $choicelist['child2']  + $buildings;	

	}else if ($action == "child2"){
		
		$child_choice = $_POST['child_choice'];

		$sql = mysql_query("SELECT Building_id FROM units WHERE Id = $child_choice") or die("cannot connect to units");
		while($row = mysql_fetch_assoc($sql)) {
			$building_id = $row['Building_id'];
		}
		$sql_2 = mysql_query("SELECT Name FROM buildings WHERE Id = $building_id") or die("cannot connect to buildings");
		$cnt2=0;
		while($row = mysql_fetch_assoc($sql_2)) {
			$buildings[$cnt2]['building_id'] = $building_id;		
			$buildings[$cnt2]['parentId'] = 5;
			$buildings[$cnt2]['name'] = $row['Name'];
			$buildings[$cnt2]['label'] = 'Building';
			$cnt2++;
		}
	
	}
		
	$choicelist['child2'] = $buildings;
	
	$choicelist['length'] = $cnt2;

//print_r($choicelist);	
	
	echo json_encode($choicelist);
	
	mysql_close($con);

	exit;
?>