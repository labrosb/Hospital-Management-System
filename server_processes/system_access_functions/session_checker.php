<?php 
	session_start();

	include_once($_SERVER['DOCUMENT_ROOT']."/hospital/server_processes/system_access_functions/security_functions.php");	// Security functions	

	if (!check_session_timer())
	{
		exit("false");
	}
	
	if (isset($_SESSION['Rights']) && isset($_SESSION['id']) )
	{
		if ($_SESSION['Rights'] == "asth")
		{
			exit("is_patient");
		}
		else if ($_SESSION['Rights'] == 'doctor') 
		{
			exit("is_doctor");
		}
		else if ($_SESSION['Rights'] == 'manager') 
		{
			exit("is_manager");
		}			
	}
	else {
		exit("false");
	}
?>