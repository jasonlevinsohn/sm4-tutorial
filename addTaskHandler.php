

<?php

require_once('functions/connect.php');
include('functions/functions.php');

try {
	//echo 'The values are task: ' .$_POST['task'] . ' taskCombo: ' . $_POST['taskCombo'] . ' and order check box ' . $_POST['orderCheckBox'];
	
	
	//The order number we will insert
	$orderNumberToInsert = 0;
	
	//If the order check box is true, we want to 
	//insert the new task before the task combo id
	if(isset($_POST['orderCheckBox']) && $_POST['orderCheckBox'] && isset($_POST['task']) && $_POST['task'] != "") {
		$getOrderTaskQuery = "SELECT * FROM task WHERE id=" . $_POST['taskCombo'];
		$getOrderTaskStatement = $dbh->query($getOrderTaskQuery)->fetchAll();
		if(count($dbh->query($getOrderTaskQuery)->fetchAll()) == 1) {
			
			
			foreach($getOrderTaskStatement as $ordered) {
				
			
				$getTasksHigherQuery = "SELECT * FROM task WHERE orderNumber >= " . $ordered['orderNumber'];
				$getTasksHigherStatement = $dbh->query($getTasksHigherQuery);
				//Increase the order number by 1 for all the tasks that have
				//a higher order number than the one we have choosen
				foreach($getTasksHigherStatement as $higher) {
					//echo "Ordered Number: " . ($higher['orderNumber'] + 1) . " id number: " . $higher['id'] . " ";
					
					$raiseUpdate = "UPDATE task SET orderNumber = ? WHERE id = ?";
					$raiseUpdatePrep = $dbh->prepare($raiseUpdate);
					$raiseUpdatePrep->execute(array(($higher['orderNumber'] + 1), $higher['id']));
					
				}
				
				//Set the order number to insert
				$orderNumberToInsert = $ordered['orderNumber'];
				
			}
			
		} else {
			echo '{"failure": true, "msg":'.json_encode('Error getting Ordered Task') . '}';
		}
		
	} else if (isset($_POST['task']) && $_POST['task'] != "") {
		//Otherwise we, just want to add a new task at the end of the task list.
		
		//Get the highest order number
		$highNumber = 0;
		$getHighestOrderQuery = "SELECT MAX(orderNumber) as theMax FROM task";
		$getHighestOrderStatement = $dbh->query($getHighestOrderQuery);
		foreach($getHighestOrderStatement as $glory) {
			$highNumber = $glory['theMax'];
		}
		
		
		$orderNumberToInsert = $highNumber + 1;
		
		
	}
	
	//Insert the new task at the order number we specified
	$newTask = cleanAndPreventInjection($_POST['task']);

	//Strip the malicous tags from any name entered
	$newTask = strip_tags($newTask);

	$insertNewTask = "INSERT INTO task (taskName, orderNumber) VALUES (?, ?)";
	$insertNewTaskPrep = $dbh->prepare($insertNewTask);
	$insertNewTaskPrep->execute(array($newTask, $orderNumberToInsert));
	
	//Now we need to create a record in sitetask for each site and this task
	try {
		//Get the most recently created Task Id.
		$getNewTaskQuery = "SELECT * FROM task WHERE taskName = '" . $newTask . "'";
		$getNewTaskStatement = $dbh->query($getNewTaskQuery)->fetchAll();
		if(count($getNewTaskStatement) == 1) {
			foreach($getNewTaskStatement as $newTask) {
				$newTaskId = $newTask['id'];
			}	
		} else {
			echo 'There was an error getting the last task created';
		}
		
		//Add task to every site
		$getAllSitesQuery = "SELECT * FROM site WHERE isArchive = 0";
		foreach($dbh->query($getAllSitesQuery) as $allSites) {
			
			$addTaskToSites = "INSERT INTO sitetask (siteId, taskId, taskStage) VALUES (?, ?, 0)";
			$addTaskToSitesPrep = $dbh->prepare($addTaskToSites);
			$addTaskToSitesPrep->execute(array($allSites['id'], $newTaskId));
		}
		
	} catch (Exception $e) {
		echo '{"failure": true, "message":'.json_encode('Error adding records to the sitetask table') . '}';
	}
	
	echo '{"success": true, "message":'.json_encode('New Task Created') . '}';
	
} catch (Exception $e) {
	echo '{"failure": true, "message":'.json_encode('Error Updating') . '}';
	
}

?>