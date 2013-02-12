

<?php

require_once('../functions/connect.php');
require_once('../functions/functions.php');

try {
	
	session_start();

	$fDate = "2012-08-01";

	$profileQuery = "SELECT * FROM siteprofile WHERE siteId = " . $_POST['siteId'];
	$hasProfile = count($dbh->query($profileQuery)->fetchAll());
	
	//Insert the new Profile if one has not been created
	if($hasProfile > 0) {
		echo '{"failure": true, "msg":'.json_encode('Error Updating') . '}';
	} else {
		$insertProfile = "INSERT INTO siteprofile(siteId, contact, email, phone, migrationWeek, legacySystem, pm, pis, clearingHouse, " .
						 "patientsToAdd, patientIdStartNumber, insMasterStatus, procMasterStatus, refMasterStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$siteId = 0;
		$contact = "";
		$email = "";
		$phone = "";
		$migrationWeek = "";
		$legacySystem = 0;
		$pm = "";
		$pis = "";
		$clearingHouse = 0;
		$patsToAdd = "";
		$patientIdStartNumber = "";
		$insMasterStatus = 0;
		$procMasterStatus = 0;
		$refMasterStatus = 0;
		
		
		if(isset($_POST['siteId']) && $_POST['siteId'] != "") {
			$siteId = $_POST['siteId'];
		}
		
		if(isset($_POST['newProfileContact']) && $_POST['newProfileContact'] != "") {
			$contact = cleanAndPreventInjection($_POST['newProfileContact']);
		}
		
		if(isset($_POST['newProfileEmail']) && $_POST['newProfileEmail'] != "") {
			$email = cleanAndPreventInjection($_POST['newProfileEmail']);
		}
		
		if(isset($_POST['newProfilePhone']) && $_POST['newProfilePhone'] != "") {
			$phone = cleanAndPreventInjection($_POST['newProfilePhone']);
		}
		
		if(isset($_POST['newProfileDate']) && $_POST['newProfileDate'] != "") {
			$migrationWeek = cleanAndPreventInjection($_POST['newProfileDate']);
			$formatDate = new DateTime($migrationWeek);
			$fDate = $formatDate->format('Y-m-d');
		}
		
		if(isset($_POST['newProfilePM']) && $_POST['newProfilePM'] != "") {
			$pm = cleanAndPreventInjection($_POST['newProfilePM']);
		}
		
		if(isset($_POST['newProfilePis']) && $_POST['newProfilePis'] != "") {
			$pis = cleanAndPreventInjection($_POST['newProfilePis']);
		}
		
		if(isset($_POST['newProfileLegacy']) && $_POST['newProfileLegacy'] != "") {
			$legacySystem = $_POST['newProfileLegacy'];
		}
		
		if(isset($_POST['newProfileClearinghouse']) && $_POST['newProfileClearinghouse'] != "") {
			$clearingHouse = $_POST['newProfileClearinghouse'];
		}
		
		if(isset($_POST['patsToAdd']) && $_POST['patsToAdd'] != "") {
			$patsToAdd = cleanAndPreventInjection($_POST['patsToAdd']);
		}
		
		if(isset($_POST['numberToStartPats']) && $_POST['numberToStartPats'] != "") {
			$patientIdStartNumber = cleanAndPreventInjection($_POST['numberToStartPats']);
		}

		$insertProfilePrep = $dbh->prepare($insertProfile);
		$insertProfilePrep->execute(array($siteId, $contact, $email, $phone, $fDate, $legacySystem, $pm, $pis, $clearingHouse, $patsToAdd, $patientIdStartNumber,$insMasterStatus, $procMasterStatus, $refMasterStatus));
		
		echo '{"success": true, "message":'.json_encode('New Profile Created') . '}';
		$_SESSION['PROFILE_ADDED'] = 'profile added';

	
	}
	
	
} catch (Exception $e) {
	echo '{"failure": true, "message":'.json_encode('Error Updating') . '}';
	
}

?>