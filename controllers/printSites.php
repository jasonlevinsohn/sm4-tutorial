<!DOCTYPE html>
<html>
<head>


<?php
//Query the database
require_once('../functions/connect.php');

$printQuery  = "select site.siteNumber as number ";
$printQuery .= ", site.siteName as name ";
$printQuery .= ", siteprofile.migrationWeek as migWeek ";
$printQuery .= ", pm.name as pm ";
$printQuery .= ", siteprofile.PIS as pis ";
$printQuery .= ", legacysystem.name as legacy ";
$printQuery .= ", clearinghouse.name as clearing ";
$printQuery .= ", siteprofile.contact as contact ";
$printQuery .= ", siteprofile.email as email ";
$printQuery .= ", siteprofile.phone as phone ";
$printQuery .= "FROM site ";
$printQuery .= "INNER JOIN siteprofile on site.id = siteprofile.siteid ";
$printQuery .= "LEFT JOIN pm on siteprofile.pm = pm.id ";
$printQuery .= "LEFT JOIN legacysystem on siteprofile.legacysystem = legacysystem.id ";
$printQuery .= "LEFT JOIN clearinghouse on siteprofile.clearingHouse = clearinghouse.id ";
$printQuery .= "WHERE site.isArchive = 0 ";
$printQuery .= "ORDER BY migrationWeek ASC ";



//echo $printQuery;

$printStatement = $dbh->query($printQuery);



?>


<title>Jason's Current Sites</title>
<!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../extjs/ext-4-0-2a/resources/css/ext-all.css">
	
<style>

.tableHeader {
	font-size	: 16pt;
	font-family	: Calibri, sans-serif;
	text-align	: center;
	border		: 1px solid #000;
	padding		: 2px 5px 2px 5px;
	

}

.tableDetail {
	font-size	: 14pt;
	font-family	: Calibri, sans-serif;
	text-align	: center;
	border		: 1px solid #000;
	padding		: 2px 5px 2px 5px;

}

h1 {
	font-family	: Calibri, sans-serif;
	font-size	: 20pt; 	
	margin-left	: 100px;

}

</style>

<!-- JavaScript -->
    <script type="text/javascript">
	
	
	</script>

</head>

<body>


<h1>Jason's Current Sites</h1>
<table id="container">
	<tr>
		<th class="tableHeader">Number</th>
		<th class="tableHeader">Name</th>
		<th class="tableHeader">Mig Date</th>
		<th class="tableHeader">PM</th>
		<th class="tableHeader">PIS</th>
		<th class="tableHeader">Legacy</th>
		<th class="tableHeader">Clearing</th>
		<th class="tableHeader">Contact</th>
		<th class="tableHeader">Email</th>
		<th class="tableHeader">Phone</th>
	</tr>
	<?php $colorSwitchCounter = 0; ?>
	<?php foreach($printStatement as $profile) { ?>
		<?php
			//Color Interval for projects
			if($colorSwitchCounter%2) {
				$rowColor = '#FFFFFF';
			} else {
				$rowColor = '#C7EDD7';
			}
			$colorSwitchCounter++;
		?>
		
		<?php
			//Format Phone Number
			$pn = $profile['phone'];
			$formattedPhone = "(" . substr($pn, 0, 3) . ") " . substr($pn, 3, 3) . "-" . substr($pn, 6, 4);
		?>
		
		<tr>
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['number']?></td>
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['name']?></td>	
			<td class="tableDetail" <?php echo 'style="min-width: 95px;background-color: ' . $rowColor . '"'; ?>><?php echo $profile['migWeek']?></td>		
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['pm']?></td>		
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['pis']?></td>		
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['legacy']?></td>					
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['clearing']?></td>
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['contact']?></td>
			<td class="tableDetail" <?php echo 'style="background-color: ' . $rowColor . '"'; ?>><?php echo $profile['email']?></td>
			<td class="tableDetail" <?php echo 'style="min-width: 125px;background-color: ' . $rowColor . '"'; ?>><?php echo $formattedPhone ?></td>
		</tr>
		
	<?php } ?>


</table> <!-- End Main Container -->

</body>

</html>