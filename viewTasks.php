<?php
			//Load the Tasks
			require_once('functions/connect.php');
			
			if(isset($_POST['startSite']) && isset($_POST['limitSite'])) {
			
				$startSite = $_POST['startSite'];
				$limitSite = $_POST['limitSite'];
				$siteQuery = "SELECT * from site ";
				$siteQuery .= " LEFT JOIN siteProfile ON site.id = siteProfile.siteId ";
				$siteQuery .= " WHERE site.isArchive = 0 ORDER BY siteProfile.migrationWeek ASC LIMIT " . $startSite . ", " . $limitSite ;
			} else {
				$siteQuery = "SELECT * from site ";
				$siteQuery .= " LEFT JOIN siteProfile ON site.id = siteProfile.siteId ";
				$siteQuery .= " WHERE site.isArchive = 0 ORDER BY siteProfile.migrationWeek ASC";
			}
			
			
			$getSitesStatement = $dbh->query($siteQuery)->fetchAll();
			//$getSiteCount = count($getSitesStatement);
			//foreach($getSitesStatement as $siteRow)
			
			$taskQuery = "SELECT * from task ORDER BY orderNumber";
			$getTasksStatement = $dbh->query($taskQuery);
		?>	
		<!-- Local CSS -->
		<style type="text/css">
			
			
			.archiveButton {
				border: 1px solid #000;
				cursor: pointer;
				
				float: left;
				width: 48%;
				margin-right: 2%;
				text-align: center;
			}
			
			.deleteButton {
				border: 1px solid #000;
				cursor: pointer;
				float: right;
				width: 48%;
				margin-left: 2%;
				text-align: center;
			}
		</style>
		<!-- Ext JS Scripts -->
		
		<h1 id="title"></h1>
		
		
		<?php
			//Test to see if there are any Sites to Load
			if(count($dbh->query($siteQuery)->fetchAll()) > 0) {
		?>
		<table>
			<tr class="header-table">
				<th class="contentTable">Site</th>
				<?php
					$siteCounter = 0;
					foreach($getSitesStatement as $siteRow) { 
						//Get Site Id's for getting the tasks later on
						$siteNumberArray[$siteCounter] = $siteRow[0];
						$siteNameArray[$siteCounter] = $siteRow[1];
						$siteIdArray[$siteCounter] = $siteRow[2];
						$siteCounter++;
				?>
					
					
					<th id="<?php echo 'site' . $siteRow[0] ?>" class="contentTable"><?php echo $siteRow[2] . ' - ' . $siteRow[1];?>
						<div id="siteButtons">
							
						</div>
					</th>
				<?php } ?>	
			</tr>
			<tr class="contentTable">
				<td class="contentTable">Tasks</td>
				
				<!-- For each site we have add more column headers -->
				<?php foreach($dbh->query($siteQuery) as $siteRow) { ?>
					<td class="taskMain">Status</td>
					
				<?php } ?>	
			
			</tr>
			<?php foreach($getTasksStatement as $taskRow) { ?>
				<tr class="contentTable">
					<td class="contentTable" id="taskRow<?php echo $taskRow[0];?>"><?php echo $taskRow[1]; ?></td>
						
						<?php
						//Write query for each task
						
						
						
						for($i = 0; $i < count($siteNumberArray); $i++) {
							
							$eachTaskQuery = "SELECT * FROM sitetask INNER JOIN site on sitetask.siteId = site.id WHERE sitetask.siteId = " . $siteNumberArray[$i] . " AND sitetask.taskId = " . $taskRow[0] . " AND site.isArchive = 0";
							
							
							if(count($dbh->query($eachTaskQuery)) == 1) {
								foreach($dbh->query($eachTaskQuery) as $eachTask) {
								
								
								?>
								<script>
									//Check task row
									Ext.onReady(function() {
									
										var onSuccessOrFail = function(form, action) {
											var cf<?php echo $eachTask[0]; ?> = Ext.getCmp('changeForm<?php echo $eachTask[0]; ?>');
											cf<?php echo $eachTask[0]; ?>.el.unmask();
										}
											
										var incompleteHandler<?php echo $eachTask[0]; ?> = function() {
											//Ext.MessageBox.alert('Status Changed to Incomplete');
											var cf<?php echo $eachTask[0]; ?> = Ext.getCmp('changeForm<?php echo $eachTask[0]; ?>');
											var changeIncompleteTask<?php echo $eachTask[0] ?> = Ext.get('task<?php echo $eachTask[0]; ?>');
											cf<?php echo $eachTask[0]; ?>.el.mask('Please wait', 'x-mask-loading');
											cf<?php echo $eachTask[0]; ?>.getForm().submit({
												params	: {
													taskStage	: 'incomplete',
													taskId		: '<?php echo $eachTask[0]; ?>'
												},
												success	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
													changeWindow<?php echo $eachTask[0]; ?>.hide();
													//location.reload(true);
													changeIncompleteTask<?php echo $eachTask[0] ?>.removeCls('notApplicable');
													changeIncompleteTask<?php echo $eachTask[0] ?>.removeCls('taskComplete');
													changeIncompleteTask<?php echo $eachTask[0] ?>.removeCls('taskPending');
													changeIncompleteTask<?php echo $eachTask[0] ?>.addCls('taskIncomplete');
													changeIncompleteTask<?php echo $eachTask[0] ?>.update('Incomplete');
												},
												failure	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
												}
											});
										}
										
										
										
										var completeHandler<?php echo $eachTask[0]; ?> = function() {
											//Ext.MessageBox.alert('Status Changed to Complete');
											var cf<?php echo $eachTask[0]; ?> = Ext.getCmp('changeForm<?php echo $eachTask[0]; ?>');
											var changeCompleteTask<?php echo $eachTask[0] ?> = Ext.get('task<?php echo $eachTask[0]; ?>');
											cf<?php echo $eachTask[0]; ?>.el.mask('Please wait', 'x-mask-loading');
											cf<?php echo $eachTask[0]; ?>.getForm().submit({
												params	: {
													taskStage	: 'complete',
													taskId		: '<?php echo $eachTask[0]; ?>'
												},
												success	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
													changeWindow<?php echo $eachTask[0]; ?>.hide();
													//location.reload(true);
													changeCompleteTask<?php echo $eachTask[0] ?>.removeCls('taskIncomplete');
													changeCompleteTask<?php echo $eachTask[0] ?>.removeCls('notApplicable');
													changeCompleteTask<?php echo $eachTask[0] ?>.removeCls('taskPending');
													changeCompleteTask<?php echo $eachTask[0] ?>.addCls('taskComplete');
													changeCompleteTask<?php echo $eachTask[0] ?>.update('Complete');
													
													
												},
												failure	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
												}
											});
										}	
										
										var pendingHandler<?php echo $eachTask[0]; ?> = function() {
											//Ext.MessageBox.alert('Status Changed to Pending');
											var cf<?php echo $eachTask[0]; ?> = Ext.getCmp('changeForm<?php echo $eachTask[0]; ?>');
											var changePendingTask<?php echo $eachTask[0] ?> = Ext.get('task<?php echo $eachTask[0]; ?>');
											cf<?php echo $eachTask[0]; ?>.el.mask('Please wait','x-mask-loading');
											cf<?php echo $eachTask[0]; ?>.getForm().submit({
												params	: {
													taskStage	:	'pending',
													taskId		: 	'<?php echo $eachTask[0]; ?>'
												},
												success	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
													changeWindow<?php echo $eachTask[0]; ?>.hide();
													//location.reload(true);
													changePendingTask<?php echo $eachTask[0] ?>.removeCls('taskIncomplete');
													changePendingTask<?php echo $eachTask[0] ?>.removeCls('notApplicable');
													changePendingTask<?php echo $eachTask[0] ?>.removeCls('taskComplete');
													changePendingTask<?php echo $eachTask[0] ?>.addCls('taskPending');
													changePendingTask<?php echo $eachTask[0] ?>.update('Pending');
												},
												failure	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
												}
											});
										}
										
										var naHandler<?php echo $eachTask[0]; ?> = function() {
											//Ext.MessageBox.alert('Status Changed to Pending');
											var cf<?php echo $eachTask[0]; ?> = Ext.getCmp('changeForm<?php echo $eachTask[0]; ?>');
											var changeNaTask<?php echo $eachTask[0] ?> = Ext.get('task<?php echo $eachTask[0]; ?>');
											cf<?php echo $eachTask[0]; ?>.el.mask('Please wait','x-mask-loading');
											cf<?php echo $eachTask[0]; ?>.getForm().submit({
												params	: {
													taskStage	:	'na',
													taskId		: 	'<?php echo $eachTask[0]; ?>'
												},
												success	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
													changeWindow<?php echo $eachTask[0]; ?>.hide();
													//location.reload(true);
													changeNaTask<?php echo $eachTask[0] ?>.removeCls('taskIncomplete');
													changeNaTask<?php echo $eachTask[0] ?>.removeCls('taskComplete');
													changeNaTask<?php echo $eachTask[0] ?>.removeCls('taskPending');
													changeNaTask<?php echo $eachTask[0] ?>.addCls('notApplicable');
													changeNaTask<?php echo $eachTask[0] ?>.update('N/A');
												},
												failure	: function() {
													cf<?php echo $eachTask[0]; ?>.el.unmask();
												}
											});
										}
											
										var changeForm<?php echo $eachTask[0]; ?> = new Ext.form.FormPanel({
											/* renderTo	: changeWindow<?php echo $eachTask[0]; ?>,  */
											/* renderTo	: Ext.getBody(), */
											/* width		: 250, */
											/* height		: 100, */
											
											frame		: true,
											id			: 'changeForm<?php echo $eachTask[0]; ?>',
											url 		: 'updateTaskJSON.php',
											/* labelWidth	: 126, */
											defaultType	: 'textfield',
											style		: 'padding-right: 20px;',
											buttons		:  [
													{
														text	: 'N/A',
														handler	: naHandler<?php echo $eachTask[0]; ?>		
													},
													'-',
													{
														text	: 'Incomplete',
														handler	: incompleteHandler<?php echo $eachTask[0]; ?>
													},
													{
														text	: 'Pending',
														handler	: pendingHandler<?php echo $eachTask[0]; ?>
													},
													{
														text	: 'Complete',
														handler	: completeHandler<?php echo $eachTask[0]; ?>
													}
													
												]
											
											
										});
										
										
										
										
										
										var changeWindow<?php echo $eachTask[0]; ?> = new Ext.Window({
												/* html		: 'My first button window called changeWindow<?php echo $eachTask[0]; ?>' , */
												closeAction	: 'hide',
												title		: 'Site: <?php echo $siteIdArray[$i] . ' - ' . $siteNameArray[$i]; ?>',
												/* layout		: 'fit',  */
												height		: 100,
												width		: 400,
												constrain	: true,
												style		: 'text-align: center;',
												/* html		: 'Change status for task:<br /><b> <?php echo $taskRow[1]; ?></b>', */
												items		: [
														{ html : 'Change status for task:<br /><b> <?php echo $taskRow[1]; ?></b>'
														},
														changeForm<?php echo $eachTask[0]; ?>
												]
												
										});
										
										
										
										var myTasks = Ext.get('task<?php echo $eachTask[0]; ?>');
										
										myTasks.on('click', function(eventObj, elRef) {
											
											changeWindow<?php echo $eachTask[0]; ?>.show();
										});
										
										myTasks.on('mouseover', function(eventObj, elRef) {
											console.log('You found the tasks: ' + elRef.id);
										});
										
										
									});	
								</script>
								
								<?php
									if($eachTask['taskStage'] == 0) { ?>
										<td id="task<?php echo $eachTask[0];?>" class="taskIncomplete" style="cursor: pointer;">Incomplete</td>
									<?php } elseif($eachTask['taskStage'] == 1) { ?>
										<td id="task<?php echo $eachTask[0];?>" class="taskPending" style="cursor: pointer;">Pending</td>
									<?php } elseif($eachTask['taskStage'] == 2) { ?>
										<td id="task<?php echo $eachTask[0];?>" class="taskComplete" style="cursor: pointer;">Complete</td>
									<?php } elseif($eachTask['taskStage'] == 3) { ?>
										<td id="task<?php echo $eachTask[0];?>" class="notApplicable" style="cursor: pointer;">N/A</td>
									<?php } else { ?>
										<td class="contentTable">N/A</td>
									<?php } 
								}
								
							} else { ?>
								<td class="contentTable">N/A - Error</td>
							<?php } 
						} //End $siteNumberArray FOR LOOP
						
						
					?>
				</tr>
			<?php } ?>	
		</table>
		<?php } else {
			echo 'No Sites to Load';
		
		}?>
		
		