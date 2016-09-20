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
	
	$id = $_GET['id'];

	
	$sql = mysql_query("SELECT * FROM reasons") or die("cannot connect to reasons");
	while($row = mysql_fetch_assoc($sql)) {
		$reasons[] = $row;
	}
	$cnt=0;	
	$sql = mysql_query("SELECT Id, Start, End, Reason FROM unavailable_staff WHERE Staff_id = $id ") or die("cannot connect to unavailable_staff");
	while($row = mysql_fetch_assoc($sql)) {
		$types[$cnt]['id'] = $row['Id'];
		$types[$cnt]['start_date'] = $row['Start'];
		$types[$cnt]['end_date'] = $row['End'];
		$types[$cnt]['parent'] = $row['Reason'];
		$types[$cnt]['color'] = "#c6e4ee";
		$types[$cnt]['textColor'] = "#566c71";
		$types[$cnt]['more'] = '...........';
		$exam_id = $types[$cnt]['id'];
		
		for ($i=0; $i<=count($reasons)-1; $i++) {
			if ($types[$cnt]['parent'] == $reasons[$i]['Id']) {
				$types[$cnt]['parent_text'] = $reasons[$i]['Name'];
				$types[$cnt]['text'] = $types[$cnt]['parent_text'];
				break;
			}
		}
		if($types[$cnt]['parent'] == 4) { //Εξετάσεις
			$sql_2 = mysql_query("SELECT Exam_type_id, Patient_id, Ward_id FROM examinations WHERE Exam_id = $exam_id LIMIT 1") or die("cannot connect to examinations");
			while($row = mysql_fetch_assoc($sql_2)) {
				$types[$cnt]['exam_type_id'] = $row['Exam_type_id'];
				$types[$cnt]['patient_id'] = $row['Patient_id'];
				$types[$cnt]['ward_id'] = $row['Ward_id'];
				$exam_type_id = $types[$cnt]['exam_type_id'];
				$patient_id = $types[$cnt]['patient_id'];
				$ward_id = $types[$cnt]['ward_id'];	
				
				$sql_3 = mysql_query("SELECT Name, Surname FROM patients WHERE Id = $patient_id LIMIT 1") or die("cannot connect to patients");
				while($row = mysql_fetch_assoc($sql_3)) {
					$types[$cnt]['patient_name'] = $row['Name'];
					$types[$cnt]['patient_surname'] = $row['Surname'];				
				}
				$sql_4 = mysql_query("SELECT Name FROM examination_types WHERE Id = $exam_type_id LIMIT 1") or die("cannot connect to examimation_types");
				while($row = mysql_fetch_assoc($sql_4)) {
					$types[$cnt]['exam_name'] = $row['Name'];				
				}	
				
				$sql_5 = mysql_query("SELECT Unit_id, Number FROM examimation_wards WHERE Id = $ward_id LIMIT 1") or die("cannot connect to examimation_wards");
				while($row = mysql_fetch_assoc($sql_5)) {
					$unit_id = $row['Unit_id'];
					$types[$cnt]['number'] = $row['Number'];
					
					$sql_6 = mysql_query("SELECT Name, Building_id FROM units WHERE Id = $unit_id LIMIT 1") or die("cannot connect to units");
					while($row = mysql_fetch_assoc($sql_6)) {
						$types[$cnt]['unit_name'] = $row['Name'];
						$building_id = $row['Building_id'];
						
						$sql_7 = mysql_query("SELECT Name, Address FROM buildings WHERE Id = $building_id LIMIT 1") or die("cannot connect to buildings");
						while($row = mysql_fetch_assoc($sql_7)) {
							$types[$cnt]['building_name'] = $row['Name'];
							$types[$cnt]['building_address'] = $row['Address'];				
						}						
					}						
				}					
			}		

			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['exam_name'];

		}
		
		else if($types[$cnt]['parent'] == 5) { //Εφημερία
		
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
			
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['unit_name'];
			$types[$cnt]['text'] = $types[$cnt]['text']. ' , '.$types[$cnt]['building_name'];		
		}
		
		$cnt++;
	}

//---Βάρδια---// 

	$sql = mysql_query("SELECT Id, Start_date, End_date, Unit_id FROM work_shifts WHERE Staff_id = $id ") or die("cannot connect to work_shifts");
	while($row = mysql_fetch_assoc($sql)) {
		$types[$cnt]['id'] = $row['Id'];
		$types[$cnt]['start_date'] = $row['Start_date'];
		$types[$cnt]['end_date'] = $row['End_date'];
		$types[$cnt]['unit_id'] = $row['Unit_id'];
		$unit_id = $types[$cnt]['unit_id'];
		$types[$cnt]['color'] = "#c6e4ee";
		$types[$cnt]['textColor'] = "#566c71";
		$types[$cnt]['parent'] = 6;
		$types[$cnt]['parent_text'] = 'Work swift';
		$types[$cnt]['text'] = 'Work swift';
		$types[$cnt]['more'] = '...........';
		
		
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
			
			$types[$cnt]['text'] = $types[$cnt]['text']. ' : '.$types[$cnt]['unit_name'];
			$types[$cnt]['text'] = $types[$cnt]['text']. ' , '.$types[$cnt]['building_name'];
		$cnt++;
	}
		
//----//	
	mysql_close($con);
	//print_r($types);
	echo json_encode($types);
	exit;
?>