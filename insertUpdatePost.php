<?php
//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();

//Login Section
if(isset($_POST['title']) && isset($_POST['message']) && isset($_SESSION['SESS_FIRST_NAME'])) {
	
	//connect to the database
	require_once('functions/connect.php');
	
	//Get Post Type
	if(isset($_POST['postType'] && $_POST['postType'] == 'mainPost')) {
		
		$title   = $_POST['title'];
		$message = $_POST['message']; 
		$author  = $_SESSION['SESS_FIRST_NAME'];
		$currentTime = date("y/m/d : H:i:s", time());
		$qry = "INSERT INTO mainposts (title, posttext, author, timestamp) VALUES (?, ?, ?, ?)";
		$stmt = $dbh->prepare($qry);
		$stmt->execute(array($title, $message, $author, $currentTime));
		
		echo '{"success": true, "message":' . json_encode('Post has been added') . '}';
	
	} else {
		echo '{"failure": true, "message":' . json_encode('There is no post type for' . $_POST['postType']) . '}';
	}
} else {
	echo '{"failure": true, "message":' . json_encode('The title and message fields are not set or you are not logged in') . '}';
}
	
	

//End Login Section

?>


