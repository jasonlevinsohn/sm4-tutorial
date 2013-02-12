<?php
	echo 'lets archive site ' . $_GET['archiveSite'];
	require_once('functions/connect.php');
	$updateArchiveQuery = "UPDATE site SET isArchive=1 WHERE id=?";
	$updateArchivePrep = $dbh->prepare($updateArchiveQuery);
	$updateArchivePrep->execute(array($_GET['archiveSite']));
	
	
?>

<!-- Reload the page now -->

<META HTTP-EQUIV="refresh" CONTENT="0">