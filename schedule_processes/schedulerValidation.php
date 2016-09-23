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
	
	date_default_timezone_set('Europe/Athens');

	$this_doctor = $_POST['staff_id'];
	
	$event = $_POST['event'];
	$action = $_POST['action'];
	$startDate = $_POST['startDate'];
	$endDate = $_POST['endDate'];
	$day_off_search = $_POST['dayOff'];
	
	$result['dayOff'] = null;
	//$startDate = date('Y-m-d H:i:s', strtotime($startDate));
	//$endDate = date('Y-m-d H:i:s', strtotime($endDate));
	
	if ($action == 'insert'){
		$sql = mysql_query("SELECT Reason FROM unavailable_staff WHERE((Staff_id='$this_doctor') 
							AND (('$startDate' BETWEEN Start AND End) OR ('$endDate' BETWEEN Start AND End) 
							OR ('$startDate'<=Start AND '$endDate'>= End)) ) ORDER BY Start DESC LIMIT 1")  
							or die("cannot connect to unavailable_staff");
		$num_rows = mysql_num_rows($sql);	
		
		$sql_2 = mysql_query("SELECT Id FROM work_shifts WHERE((Staff_id='$this_doctor') 
							AND (('$startDate' BETWEEN Start_date AND End_date) OR ('$endDate' BETWEEN Start_date AND End_date) 
							OR ('$startDate'<= Start_date AND '$endDate'>= End_date)) ) ORDER BY Start_date DESC ")  
							or die("cannot connect to work_shifts");
		$num_rows_2 = mysql_num_rows($sql_2);	
		
		
		if ( $num_rows > 0 || $num_rows_2 > 0) {     //If it overlays to other event
			$availability = false;
			switch($event) {
				case 1: // Leave
					$result['do'] = "ASK";
					$result['events'] = get_events_id_only($startDate, $endDate, $this_doctor);
					break;
				case 2: // Sick leave
					$result['do'] = "FORCE";
					$result['events'] = get_events_id_only($startDate, $endDate, $this_doctor);
					break;
				case 3: // day-off
					$result['do'] = "ASK";
					$result['events'] = get_events_id_only($startDate, $endDate, $this_doctor);
					break;
				case 4: // Exams
					$events = get_reasons($startDate, $endDate, $this_doctor);
		//print_r($events);
					$result['do'] = "NOT";
					foreach($events as &$event){
						if($event['Reason'] == 6 ){
							$result['do'] = "NTN";	
							break;
						}
					}						
					break;
				case 5: // call-duty
					$result['do'] = "NOT";	
					break;
				case 6: // work-shift
					$events = get_reasons($startDate, $endDate, $this_doctor);
		//print_r($events);

					$result['do'] = "NTN";
					foreach($events as &$event){
						if($event['Reason'] > 4 || $event['Reason'] < 4 ){
							$result['do'] = "NOT";	
							break;
						}
					}	
					break;					
			}
		}
		else{ //if not occupied
			 $result['do'] = "NTN";
			 $result['eventsNum'] = 0;
		}

		if($day_off_search == "true"){	
			$result['dayOff'] = findDayOff($startDate, $endDate, $this_doctor );							
		}
		mysql_close($con);
		//print_r($result);
		echo json_encode($result);	
		exit;

	}

	
	function repoStartEnd($startDate){
		$startDateArray = explode( " " , $startDate);		
		$repoDate["start"] = $startDateArray[0]." 7:00:00";
		$repoDate["end"] = $startDateArray[0]." 22:55:00";
		return $repoDate;
	}	
	
	
	function findDayOff($startDate, $endDate, $this_doctor ) {
		$newDate = date('Y-m-d H:i:s', strtotime($startDate. ' + 1 days'));
		$daysNum = 1;
		$availableDate = false;	
		while ($daysNum <= 7 && $availableDate == false){
			$day = date('D', strtotime( $newDate));			
			if ( $day == "Sat" || $day == "Sun"  ) {
				$newDate = date('Y-m-d H:i:s', strtotime($newDate. ' + 1 days'));
				$daysNum++;
			}else{		
				$repoDate = repoStartEnd($newDate);
				$repoStart = $repoDate["start"];
				$repoEnd = $repoDate["end"];
				$sql_2 = mysql_query("SELECT Reason FROM unavailable_staff WHERE((Staff_id='$this_doctor') 
									AND (Start BETWEEN '$repoStart' AND '$repoEnd') OR (End BETWEEN '$repoStart' AND '$repoEnd')
									OR ('$repoStart' BETWEEN Start AND End) OR('$repoEnd' BETWEEN Start AND End)) 
									ORDER BY Start DESC") 
									or die("cannot connect to unavailable_staff");	
									
				$num_rows = mysql_num_rows($sql_2);	
				if ($num_rows > 0){
					$newDate = date('Y-m-d H:i:s', strtotime($newDate. ' + 1 days'));
					$day = date('D', strtotime( $newDate));
					$daysNum++;
				}
				else{		
					$availableDate = true;
					$result['dayOffDate'] = date('Y-m-d', strtotime($repoStart));
					$result['dayOffStart'] = $repoStart;	
					$result['dayOffEnd'] = $repoEnd;					
				}
				
			}
								
		}	
		$result['dateExistance'] = $availableDate;
		$result['daysAfter'] = $daysNum;	
		$result['day'] = $day;		
		
		return $result;
	}
	
	
	function get_events($start_Date, $End_Date, $this_doctor){
		$i=0;
		$sql = mysql_query("SELECT * FROM unavailable_staff WHERE((Staff_id='$this_doctor') 
							AND (('$start_Date' BETWEEN Start AND End) OR ('$End_Date' BETWEEN Start AND End) 
							OR ('$start_Date'<=Start AND '$End_Date'>= End)) ) ORDER BY Start DESC")  
							or die("cannot connect to unavailable_staff");			
		while($row = mysql_fetch_assoc($sql)) {
			$result[$i]['event_id'] = $row['Id'];
			$result[$i]['startDate'] = $row['Start'];
			$result[$i]['endDate'] = $row['End'];
			$result[$i]['reason'] = $row['Reason'];	
			$i++;
		}	
		return $result;
	}

	
	function get_events_id_only($start_Date, $end_Date, $this_doctor){
		$i=0;
		$sql = mysql_query("SELECT Id FROM unavailable_staff WHERE((Staff_id='$this_doctor') 
							AND (('$start_Date' BETWEEN Start AND End) OR ('$end_Date' BETWEEN Start AND End) 
							OR ('$start_Date'<=Start AND '$end_Date'>= End)) ) ORDER BY Start DESC")    
							or die("cannot connect to unavailable_staff");
		while($row = mysql_fetch_assoc($sql)) {
			$result[$i]['Id'] = $row['Id'];
			$i++;
		}	
		$sql_2 = mysql_query("SELECT Id FROM work_shifts WHERE((Staff_id='$this_doctor') 
							AND (('$start_Date' BETWEEN Start_date AND End_date) OR ('$end_Date' BETWEEN Start_date AND End_date) 
							OR ('$start_Date'<= Start_date AND '$end_Date'>= End_date)) ) ORDER BY Start_date DESC ")  
							or die("cannot connect to work_shifts");
		while($row = mysql_fetch_assoc($sql_2)) {
			$result[$i]['Id'] = $row['Id'];
			$i++;
		}							
		return $result;
	}

	function get_reasons($start_Date, $End_Date, $this_doctor){
		$i=0;
		$sql = mysql_query("SELECT Reason FROM unavailable_staff WHERE((Staff_id='$this_doctor') 
							AND (('$start_Date' BETWEEN Start AND End) OR ('$End_Date' BETWEEN Start AND End) 
							OR ('$start_Date'<=Start AND '$End_Date'>= End)) ) ORDER BY Start DESC")    
							or die("cannot connect to unavailable_staff");
		while($row = mysql_fetch_assoc($sql)) {
			$result[$i]['Reason'] = $row['Reason'];
			$i++;
		}
		$sql_2 = mysql_query("SELECT Id FROM work_shifts WHERE((Staff_id='$this_doctor') 
							AND (('$start_Date' BETWEEN Start_date AND End_date) OR ('$End_Date' BETWEEN Start_date AND End_date) 
							OR ('$start_Date'<= Start_date AND '$End_Date'>= End_date)) ) ORDER BY Start_date DESC ")  
							or die("cannot connect to work_shifts");
		while($row = mysql_fetch_assoc($sql_2)) {
			$result[$i]['Reason'] = 6;
			$i++;
		}				
		return $result;
	}
?>