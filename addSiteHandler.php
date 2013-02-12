<?php

//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();
/*
This function adds a new site to the sites table and also updates the siteTasks table with
a new record for every task with a status of incomplete
*/
try {

	if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) {
		require_once('functions/connect.php');
		include('functions/functions.php');
		
		$addSiteName = cleanAndPreventInjection($_POST['siteName']);
		$addSiteNumber = cleanAndPreventInjection($_POST['siteNumber']);

		//Strip the malicous tags from any name entered
		$addSiteName = strip_tags($addSiteName);
		$addSiteNumber = strip_tags($addSiteNumber);
		
		
		//Check to see if this site is already in the database.  If it is, don't add it again.  If it is not, continue 
		//with the creation.
		$getLastSiteQuery = "SELECT * FROM site WHERE siteNumber = '" . $addSiteNumber . "'";
		$getLastSiteStatement = $dbh->query($getLastSiteQuery)->fetchAll();
		
		if(count($getLastSiteStatement) == 0) {
			$addSiteQuery = "INSERT INTO site (siteName, siteNumber, pm) VALUES (?, ?, ?)";
			$addSitePrep = $dbh->prepare($addSiteQuery);
			$addSitePrep->execute(array($addSiteName, $addSiteNumber, $_SESSION['SESS_MEMBER_ID']));
			
			//------After adding the site, do another query to get the siteId from the new site we just added
			//------then write another query to get all the tasks in the database and assign "incomplete"
			//------to the newly added site.
			
			$getNewSiteIdQuery = "SELECT * FROM site WHERE siteNumber = '" . $addSiteNumber . "'";
			$getNewSiteIdStatement = $dbh->query($getNewSiteIdQuery)->fetchAll();
			if(count($getNewSiteIdStatement) == 1) {
				foreach($dbh->query($getNewSiteIdQuery)->fetchAll() as $lastSite) {
					$theNewSiteNumber = $lastSite['id'];
				}
				
				try {
			
					$getAllTasks = "SELECT * FROM task";
					//----Put an entry in the siteTasks Table for every task and this one site, 
					//----Which we just added
					foreach($dbh->query($getAllTasks) as $allTasks) {
						$addSiteTasksQuery = "INSERT INTO sitetask (siteId, taskId, taskStage) VALUES (?, ? , 0)";
						$addSiteTasksPrep = $dbh->prepare($addSiteTasksQuery);
						$addSiteTasksPrep->execute(array($theNewSiteNumber, $allTasks['id']));
					}
					
					echo '{"success": true, "message":'.json_encode('New Site and Site Tasks Created') . '}';
			
				} catch (Exception $e) {
				
					echo '{"failure": true, "message":'.json_encode('Error entering records to sitetask table:' + $e->getMessage()) . '}';
				}
			}
			else {
				echo '{"failure": true, "message":'.json_encode('Site not found directly after being added.') . '}';
				break;
			}
			
			
			
		} else if(count($getLastSiteStatement) == 1) {
			echo '{"failure": true, "message":'.json_encode('The site is already in the system') . '}';
		}
		
		
	} else {
		echo '{"failure": true, "message":'.json_encode('Site could not be created, because no one is logged in ') . '}';
	}
} catch (Exception $e) {
	echo '{"failure": true, "message":'.json_encode('General error entering new Site' + $e->getMessage()) . '}';
}
		
	

?>