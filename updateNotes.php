<?php
//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();

//Login Section
if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) {
	
	//connect to the database
	require_once('functions/connect.php');
	
	$siteId 		= $_POST['siteId'];
	//$noteType		= $_POST['noteType'];
	$profileNotes	= $_POST['profileNotes'];
	
	try {
		
		$qry = "UPDATE siteprofile SET profileNotes WHERE siteId = ?";
		$stmt = $dbh->prepare($qry);
		$stmt->execute(array($siteId));
	
		echo '{"success": true, "message":'. json_encode('Profile Note Saved') . '}';
	
	} catch(Exception $e) {
		
		echo '{"failure": true, "message":'. json_encode('Query did not run') . '}';
		
	}
} else {

	echo '{"failure": true, "message":'. json_encode('No Session Available') . '}';

}
	
	

?>


