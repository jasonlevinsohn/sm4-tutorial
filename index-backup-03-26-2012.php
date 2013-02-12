<!DOCTYPE html>
<html>
<head>
<title>Jason's Projection Project</title>
<link rel="favicon" type="image/x-icon" href="favicon.ico">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<script type="text/javascript">


</script>
<link rel="stylesheet" type="text/css" href="../projection/extjs/ext-4-0-2a/resources/css/ext-all.css">
<style type="text/css">


#wrapper {
	padding: 10px 10px 10px 10px;
	background-color: #A4C639;
	font-family: "Comic Sans MS", cursive, sans-serif;

}

#content {
	background-color: #F0FFFF;
	padding: 10px 10px 10px 10px;
}

#title {
	border-bottom: 3px solid #000;
	clear: both;
	

}

#loginSection {
	float: right; 
	margin-bottom: 20px;
	
	
}

.header-table {
	border: 1px solid #000;
}

.taskGrid {
	background-color: #F0FFFF;
}

.contentTable {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
}

.taskMain {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
}

.taskIncomplete {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
	cursor: default;
	background-color: #FF9999;
}

.taskPending {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
	cursor: default;
	background-color: #FFFF99;
}

.taskComplete {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
	cursor: default;
	background-color: #99FF99;
}

.notApplicable {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
	cursor: default;
	background-color: #D4D4D4;
}


</style>
<!-- JavaScript -->
    <script type="text/javascript" src="../projection/extjs/ext-4-0-2a/ext-all-debug.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	
	<script type="text/javascript">
		Ext.onReady(function() {
			
			
		});
		
		
		
	</script>
</head>

<body>

<?php
//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();

//Connect to the database
require_once('functions/connect.php');

$title = 'Jason\'s Projection Project';
/*
Add a new task to the database. When we add a new task, we also need to create
a record in the siteTasks table for every site in the database.  
*/
if (isset($_GET['addTaskText']) && isset($_GET['addTaskToEverySite'])) {
	
	//include('addTasks.php');
	
}


/* 
Add a new site to the database.  When we add a new site, we also need to create
a record in the siteTasks table for every Task in the database.  This makes the code 
longer and a little more complicated.
*/
if(isset($_GET['addSiteNameText']) && isset($_GET['addSiteNumberText'])) {
	
	//include('addSites.php');

} //--End Entering New Site

//Login Section
if(isset($_GET['userInput']) && isset($_GET['passInput'])) {
	
	
	$login = $_GET['userInput'];
	$pass = md5($_GET['passInput']);
	$qry = "SELECT * FROM users where user = '$login' AND pass = '$pass'";
	echo $qry;
	$foundUser = 0;
	$statement = $dbh->query($qry);
	
	echo 'count is: '. count($statement);
	if(count($statement) == 1) {
		foreach($dbh->query($qry) as $row) {
			
			session_regenerate_id();
			$foundUser = 1;
			$_SESSION['SESS_MEMBER_ID'] = $row['id'];
			$_SESSION['SESS_FIRST_NAME'] = $row['firstName'];
			
			echo 'session id is: ' . $_SESSION['SESS_MEMBER_ID'];
			
		}
		if(isset($_SESSION['SESS_MEMBER_ID']))
		{
			//Record the login with a timestamp
			try {
				$currentTime = date("d/m/y : H:i:s", time());
				$updateUserTimeStamp = "UPDATE users SET timestamp=? WHERE id=?";
				$updateUserPrep = $dbh->prepare($updateUserTimeStamp);
				$updateUserPrep->execute(array($currentTime, $_SESSION['SESS_MEMBER_ID']));
			} catch(Exception $e) {
				echo 'Could not update Timestamp: ' . $e->getMessage();
			}
		} else {
			echo 'No user and password found';
		}
		
	} else {
	 	echo 'That user does not exist';
	}
	
} else if(isset($_GET['loginSubmit']) && $_GET['loginSubmit'] == 'logout') {
	session_unset();
	session_destroy();
}
//End Login Section

