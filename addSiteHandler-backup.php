<?php
/*
This function adds a new site to the sites table and also updates the siteTasks table with
a new record for every task with a status of incomplete
*/
try {
		session_start();
		require_once('functions/connect.php');
		include('functions/functions.php');
		
		$addSiteName = cleanAndPreventInjection($_POST['siteName']);
		$addSiteNumber = cleanAndPreventInjection($_POST['siteNumber']);
	
		$addSiteQuery = "INSERT INTO site (siteName, siteNumber) VALUES (?, ?)";
		$addSitePrep = $dbh->prepare($addSiteQuery);
		$addSitePrep->execute(array($addSiteName, $addSiteNumber));
		
		try {
			//------After adding the site, do another query to get the siteId from the new site we just added
			//------then write another query to get all the tasks in the database and assign "incomplete"
			//------to the newly added site.
	
			$getLastSiteQuery = "SELECT * FROM site WHERE siteNumber = '" . $addSiteNumber . "'";
		
			//-----Here get the site id, only if the count is one.
			$getLastSiteStatement = $dbh->query($getLastSiteQuery)->fetchAll();
			if(count($getLastSiteStatement) == 1) {
				foreach($dbh->query($getLastSiteQuery)->fetchAll() as $lastSite) {
					$theNewSiteNumber = $lastSite['id'];
				}
			}
			else {
				echo 'The new site was not entered';
				break;
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
				
				$_SESSION['SITE_ADDED'] = 'siteadded';
			
			} catch (Exception $e) {
				
				echo '{"failure": true, "message":'.json_encode('Error entering records to sitetask table:' + $e->getMessage()) . '}';
			}
			
		} catch (Exception $e) {
			
			echo '{"failure": true, "message":'.json_encode('Error getting the new site: ' . $e->getMessage()) . '}';
		
		}
		
	} catch (Exception $e) {
		
		echo '{"failure": true, "message":'.json_encode('Error entering a new site: ' . $e->getMessage()) . '}';
	}

?>