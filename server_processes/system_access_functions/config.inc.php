<?php
	function DB_Connect()
	{			
		// Sets username/pass with different priviledges
		// for each user category
		
		if(!isset($_SESSION['Rights']))				// If user is a guest
		{
			define("dbUser", "guest");
			define("dbPass", "j8sub8GDRuu5B2nE");		
		}
		else if($_SESSION['Rights'] == "asth")		// If user is patient
		{
			define("dbUser", "patient");
			define("dbPass", "RMfNZ4HFuVH4sBDH");		
		}
		else if($_SESSION['Rights'] == "doctor")	// If user is doctor
		{
			define("dbUser", "doctor");
			define("dbPass", "xn6r929YzKmC8uXZ");		
		}
		else if($_SESSION['Rights'] == "manager")	// If user is manager
		{
			define("dbUser", "manager");
			define("dbPass", "dNRZJ7tu9GGWLLj8");		
		}
		
		// Connection to database
		$con = new PDO('mysql:host=localhost; dbname=hosp_eng; charset=utf8', dbUser, dbPass);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);		
		return $con;
	}
?>
