<?php
	session_start();

	$email=$_POST['email'];
	include("config.inc.php");

	$con = mysql_connect(dbServer,dbUser,dbPass) or die("ERROR");

	mysql_select_db(dbDatabase) or die("Cannot select database ".$dbDatabase.".");

	mysql_query('SET character_set_results=utf8');
	mysql_query('SET names=utf8');  
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_connection=utf8');   
	mysql_query('SET character_set_results=utf8');   
	mysql_query('SET collation_connection=utf8_general_ci'); 
	
	
	$sql = mysql_query("SELECT * FROM users WHERE Username='$email' ") or die("cannot connect to examination_types");
	$num_rows = mysql_num_rows($sql);
	
	if($num_rows == 0){
		echo "NO";
	}else{
		echo "YES";
	}

	mysql_close($con);	
	
?>
