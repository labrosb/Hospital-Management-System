<?php session_start();
	if (isset($_SESSION['Rights']) && isset($_SESSION['id']) ){
		if ($_SESSION['Rights'] == "asth"){
			echo"asth";
		}
		else if ($_SESSION['Rights'] == 'staff') {
			echo"staff";
		}
		else if ($_SESSION['Rights'] == 'admin') {
			echo"admin";
		}
		else if ($_SESSION['Rights'] == 'manager') {
			echo"manager";
		}			
	}
	else {
		echo"false";
	}

?>