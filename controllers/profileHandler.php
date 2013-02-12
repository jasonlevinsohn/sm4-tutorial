

<?php

require_once('../functions/connect.php');
include('../functions/functions.php');

try {


	session_start();
	//echo 'The values are task: ' .$_POST['task'] . ' taskCombo: ' . $_POST['taskCombo'] . ' and order check box ' . $_POST['orderCheckBox'];
	
	
	//echo "Contact Name is: " . $_POST['contact'] . " and id is: " . $_POST['id'];
	
	
	//If the order check box is true, we want to 
	//insert the new task before the task combo id
	$isId = false;
	$isUpdateLegacyNotes = false;
	
	
	$id = 0;
	$contact = "";
	$fDate = "2012-08-01";
	$email = "";
	$cleanPhone = "";
	$pm = 0;
	$pis = "";
	$legacy = 0;
	$clearing = 0;
	$patsToAdd = "";
	$numToStartPats = "";
	$legacyNotes = "";
	
	
	//**********Check to see if we want to update the legacy Notes or not?**********
	if(isset($_POST['updateLegacyNotes']) && $_POST['updateLegacyNotes'] != "") {
		$isUpdateLegacyNotes = true;
		
	}
	
		
	
	if(isset($_POST['contact']) && $_POST['contact'] != "" && isset($_POST['id']) && $_POST['id'] != "") {
		$id = $_POST['id'];
		$contact = cleanAndPreventInjection($_POST['contact']);
		$isId = true;
		
	}
	
	if(isset($_POST['date']) && $_POST['date'] != "") {
		$migDate = cleanAndPreventInjection($_POST['date']);
		$formatDate = new DateTime($migDate);
		$fDate = $formatDate->format('Y-m-d');
	}
	
	if(isset($_POST['email']) && $_POST['email'] != "") {
		$email = cleanAndPreventInjection($_POST['email']);
	}
	
	if(isset($_POST['phone']) && $_POST['phone'] != "") {
		$phone = cleanAndPreventInjection($_POST['phone']);
		$cleanPhone = preg_replace("/[^0-9]/","",$phone);
	}
	
	if(isset($_POST['pm']) && $_POST['pm'] != "") {
		$pm = $_POST['pm'];
	}
	
	if(isset($_POST['pis']) && $_POST['pis'] != "") {
		$pis = cleanAndPreventInjection($_POST['pis']);
	}
	
	if(isset($_POST['legacy']) && $_POST['legacy'] != "") {
		$legacy = $_POST['legacy'];
	}
	
	if(isset($_POST['clearinghouse']) && $_POST['clearinghouse'] != "") {
		$clearing = $_POST['clearinghouse'];
	}
	
	if(isset($_POST['patsToAdd']) && $_POST['patsToAdd'] != "") {
		$patsToAdd = cleanAndPreventInjection($_POST['patsToAdd']);
	}
	
	if(isset($_POST['numberToStartPats']) && $_POST['numberToStartPats'] != "") {
		$numToStartPats = cleanAndPreventInjection($_POST['numberToStartPats']);
	}
	
	if(isset($_POST['legacyNotes']) && $_POST['legacyNotes'] != "") {
		$legacyNotes = cleanAndPreventInjection($_POST['legacyNotes']);
	}
	
	if ($isId) {
		
		if($isUpdateLegacyNotes) {
		   
			$updateLegacyNotesQuery = "UPDATE legacysystem SET notes = ? WHERE id = ?";
			$updateLegacyNotesPrep = $dbh->prepare($updateLegacyNotesQuery);
			$updateLegacyNotesPrep->execute(array($legacyNotes, $legacy));
			
			echo '{"success": true, "message":'.json_encode('Legacy Notes Updated') . '}';
		
		} else {
		
			$updateProfileQuery = "UPDATE siteprofile SET contact = ?, email = ?, phone = ?, migrationWeek = ?, " .
							  "pm = ?, pis = ?, legacySystem = ?, clearingHouse = ?, patientsToAdd = ?, patientIdStartNumber = ? WHERE siteId = ?";
			$updateProfilePrep = $dbh->prepare($updateProfileQuery);
			$updateProfilePrep->execute(array($contact, $email, $cleanPhone, $fDate, $pm, $pis, $legacy, $clearing, $patsToAdd, $numToStartPats, $id));
		
			echo '{"success": true, "message":'.json_encode('Profile Updated') . '}';
			$_SESSION['PROFILE_ADDED'] = 'profile added';
		}
		
		
		
		
		
	} else {
	
		echo '{"success": false, "message":'.json_encode('Error Updating Profile') . '}';
		
	}
	
	
	
} catch (Exception $e) {
	echo '{"success": true, "message":'.json_encode('Error Updating') . '}';
	
}

?>