<?php

class auth{

	// CHANGE THESE VALUES TO REFLECT YOUR SERVER'S SETTINGS

	var $HOST = "localhost";	// Change this to the proper DB HOST

	var $USERNAME = "root";	// Change this to the proper DB USERNAME

	var $PASSWORD = "det15a";	// Change this to the proper DB USER PASSWORD

	var $DBNAME = "lcd";	// Change this to the proper DB NAME


	// AUTHENTICATE

	function authenticate($username, $password) {
   
      $Fecha = date("Y-m-d");

		$username   = strtoupper($username);
		
		$query      = "SELECT * FROM authuser WHERE uname='$username' AND passwd=MD5('$password') AND status <> 'inactive'";

                //echo $query;
                //break;
                
                $UpdateRecords = "UPDATE authuser SET lastlogin = NOW(), logincount = logincount + 1 WHERE uname='$username'";
      
		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		$SelectedDB = mysql_select_db($this->DBNAME);

		$result     = mysql_query($query);

		$numrows    = mysql_num_rows($result);

		$row        = mysql_fetch_array($result);
		 
		$Fec        = strtotime($Fecha);  
				
      $FecDias    = strtotime("-15 days",$Fec);   //Le quito 15 dias
		
		$FecUlt     = strtotime($row[feclave]);

		// CHECK IF THERE ARE RESULTS

		// Logic: If the number of rows of the resulting recordset is 0, that means that no

		// match was found. Meaning, wrong username-password combination.


		if($numrows == 0) {

			return 0;

		//}elseif( $FecDias > $FecUlt and $username <> 'Admin' and $username <> 'faz'){
		//}elseif( $FecDias > $FecUlt and $username<>'FROYLAN' and $username<>'ADMIN'){
		//}elseif( $FecDias > $FecUlt and $username<>'admin'){

		//	return 2;

		}else{

			$Update = mysql_query($UpdateRecords);

			return $row;

		}


	} // End: function authenticate



	// PAGE CHECK

	// This function is the one used for every page that is to be secured. This is not the same one

	// used in the initial login screen

	function page_check($username, $password) {
		// Cada k entra a la pagina entra a esta func.haber si existe en la b.datos
		$query = "SELECT * FROM authuser WHERE uname='$username' AND passwd=MD5('$password') AND status <> 'inactive'";

      $connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);


		$SelectedDB = mysql_select_db($this->DBNAME);

		$result = mysql_query($query); 
		

		$numrows = mysql_num_rows($result);

		$row = mysql_fetch_array($result);



		// CHECK IF THERE ARE RESULTS

		// Logic: If the number of rows of the resulting recordset is 0, that means that no

		// match was found. Meaning, wrong username-password combination.

		if ($numrows == 0) {

			return false;

		}

