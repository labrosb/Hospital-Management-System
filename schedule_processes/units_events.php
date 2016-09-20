<?php
	
	include("../server_processes/config.inc.php");
	
	$con = mysql_connect(dbServer,dbUser,dbPass) or die('Connection-ERROR');

	mysql_select_db(dbDatabase) or die('Connection-ERROR'.$dbDatabase.'.');

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	$unit_id = $_GET['id'];
	
	$sql = mysql_query("SELECT * FROM reasons") or die("cannot connect to reasons");
	while($row = mysql_fetch_assoc($sql)) {
		$reasons[] = $row;
	}
	
	$sql = mysql_query("SELECT Id, Staff_id, Start, End, Reason FROM unavailable_staff WHERE Reason = '5' AND Specialty_id = $unit_id ") or die("cannot connect to unavailable_staff");
	$cnt=0;
	while($row = mysql_fetch_assoc($sql)) {
		$types[$cnt]['id'] = $row['Id'];
		$types[$cnt]['start_date'] = $row['Start'];
		$types[$cnt]['end_date'] = $row['End'];
		$types[$cnt]['staff_id'] = $row['Staff_id'];		
		$types[$cnt]['parent'] = $row['Reason'];
		$types[$cnt]['color'] = "#c6e4ee";
		$types[$cnt]['textColor'] = "#566c71";
		$types[$cnt]['more'] = '...........';
		$exam_id = $types[$cnt]['id'];
		$staff_id = $row['Staff_id'];
		
		
		for ($i=0; $i<=count($reasons)-1; $i++) {
			if ($types[$cnt]['parent'] == $reasons[$i]['Id']) {
				$types[$cnt]['parent_text'] = $reasons[$i]['Name'];
				$types[$cnt]['text'] = $types[$cnt]['parent_text'];
				break;
			}
		}
		if($types[$cnt]['parent'] == 5) { //Εφημερία
		
			$sql_staff = mysql_query("SELECT Name, Surname FROM medical_staff WHERE Id = $staff_id") or die("cannot connect to unavailable_staff");
				while($row = mysql_fetch_assoc($sql_staff)) {
					$types[$cnt]['name'] = $row['Name'];
					$types[$cnt]['surname'] = $row['Surname'];
				}	
		
			$type_id = $types[$cnt]['id'];
			$sql_2 = mysql_query("SELECT Unit_id FROM on_duty WHERE Id = $type_id LIMIT 1") or die("cannot connect to on_duty");
			while($row = mysql_fetch_assoc($sql_2)) {
				$unit_id = $row['Unit_id'];
			}	
			$sql_3 = mysql_query("SELECT Name, Building_id FROM units WHERE Id = $unit_id LIMIT 1") or die("cannot connect to units-2");
			while($row = mysql_fetch_assoc($sql_3)) {
				$types[$cnt]['unit_name'] = $row['Name'];
				$types[$cnt]['building_id'] = $row['Building_id'];				
			}	
			$building_id = $types[$cnt]['building_id'];
			$sql_4 = mysql_query("SELECT Name, Address FROM buildings WHERE Id = $building_id LIMIT 1") or die("cannot connect to buildings");
			while($row = mysql_fetch_assoc($sql_4)) {
				$types[$cnt]['building_name'] = $row['Name'];
				$types[$cnt]['building_address'] = $row['Address'];				
			}
			
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['name']." ".$types[$cnt]['surname'];		
		}
		
		$cnt++;
	}

//---Βάρδια---// 

	$sql = mysql_query("SELECT Id, Staff_id, Start_date, End_date FROM work_shifts WHERE unit_id = $unit_id") or die("cannot connect to unavailable_staff");
	while($row = mysql_fetch_assoc($sql)) {
	//echo $cnt;
		$types[$cnt]['id'] = $row['Id'];
		$types[$cnt]['start_date'] = $row['Start_date'];
		$types[$cnt]['end_date'] = $row['End_date'];
		$types[$cnt]['staff_id'] = $row['Staff_id'];				
		$types[$cnt]['color'] = "#c6e4ee";
		$types[$cnt]['textColor'] = "#566c71";
		$types[$cnt]['parent'] = 6;
		$types[$cnt]['parent_text'] = 'Work shift';
		$types[$cnt]['text'] = 'Work shift';
		$types[$cnt]['more'] = '...........';
		$staff_id = $row['Staff_id'];
		
		$types[$cnt]['name'] = null;
		$types[$cnt]['surname'] = null;

			$sql_staff_2 = mysql_query("SELECT Name, Surname FROM medical_staff WHERE Id = $staff_id") or die("cannot connect to medical_staff");
			while($row = mysql_fetch_assoc($sql_staff_2)) {
				$types[$cnt]['name']= $row['Name'];
				$types[$cnt]['surname'] = $row['Surname'];
			}	
		
			$sql_3 = mysql_query("SELECT Name, Building_id FROM units WHERE Id = $unit_id LIMIT 1") or die("cannot connect to units-2");
			while($row = mysql_fetch_assoc($sql_3)) {
				$types[$cnt]['unit_name'] = $row['Name'];
				$types[$cnt]['building_id'] = $row['Building_id'];				
			}	
			$building_id = $types[$cnt]['building_id'];
			$sql_4 = mysql_query("SELECT Name, Address FROM buildings WHERE Id = $building_id LIMIT 1") or die("cannot connect to buildings");
			while($row = mysql_fetch_assoc($sql_4)) {
				$types[$cnt]['building_name'] = $row['Name'];
				$types[$cnt]['building_address'] = $row['Address'];				
			}
			
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['name'].' '.$types[$cnt]['surname'];
		$cnt++;
	}
		
//----//	
	mysql_close($con);
	//print_r($types);
	echo json_encode($types);
	exit;
?>