?>
<div id="wrapper">
	<div id="content">
	
		<!-- BEGIN LOGIN/LOGOUT SCRIPTS -->
		<div id="loginSection">
			<?php if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { ?>
				<div id="logout">
					
					<form name="logout" id="logout" method="get" action="index.php">
						<?php echo 'hello, ' . $_SESSION['SESS_FIRST_NAME'];?>
						<input type="submit" name="loginSubmit" id="loginSubmit" value="logout" />
					</form>
				</div>
			<?php } else { ?>	
				<div id="login">
					<form name="login" id="login" method="get" action="index.php">
						<span id="userSpan" class="login">user:</span>
						<input type="text" id="userInput" name="userInput" />
						<span id="passSpan" class="login">pass:</span>
						<input type="text" id="passInput" name="passInput" />
						<input type="submit" name="loginSubmit" id="loginSubmit" value="login" />
					</form> 
				</div>	
			<?php } ?>
		</div><!-- loginSection div end -->
		<!-- END LOGIN/LOGOUT SCRIPTS -->
		
		
		<?php if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { ?>

			<?php //include('newSite.php'); ?>
			<?php //include('newTask.php'); ?>
			<!-- Include this after we finish setting up pageButtons.php -->
			<?php //include('pageButtons.php'); ?>
	
			
			<div id="addSiteDiv" style="width: 100px;">Add Site</div>
			<div id="addTaskDiv" style="width: 100px;">Add Task</div>
			
			<table class="contentTable" id="pages" name="pages">
			<tr>
				<th colspan="3" class="contentTable" style="text-align: center;">Pages</th> 
			</tr>
			<tr>
				<td class="contentTable"><div id="pageOneDiv"></div></td>
				<td class="contentTable"><div id="pageTwoDiv"></div></td>
				<td class="contentTable"><div id="pageThreeDiv"></div></td>
			</tr>
		</table>
		
		
		<?php
			//Get the count number for the sites
			$siteCount = "select count(*) as theCount from site where site.isArchive = 0";
			$siteCountStatement = $dbh->query($siteCount);
			foreach ($siteCountStatement as $siteC) {
				$theCount = $siteC['theCount'];
			}
			
			$taskListQuery = "SELECT * FROM task ORDER BY orderNumber";
			$taskListStatement = $dbh->query($taskListQuery);
			$taskList = "";
			$taskCounter = 0;
				foreach ($taskListStatement as $tasks) {
					if($taskCounter == 0) {
						$taskList = $taskList . "['" . $tasks['id'] . "', '" . $tasks['taskName'] . "']";
						$taskCounter = 1;
					} else {
						$taskList = $taskList . ", ['" . $tasks['id'] . "', '" . $tasks['taskName'] . "']";
					}
				}
			
		?>
		
		?>
		
		
		<script type="text/javascript">
		Ext.onReady(function() {
		
			/*************************************************************/
			/*********************TOP PANEL - BEGIN***********************/
			/*************************************************************/
				
				/*************************************************************/
				/*********************Login Form Button - BEGIN***************/
				/*************************************************************/
				
				var loginForm = Ext.create('Ext.form.Panel', {
					title		: 'Login',
					bodyPadding	: 5,
					defaultType	: 'textfield',
					items		: [
						{
							fieldLabel	: 'User',
							name		: 'user',
							allowBlank	: false
						},
						{
							fieldLabel	: 'Pass',
							name		: 'pass',
							inputType	: 'password',
						}
					]
				
				
				});
				
				/*************************************************************/
				/*********************Login Form Button - END*****************/
				/*************************************************************/
				
				/*************************************************************/
				/*********************New Task Button - BEGIN*****************/
				/*************************************************************/
				
				//Task Array DataStore for New Task Drop Down
				var taskStore = new Ext.data.ArrayStore({
					data	: [
						<?php echo $taskList; ?>
					],
					fields 	: ['id', 'taskName']
				});
				
				
				var newTaskHandler = function() {
					var taskButton = Ext.getCmp('newTaskId');
					taskButton.el.mask('Creating Task', 'x-mask-loading');
					taskButton.getForm().submit({
						success	: function() {
							taskButton.el.unmask();
							Ext.MessageBox.show({
								title	: 'Success',
								msg		: action.result.message,
								buttons	: Ext.Msg.OK,
								fn		: function(btnString) {
									newTaskWin.hide();
									location.reload(true);
								}
							});
						},
						failure	: function() {
							Ext.MessageBox.show({
								title	: 'Success',
								msg		: action.result.message,
								buttons	: Ext.Msg.ERROR,
								fn		: function(btnString) {
									newTaskWin.hide();
									location.reload(true);
								}
							});
						}
					});
					
				};
				
				
				//New Task Form
				var newTaskForm = new Ext.form.FormPanel({
					xtype		: 'fieldset',
					flex		: 1,
					border		: false,
					id			: 'newTaskId',
					url			: 'addTaskHandler.php',
					defaultType	: 'textfield',
					defaults	: {
						style	: 'margin-left: 5px;',
						
					},
					items		: [
						{
							style		: 'margin-left: 5px; padding-top: 5px;',
							fieldLabel	: 'TaskName',
							name		: 'task',
							allowBlank	: false,
							emptyText	: 'A Task Name is required',	
						},
						{
							xtype		: 'checkbox',
							name		: 'orderCheckBox',
							id			: 'orderCheckBox',
							fieldLabel	: 'Add Task Before',
							inputValue	: 'true',
							handler		: function() {
								Ext.getCmp('allSites').setValue(true);
								console.log('OrderCheckbox is: ' + Ext.getCmp('orderCheckBox').getValue());
								if(Ext.getCmp('orderCheckBox').getValue()){
									Ext.getCmp('taskCombo').setDisabled(false);
								} else {
									Ext.getCmp('taskCombo').setDisabled(true);
								}
							}
							
						},
						{
							xtype		: 'combo',
							fieldLabel	: 'Choose Task',
							name		: 'taskCombo',
							id			: 'taskCombo',
							store		: taskStore,
							displayField: 'taskName',
							valueField	: 'id',
							mode		: 'local',
							disabled	: true, 
						},
						{
							xtype		: 'checkbox',
							name		: 'allSites',
							id			: 'allSites',
							fieldLabel	: 'Add to All Sites',
							
						}
					],
					buttons		: [
						{
							text 	: 'submit',
							handler : newTaskHandler
								
						}
					]
				});
				
				var newTaskWin;
				var newTaskWindow = function(btn) {
					if(!newTaskWin) {
						newTaskWin = new Ext.Window({
							animateTarget	: btn.el,
							closeAction		: 'hide',
							title			: 'New Task',
							id				: 'myWin',
							height			: 200,
							width			: 300,
							constrain		: true,
							items			: [
								newTaskForm
							]
						
						});
					}
					newTaskWin.show();
				}
				
				var taskButton = Ext.create('Ext.Button', {
					text	: 'New Task',
					scale	: 'large',
					handler	: newTaskWindow
				});
				
				/*************************************************************/
				/*********************New Task Button - END*****************/
				/*************************************************************/

				/*************************************************************/
				/*********************New Site Button - BEGIN*****************/
				/*************************************************************/


			var newSiteForm = Ext.create('Ext.form.Panel', {
				bodyPadding	: 5,
				url			: 'addSiteHandler.php',
				id			: 'newSiteId',
				layout		: 'anchor',
				defaults	: {
					anchor	: '100%',
				},
				
				//The Fields
				defaultType	: 'textfield',
				items		: [
					{
						fieldLabel	: 'Site Number',
						name		: 'siteNumber',
						allowBlank	: false,
						emptyText	: 'Required'
					},
					{
						fieldLabel	: 'Site Name',
						name		: 'siteName',
						allowBlank	: false,
						emptyText	: 'Required'
					}
				],
				buttons		: [
					{
						text	: 'Submit',
						
						handler	: function() {
							var siteSubmitHandler = Ext.getCmp('newSiteId')
							
							
							if(siteSubmitHandler.getForm().isValid()) {
								siteSubmitHandler.el.mask('Creating Site', 'x-mask-loading');
								siteSubmitHandler.submit({
									success	: function(form, action) {
										siteSubmitHandler.el.unmask();
										
										Ext.MessageBox.show({
											title	: 'Success',
											msg		: action.result.message,
											icon	: Ext.MessageBox.INFO,
											buttons	: Ext.Msg.OK,
											fn		: function(btnString) {
												newSiteWindow.hide();
												location.reload(true);
											}
										});
											
									},
									failure	: function(form, action) {
										siteSubmitHandler.el.unmask();
										Ext.MessageBox.show({
											title	: 'Fail',
											msg		: action.result.message,
											icon	: Ext.MessageBox.ERROR,
											buttons	: Ext.Msg.OK,
											fn		: function(btnString) {
												newSiteWindow.hide();
												location.reload(true);
											}
										});
									}
								
								});
							
							}
						}
					}
				]
				
			
			});
			
			var newSiteWindow;
			var newSiteButton = function(btn) {
				if(!newSiteWindow) {
					newSiteWindow = Ext.create('Ext.window.Window', {
						animateTarget	: btn.el,
						closeAction		: 'hide',
						title			: 'New Site',
						id				: 'newSiteWindow',
						height			: 200,
						width			: 300,
						constrain		: true,
						items			: [
							newSiteForm
						]
					});
				}
				newSiteWindow.show();
			}
			
			
			var siteButton = Ext.create('Ext.Button', {
				text	: 'New Site',
				scale	: 'large',
				handler	: newSiteButton
			});
			
			var topToolbar = Ext.Toolbar({
				
				items	: [
					siteButton,
					'-',
					taskButton,
					'->',
					loginForm
				]
			});
			
			var topPanel = Ext.create('Ext.panel.Panel', {
				title		: 'Jason\'s Projection Project',
				bodyPadding	: 5,
				tbar		: topToolbar
				
			});
			
				/*************************************************************/
				/*********************New Site Button - END*****************/
				/*************************************************************/
			
			/*************************************************************/
			/*********************TOP PANEL - END*************************/
			/*************************************************************/
		
			/*************************************************************/
			/*********************TABS - BEGIN****************************/
			/*************************************************************/
			var tabs = Ext.createWidget('tabpanel', {
				//width		: 800,
				activeTab	: 0,
				frame		: true,
				cls			: 'taskGrid',
				bodyCls		: 'taskGrid',
				defaults	: {
					bodyPadding	: 10
				},
				items		: [
					{
						title		: 'Page One',
						loader		: {
							url			: 'viewTasksModify.php',
							contentType	: 'html',
							scripts		: true,
							loadMask	: true,
							params		: {
								startSite	: 0,
								limitSite	: 5
							}
						},
						listeners: {
							activate	: function(tab) {
								tab.loader.load();
							}
						}
					}
					<?php
						$pageNumberArray = array(5 => "Two", 10 => "Three", 15 => "Four");
						for($i=5;$i<$theCount;$i=$i+5) {
					?>, 
					{
						title		: 'Page <?php echo $pageNumberArray[$i] ?>',
						loader		: {
							url			: 'viewTasksModify.php',
							contentType	: 'html',
							scripts		: true,
							autoLoad	: true,
							loadMask	: true,
							params		: {
								startSite	: <?php echo $i; ?>,
								limitSite	: 5
							}
						}
					}
					<?php } ?> 
					
				]
			});
			
			/*************************************************************/
			/*********************TABS - END******************************/
			/*************************************************************/
		
			/*************************************************************/
			/**************MAIN VIEWPORT - BEGIN**************************/
			/*************************************************************/
			
			
			Ext.create('Ext.container.Viewport', {
				id			: 'mainView',
				cls			: 'taskGrid',
				renderTo	: Ext.getBody(),
				//layout		: 'vbox', 
				items	: [
					topPanel,
					tabs
					
				]
			
			});
			
			/*************************************************************/
			/**************MAIN VIEWPORT - END****************************/
			/*************************************************************/
			
			
			
		});
		</script>
			
		<?php } ?>
		
		<?php
			//If logged in load the update/delete view tasks.  Otherwise, load just view tasks. 
			 if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { 
				//include('viewTasksModify.php');
			} else {
				//include('viewTasks.php');
				
			}
			
			//include('showProfile.php');
		?>
		
	</div> <!-- End content div -->
</div><!-- End wrapper div -->


</body>
</html>

