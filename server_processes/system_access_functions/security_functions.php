<?php 
	// Checks if user has either doctor or manager rights
	// and prevents access if he has not
	function check_if_doctor_manager()
	{
		If (!isset($_SESSION['Rights']) || ($_SESSION['Rights'] != "doctor" && $_SESSION['Rights'] != "manager") )
		{
			echo json_encode("ACCESS DENIED");
			exit;
		}
	}	
	// Checks if user has doctor rights and prevents access if he has not
	function check_if_doctor()
	{
		If (!isset($_SESSION['Rights']) || $_SESSION['Rights'] != "doctor"){
			echo json_encode("ACCESS DENIED");
			exit;
		}		
	}
	// Checks if user has manager rights and prevents access if he has not
	function check_if_manager()
	{
		If (!isset($_SESSION['Rights']) || $_SESSION['Rights'] != "manager") 
		{
			echo json_encode("ACCESS DENIED");
			exit;
		}	
	}
	// Checks if user has patient rights and prevents access if he has not
	function check_if_patient()
	{
		If (!isset($_SESSION['Rights']) || $_SESSION['Rights'] != "asth") 
		{
			echo json_encode("ACCESS DENIED");
			exit;
		}	
	}
	
	// Checks if session timeout has exceed the valid time without destroying
	function check_only_session_timer()
	{	
		$valid_session=false;
		if (isset($_SESSION['timeout']))
		{
			$valid_session=true;
			$expiration_time =  60*30; 		// Expiration time in Seconds
			$current_time = time();
			If ($current_time - $_SESSION['timeout'] >= $expiration_time ) {
				$valid_session=false;
			}
		}
		return $valid_session;
	}	
	
	// Checks if session timeout has exceed the valid time and festroy
	function check_session_timer()
	{	
		$valid_session=false;
		if (isset($_SESSION['timeout'])){
			$valid_session=true;
			$expiration_time = 60*30; 		// Expiration time in Seconds
			$current_time = time();
			if ($current_time - $_SESSION['timeout'] >= $expiration_time ) 
			{
				session_destroy();
				$valid_session=false;
			}
		}
		return $valid_session;
	}
	
	// Updating session's timeout
	function update_session()
	{
		session_id_regeneration();
		$_SESSION['timeout'] = time();	
		session_commit();				
	}	
	
	// Regenerates the id of the session
	function session_id_regeneration()
	{
		$current_time = time();						
		// Regenerates id only if 5' have passed since the last regeneration	
		if (!isset($_SESSION['changed']) || $_SESSION['changed'] < $current_time - 300)
		{
			session_regenerate_id(true);
			$_SESSION['changed'] = time();			// Sets changed timestamp		
		}
	}	
	
	// Checks if session timeout has exceed the valid time and if not updates it
	function check_and_update_session()
	{
		$valid_session=true;
		if (!check_session_timer())
		{ 
			$valid_session=false;
		}
		else{	
			update_session();
		}
		return $valid_session;
	}
?>