		else {

				return $row;

		}

	} // End: function page_check

	

	// MODIFY USERS

	function modify_user($username, $password, $team, $level, $status, $msj) {

   $Fecha=date("Y-m-d");


        // If $password is blank, make no changes to the current password

        if (trim($password == '')){

            $qUpdate = "UPDATE authuser SET team='$team', level='$level', status='$status',feclave='$Fecha',msj='$msj' WHERE uname='$username'";

        }else{

            $qUpdate = "UPDATE authuser SET passwd=MD5('$password'), team='$team', level='$level', 
                       status='$status',feclave='$Fecha',msj='$msj'
					        WHERE uname='$username'";

        }



		if (trim($level)=="") {

			return "blank level";

		}elseif (($username=="sa" AND $status=="inactive")) {

			return "sa cannot be inactivated";

		}elseif (($username=="admin" AND $status=="inactive")) {

			return "admin cannot be inactivated";

		}else {

			$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

			$SelectedDB = mysql_select_db($this->DBNAME);

			$result = mysql_query($qUpdate); 

			return 1;

		}

		

	} // End: function modify_user

	

	// DELETE USERS

	function delete_user($username) {

		$qDelete = "DELETE FROM  authuser WHERE uname='$username'";	



		if ($username == "sa") {

			return "User sa cannot be deleted.";

		}

		elseif ($username == "admin") {

			return "User admin cannot be deleted.";

		}

		elseif ($username == "test") {

			return "User test cannot be deleted.";

		}



		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		

		$SelectedDB = mysql_select_db($this->DBNAME);

		$result = mysql_query($qDelete); 

	

		return mysql_error();

		

	} // End: function delete_user

	

	// ADD USERS

	function add_user($username, $password, $team, $level, $status, $msj) {

		$qUserExists = "SELECT * FROM authuser WHERE uname='$username'";

		$qInsertUser = "INSERT INTO authuser(uname, passwd, team, level, status, lastlogin, logincount, msj)

				  			   VALUES ('$username', MD5('$password'), '$team', '$level', '$status', '', 0, '$msj')";



		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		

		// Check if all fields are filled up

		if (trim($username) == "") { 

			return "blank username";

		}

		// password check added 09-19-2003

		elseif (trim($password) == "") {

			return "blank password";

		}

		elseif (trim($level) == "") {

			return "blank level";

		}

		

		// Check if user exists

		$SelectedDB = mysql_select_db($this->DBNAME);

		$user_exists = mysql_query($qUserExists); 



		if (mysql_num_rows($user_exists) > 0) {

			return "username exists";

		}

		else {

			// Add user to DB			

			// OLD CODE - DO NOT REMOVE

			// $result = mysql_db_query($this->DBNAME, $qInsertUser);

	

			// REVISED CODE

			$SelectedDB = mysql_select_db($this->DBNAME);

			$result = mysql_query($qInsertUser); 



			return mysql_affected_rows();

		}

	} // End: function add_user





	// *****************************************************************************************

	// ************************************** G R O U P S ************************************** 

	// *****************************************************************************************



	// ADD TEAM

	function add_team($teamname, $teamlead, $status="active") {

		$qGroupExists = "SELECT * FROM authteam WHERE teamname='$teamname'";

		$qInsertGroup = "INSERT INTO authteam(teamname, teamlead, status) 

				  			   VALUES ('$teamname', '$teamlead', '$status')";

		

		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		

		// Check if all fields are filled up

		if (trim($teamname) == "") { 

			return "blank team name";

		}

		

		// Check if group exists

		// OLD CODE - DO NOT REMOVE

		// $group_exists = mysql_db_query($this->DBNAME, $qGroupExists);

		

		// REVISED CODE

		$SelectedDB = mysql_select_db($this->DBNAME);

		$group_exists = mysql_query($qGroupExists); 



		if (mysql_num_rows($group_exists) > 0) {

			return "group exists";

		}

		else {

			// Add user to DB

			// OLD CODE - DO NOT REMOVE

			// $result = mysql_db_query($this->DBNAME, $qInsertGroup);



			// REVISED CODE

			$SelectedDB = mysql_select_db($this->DBNAME);

			$result = mysql_query($qInsertGroup); 



			return mysql_affected_rows();

		}

	} // End: function add_group

	

	// MODIFY TEAM

	function modify_team($teamname, $teamlead, $status) {

		$qUpdate = "UPDATE authteam SET teamlead='$teamlead', status='$status'

					   WHERE teamname='$teamname'";

		$qUserStatus = "UPDATE authuser SET status='$status' WHERE team='$teamname'";



		if ($teamname == "Admin" AND $status=="inactive") {

			return "Admin team cannot be inactivated.";

		}

		elseif ($teamname == "Ungrouped" AND $status=="inactive") {

			return "Ungrouped team cannot be inactivated.";

		}

		else {		

			$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

			

			// UPDATE STATUS IF STATUS OF TEAM IS INACTIVATED

			// OLD CODE - DO NOT REMOVE

			//$userresult = mysql_db_query($this->DBNAME, $qUserStatus);



			// REVISED CODE

			$SelectedDB = mysql_select_db($this->DBNAME);

			$userresult = mysql_query($qUserStatus); 

	

			// OLD CODE - DO NOT REMOVE
			// $result = mysql_db_query($this->DBNAME, $qUpdate);



			// REVISED CODE

			$result = mysql_query($qUpdate); 

	

			return 1;

		}

		

	} // End: function modify_team



	// DELETE TEAM

	function delete_team($teamname) {

		$qDelete = "DELETE FROM authteam WHERE teamname='$teamname'";

		$qUpdateUser = "UPDATE authuser SET team='Ungrouped' WHERE team='$teamname'";	

		if ($teamname == "Admin") {

			return "Admin team cannot be deleted.";

		}

		elseif ($teamname == "Ungrouped") {

			return "Ungrouped team cannot be deleted.";

		}

		elseif ($teamname == "Temporary") {

			return "Temporary team cannot be deleted.";

		}



		$connection = mysql_connect($this->HOST, $this->USERNAME, $this->PASSWORD);

		// OLD CODE - DO NOTE REMOVE

		// $result = mysql_db_query($this->DBNAME, $qUpdateUser);



		// REVISED CODE

		$SelectedDB = mysql_select_db($this->DBNAME);

		$result = mysql_query($qUpdateUser); 



		// OLD CODE - DO NOT REMOVE

		// $result = mysql_db_query($this->DBNAME, $qDelete);

		

		// REVISED CODE

		$result = mysql_query($qDelete); 



		return mysql_error();

		

	} // End: function delete_team





} // End: class auth

?>