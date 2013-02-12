<?php
//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();

//Login Section
if(isset($_POST['user']) && isset($_POST['pass'])) {
	
	//connect to the database
	require_once('functions/connect.php');
	$login = $_POST['user'];
	$pass = md5($_POST['pass']);
	
	$qry = "SELECT * FROM users where user = '$login' AND pass = '$pass'";
	//echo $qry;
	$statement = $dbh->query($qry)->fetchAll();
	
	//echo '{"success": true, "message":"you logged in", "userName":"' . $login . '"}';
	$foundUser = 0;
	
	
	//echo 'count is: '. count($statement);
	
	if(count($statement) == 1) {
		foreach($dbh->query($qry) as $row) {
			
			session_regenerate_id();
			$foundUser = 1;
			$_SESSION['SESS_MEMBER_ID'] = $row['id'];
			$_SESSION['SESS_FIRST_NAME'] = $row['firstName'];
			
			//echo 'session id is: ' . $_SESSION['SESS_MEMBER_ID'];
			
		}
		if(isset($_SESSION['SESS_MEMBER_ID']))
		{
			//Record the login with a timestamp
			try {
				$currentTime = date("y/m/d : H:i:s", time());
				$updateUserTimeStamp = "UPDATE users SET timestamp=? WHERE id=?";
				$updateUserPrep = $dbh->prepare($updateUserTimeStamp);
				$updateUserPrep->execute(array($currentTime, $_SESSION['SESS_MEMBER_ID']));
				
				echo '{"success": true, "message":'.json_encode('You are logged in: ' . $_SESSION['SESS_MEMBER_ID']) . ', "userName":"' . $row['firstName'] . '"}';
			
			} catch(Exception $e) {
				
				echo '{"failure": true, "message":'.json_encode('Could not update Timestamp: ' . $e->getMessage()) . '}';
			}
		} else {
			
			echo '{"failure": true, "message":'.json_encode('No user and password found') . '}';
		}
		
	} else {
	 	
		echo '{"failure": true, "message":'.json_encode('That user does not exist') . '}';
	}
} else if(isset($_POST['logout']) && $_POST['logout'] == 1) {
	echo '{"success": true, "message":'.json_encode('You are logged out') . '}';
	session_unset();
	session_destroy();
} else {
	echo '{"failure": true, "message":'.json_encode('Error Updating') . '}';
}
//End Login Section

?>


