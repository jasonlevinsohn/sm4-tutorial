<!DOCTYPE html>
<html>
<head>
<title>Jason's Web Site Development Project Manager</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">


<link rel="stylesheet" type="text/css" href="../extjs/ext-4-0-2a/resources/css/ext-all.css">
<!--Icons CSS-->
	<link rel="stylesheet" type="text/css" href="icons.css" />
	
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

.siteTitle {
	border: 1px solid #000;
	border-collapse:collapse;
	padding: 5px 5px 5px 5px;
	text-align: center;
	min-width: 150px;

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
    <script type="text/javascript" src="../extjs/ext-4-0-2a/ext-all.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	
</head>

<body>

<?php
//Report all errors
error_reporting(E_ALL);

//Start the session stuff
session_start();

$browser = get_browser(null, true);
//echo  $browser['platform'];
//Send an email when someone comes along
$to = "jason.levinsohn@gmail.com";
$subject = "SENCHA - Visitor to SM4";
$message = "" .
		"Someone is checking out the SM4 at: <b>" . $_SERVER['REMOTE_ADDR'] . "</b> <br />" .
		"They are using the <b>" . $browser['browser'] . "</b> browser on the <b>" . $browser['platform'] . " </b> platform<br />";
	
$header = 'From: jlev711@gmail.com' . "\r\n" .
	'Reply-To: jlev711@gmail.com' . "\r\n" .
	'Content-Type: text/html';
	

if(mail($to, $subject, $message, $header)) {

} else {
	echo 'Less Amesome';
}

//Connect to the database
require_once('functions/connect.php');

//Variables
$userName 		= "";
$loggedIn 		= false;
$newProfiles	= false;

$title = 'Jason\'s Projection Project';

//Check to see if we are logged in
 if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { 
		$loggedIn = true;
		$userName = $_SESSION['SESS_FIRST_NAME'];
	} else {
		$loggedIn = false;
	}

		


?>

		
		
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
		
		
		
		
		<script type="text/javascript">
		Ext.onReady(function() {
			
/*************************************************************/
/*********************SITE PROFILE - BEGIN***********************/
/*************************************************************/
			//Initialize Quick Tips
			Ext.tip.QuickTipManager.init();
							
			//Validate the email address
			
			var myValidFn = function(v) {
				var myRegex = /\b[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b/;
				return myRegex.test(v);
			};
			
			
			Ext.apply(Ext.form.VTypes, {
				emailOnly : myValidFn,
				emailOnlyText : 'Must be a valid email address'
			});
			
			/************************************************************************/
			/*******************Legacy System Local Datastore - BEGIN****************/
			/************************************************************************/
			<?php
				require_once('functions/connect.php');
				$legacyQuery = "SELECT * FROM legacysystem";
				$legacyStatement = $dbh->query($legacyQuery);
				$legacyList = "";
				$legacyCounter = 0;
				foreach ($legacyStatement as $legacy) {
					if($legacyCounter == 0) {
						$legacyList = $legacyList . "['" . $legacy['id'] . "', '" . $legacy['name'] . "']";
						
						$legacyCounter = 1;
					} else {
						$legacyList = $legacyList . ", ['" .$legacy['id'] . "', '" . $legacy['name'] . "']";
						
					}
				}
				
				
			?>
			
			var legacyStore = new Ext.data.ArrayStore({
				data	: [
					<?php echo $legacyList; ?>
				],
				fields	: ['id', 'legacyName']
			});
			
			/************************************************************************/
			/*******************Legacy System Local Datastore - END****************/
			/************************************************************************/
			
			/************************************************************************/
			/*******************Clearinghouse Local Datastore - BEGIN****************/
			/************************************************************************/
			
			var clearinghouseStore = new Ext.data.ArrayStore({
				data	: [
						['0', 'Not Entered'],
						['1', 'Flagship'],
						['2', 'Chase'],
						['3', 'Merchant Warehouse']
					],
				fields	: ['id', 'houseName']
			});
			
			/************************************************************************/
			/*******************Clearinghouse Local Datastore - END******************/
			/************************************************************************/
			
			
			
			/************************************************************************/
			/*******************Project Manager Local Datastore - BEGIN****************/
			/************************************************************************/
			<?php
				require_once('functions/connect.php');
				$pmQuery = "SELECT * FROM pm";
				$pmStatement = $dbh->query($pmQuery);
				$pmList = "";
				$pmCounter = 0;
				foreach ($pmStatement as $pm) {
					if($pmCounter == 0) {
						$pmList .= "['" . $pm['id'] . "', '" . $pm['name'] . "']";
						
						$pmCounter = 1;
					} else {
						$pmList .= ", ['" .$pm['id'] . "', '" . $pm['name'] . "']";
						
					}
				}
			?>
			
			var pmStore = new Ext.data.ArrayStore({
				data	: [
					<?php echo $pmList; ?>
				],
				fields	: ['id', 'name']
			});
			
			/************************************************************************/
			/*******************Project Manager Local Datastore - END****************/
			/************************************************************************/
			
			/*************************************************************/
			/*******************NEW PROFILE BUTTON - BEGIN****************/
			/*************************************************************/
			
			<?php
				
				require_once('functions/connect.php');
				//This script gets the sites without a profile in a ExtJs array list 
				//an array store, this one will be used as a checkbox
				$noProfileSiteQuery = "SELECT site.id as id, site.siteName as name FROM site LEFT JOIN siteprofile " .
				" ON site.id = siteprofile.siteid WHERE site.isArchive = 0 AND siteprofile.id is NULL";
				$noProfileSiteStatement = $dbh->query($noProfileSiteQuery);
				$noProfileList = "";
				$noProfileCounter = 0;
				
				
				
				foreach ($noProfileSiteStatement as $noprofile) {
					if($noProfileCounter == 0) {
						$noProfileList = $noProfileList . "['" . $noprofile['id'] . "', '" . $noprofile['name'] . "']";
						$noProfileCounter = 1;
					} else {
						$noProfileList = $noProfileList . ", ['" . $noprofile['id'] . "', '" . $noprofile['name'] . "']";
					}
				}
				
				//Check to see if we have any new Profiles to create
				if($noProfileCounter > 0) {
					 $newProfiles = true;
				}
				
				
				
			?>
			
			//No Site Profile Array DataStore for New Profile Drop Down
			var noProfileStore = new Ext.data.ArrayStore({
				data	: [
					<?php echo $noProfileList; ?>
				],
				fields 	: ['id', 'name']
			});
			
			
			var newProfileFormOne = {
				xtype		: 'fieldset',
				title		: 'Contact Info',
				flex		: 1,
				border		: false,
				style		: 'margin-left: 10px;',
				defaultType	: 'textfield',
				
				items		: [
					{
						style		: 'padding-top: 5px; padding-left: 10px',
						fieldLabel	: 'Contact Name',
						name		: 'newProfileContact',
						id			: 'profileContact',
						allowBlank	: false,
						emptyText	: 'A contact name is required',
						maskRe		: /[a-z ]/i,
						invalidText	: 'Contact Name is required',
						
						
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'Email',
						vtype		: 'emailOnly',
						name		: 'newProfileEmail',
						invalidText	: 'Not a Valid Email Address',
						
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'Phone',
						name		: 'newProfilePhone',
						emptyText	: 'Enter numbers only',
						maskRe		: /[0-9]/
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'Go-Live Week',
						xtype		: 'datefield',
						name		: 'newProfileDate'
					}		
				]
			};
			
			var newProfileFormTwo = {
				xtype		: 'fieldset',
				title		: 'Project Information',
				flex		: 1,
				border		: false,
				style		: 'margin-left: 10px; margin-right: 10px;',
				defaultType	: 'textfield',
				items	: [
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						xtype		: 'combo',
						fieldLabel	: 'Project Manager',
						name		: 'newProfilePM',
						store		: pmStore,
						displayField: 'name',
						valueField	: 'id',
						mode		: 'local',
						typeAhead	: false
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'PIS',
						name		: 'newProfilePis'
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;', 
						xtype		: 'combo',
						fieldLabel	: 'Legacy Host',
						name		: 'newProfileLegacy',
						store		: legacyStore,
						displayField: 'legacyName',
						valueField	: 'id',
						mode		: 'local',
						typeAhead	: false
					},
					{	
						style		: 'padding-top: 2px; padding-left: 10px;', 
						xtype		: 'combo',
						fieldLabel	: 'CC Processor',
						name		: 'newProfileClearinghouse',
						store		: clearinghouseStore,
						displayField: 'houseName',
						valueField	: 'id',
						mode		: 'local',
						typeAhead	: false
					}
					
				]
			};
			
			var newProfileFormThree = {
				xtype		: 'fieldset',
				title		: 'Site Specific',
				flex		: 1,
				border		: false,
				style		: 'margin-left: 10px; margin-right: 10px',
				defaultType	: 'textfield',
				items		: [
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'Pages Contracted',
						name		: 'patsToAdd'
					},
					{
						style		: 'padding-top: 2px; padding-left: 10px;',
						fieldLabel	: 'Site Industry',
						name		: 'numberToStartPats'
					}
				]
			};
			
			//New Profile Form Handler
			var addNewProfileHandler = function(btn) {
				var newProfile = Ext.getCmp('newProfileForm');
				var siteNumber = Ext.getCmp('noProfileCombo').getValue();
				
				if(newProfile.getForm().isValid()) {
					newProfile.getForm().submit({
						params		: {
							siteId		: siteNumber,
						},
						success		: function(form, action) {
							Ext.MessageBox.show({
								title	: 'Success',
								msg		: action.result.message,
								icon	: Ext.MessageBox.INFO,
								buttons	: Ext.Msg.OK,
								fn		: function(btnString) {
									//Reload the page if our update was a success
									newPro.hide();
									location.reload(true);
									
								}
							});
						},
						failure		: function(form, action) {
							Ext.MessageBox.alert({
								title	: 'Error adding Profile for site ' + siteNumber,
								msg		: action.result.message,
								icon	: Ext.MessageBox.ERROR,
								buttons	: Ext.Msg.OK
							});
						}
					});
				} //End Check for Valid New Profile Form
			}
			
			//New Profile Form
			var newProfileForm = new Ext.form.FormPanel({
				/* title		: 'New Profile:', */
				id			: 'newProfileForm',
				layout		: 'hbox',
				width		: 650,
				height		: 300,
				url			: 'controllers/addNewProfile.php',
				frame		: false, //makes background white or colored
				items		: [
					newProfileFormOne,
					newProfileFormTwo,
					newProfileFormThree
				],
				buttons		: [
					{
						text 	: 'Save Profile',
						handler	: addNewProfileHandler
					}
				]
			});
			
			var openNewProfile = function(btn) {
				var newProf = Ext.getCmp('chooseForm');
				var newProfWindow = Ext.getCmp('newProfileWindow');
				var newProfValue = Ext.getCmp('noProfileCombo');
				var newProfForm = Ext.getCmp('newProfileForm');
				
				
				//console.log('Get XY Coordinates');
				
				//Get the location of the new Profile Window
				var profWindowXPos = newProfWindow.getPosition()[0];
				var profWindowYPos = newProfWindow.getPosition()[1];
				
				
				//Set the new Location of the Profile Window
				newProfWindow.setPosition(profWindowXPos - 120, profWindowYPos - 120, true);
				//console.log(newProfWindow.getPosition()[0]);
				newProfWindow.setHeight(400);
				newProfWindow.setWidth(700);
				newProfWindow.add(newProfileForm);
				newProfForm.setTitle('New Form for Site: ' + newProfValue.getRawValue());
				
				
				
			}
			
			var chooseSiteForm = new Ext.form.FormPanel({
				xtype		: 'fieldset',
				flex		: 1,
				border		: true,
				frame		: true,
				id			: 'chooseForm',
				items		: [
					{
						xtype		: 'combo',
						name		: 'noProfileCombo',
						id			: 'noProfileCombo',
						store		: noProfileStore,
						displayField: 'name',
						valueField	: 'id',
						mode		: 'local'
					}
				],
				buttons		: [
					{
						text	: 'Add',
						handler	: openNewProfile
							
						
					}
				]
				
			});
			
			
			var newPro;
			var newProfileWindow = function(btn) {
				if(!newPro) {
					newPro = new Ext.Window({
						animateTarget	: btn.el,
						closeAction		: 'hide',
						title			: 'New Profile',
						id				: 'newProfileWindow',
						height			: 200,
						width			: 300,
						constrain		: true,
						modal			: true,
						items			: [
							chooseSiteForm
						]
					
					});
				}
				newPro.show();
				
				
				
				
			}
			/*************************************************************/
			/*******************NEW PROFILE BUTTON - END******************/
			/*************************************************************/
					
			//Main Form Button Handler
			var btnHandler = function(btn) {
				//Ext.MessageBox.alert('You clicked', btn.id);
				var formButtons = Ext.getCmp('mainProfileForm');
				if(btn.id == 'update') {
					formButtons.getForm().submit({
						success	: function() {
							Ext.MessageBox.alert('success');
						},
						failure	: function() {
							Ext.MessageBox.alert('failure');
						}
					});
				}
				if(btn.id == 'new') {
					newProfileWindow(btn.id);
					
				} else if (btn.id == 'newProfileMenu') {
					newProfileWindow(btn.id);
					trainingGoToNewProfileBox.hide();
					
					trainingChooseNewProfile();
					
				}
				
			}
			
			
			
			
			//GUI Button Objects
			var saveButton = new Ext.Button({
				text		: 'Save',
				id		: 'save',
				handler	: btnHandler,
				disabled	: true
			});
			
			var updateButton = new Ext.Button ({
				text		: 'Update',
				id		: 'update',
				handler	: btnHandler,
				disabled	: true
			});
			
			var newButton = new Ext.Button ({
				text	: 'New Profile',
				id		: 'newTBar',
				handler	: btnHandler,
				disabled	: true
			});
			
			//Bottom Toolbar
			var bottomToolbar = new Ext.Toolbar({
				items	: [
					newButton,
					'->',
					updateButton,
					'-',
					saveButton
				]
			});
			
			<?php
				
				$siteNumber = "0000";
				$siteName = "Unknown";
				$pmName = "Unknown";
				$isArchive = 0;
				$siteItems = ""; //List of Ext JS Form object names to insert in the Panel's items array.
				$counter = 0; //Tells us whether this is the first item in the list as to not add a comma 
							  // before ExtJS form object name in the list
				
				//Query all the site profiles
				require_once('functions/connect.php');
				$siteProfileQuery = "SELECT * FROM siteprofile ORDER BY migrationWeek ASC";
				$siteProfileStatement = $dbh->query($siteProfileQuery);
				
				foreach ($siteProfileStatement as $profile) {
				
					//Query the site table for the site Name and get the isArchive field
					//to tell us whether we should list it or not
					$siteNameQuery = "SELECT * FROM site WHERE id = " . $profile['siteId'];
					
					
					$siteNameStatement = $dbh->query($siteNameQuery)->fetchAll();
					if(count($dbh->query($siteNameQuery)->fetchAll()) == 1) {
						foreach ($siteNameStatement as $site) {
							$siteId = $site['id'];
							$siteNumber = $site['siteNumber'];
							$siteName = $site['siteName'];
							$isArchive = $site['isArchive'];
						}
					} else {
						echo 'Error quering site table: The site id was not found';
					}
					
					//Query the Legacy System table for the Legacy Notes
					$legacyNotes = "<b>No Notes for this system</b>";
					
					
					$legacyNotesQuery = "SELECT notes from legacysystem WHERE id = " . $profile['legacySystem'];
					$legacyNotesStatement = $dbh->query($legacyNotesQuery)->fetchAll();
					if(count($dbh->query($legacyNotesQuery)->fetchAll()) == 1) {
						foreach($legacyNotesStatement as $legacyNote) {
							$legacyNotes = $legacyNote['notes'];
						}
					} //else {
						//echo 'Error query legacy system table: The legacy system was not found';
					//}
					
					//Query the pm table for the Project Manager Name
					/*
					$pmNameQuery = "SELECT name FROM pm WHERE id = " . $profile['pm'];
					$pmNameStatement = $dbh->query($pmNameQuery)->fetchAll();
					if(count($dbh->query($pmNameQuery)->fetchAll()) == 1) {
						foreach ($pmNameStatement as $pm) {
							$pmName = $pm['name'];
						}
					} else {
						echo 'Error quering PM table: The PM id was not found';
					}
					*/
					
					
					//Generate the ExtJS Form for each site
			?>		
					<?php if($isArchive == 0) { ?>
					/*************************************************************/
					/*******************UPDATE BUTTON - BEGIN*********************/
					/*************************************************************/
						var btnHandler<?php echo $siteNumber; ?> = function(btn) {
							//Ext.MessageBox.alert('You clicked', btn.id);
							var profileCmp<?php echo $siteNumber; ?> = Ext.getCmp('sitePanel<?php echo $siteNumber; ?>');
							
							profileCmp<?php echo $siteNumber; ?>.getForm().submit({
									params	: {
										id : '<?php echo $siteId; ?>'
									},
									success	: function(form, action) {
										
										//Ext.MessageBox.alert('Success', action.result.message);
										Ext.MessageBox.show({
											title 		: 'Success',
											
											msg	  		: action.result.message,
											icon  		: Ext.MessageBox.INFO,
											buttons		: Ext.Msg.OK,
											fn			: function(btnString) {
												//Reload the page if our update was a success
												location.reload(true);
											}
											
											
										});
										
										
									},
									failure	: function() {
										Ext.MessageBox.alert('failure');
									}
							});
						}
						
						
						var updateButton<?php echo $siteNumber; ?> = new Ext.Button ({
							text	: 'Update Profile',
							<?php if($loggedIn) { ?>
							disabled: false,
							<?php } else { ?>
							disabled: true,
							<?php } ?>
							scale	: 'large',
							style	: 'margin-bottom: 15px; margin-right: 15px;',
							id		: 'updateBtn<?php echo $siteNumber; ?>',
							handler	: btnHandler<?php echo $siteNumber; ?>
						});
						
						/*************************************************************/
						/*********************UPDATE BUTTON - END*********************/
						/*************************************************************/
						
						
						
						
						/*************************************************************/
						/*******************FORM FIELDS - BEGIN***********************/
						/*************************************************************/
						var formOne<?php echo $siteNumber; ?> = {
							xtype		: 'fieldset',
							title		: 'Contact Info',
							flex		: 1,
							border		: false,
							style		: 'margin-left: 10px',
							defaultType	: 'textfield',
							items	: [
								{
									style		: 'padding-top: 5px; padding-left: 10px;',
									fieldLabel	: 'Contact Name',
									name		: 'contact',
									
									
									allowBlank	: false,
									emptyText	: 'A contact name is required',
									maskRe		: /[a-z ]/i,
									value		: '<?php echo $profile['contact']; ?>'
									
									
								},
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'Email',
									vtype		: 'emailOnly',
									name		: 'email',
									value		: '<?php echo $profile['email']; ?>'
								},
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'Phone',
									name		: 'phone',
									//For now we have to improvise this phone number
									<?php $ph = $profile['phone']; ?>
									value		: '<?php echo '(' . substr($ph,0,3) . ') ' . substr($ph,3,3) . '-' . substr($ph,6, 4); ?>'
								},
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'Go-Live Week',
									xtype		: 'datefield',
									
									name		: 'date',
									//For now we are formatting the date to the american way
									<?php $mDate = new DateTime($profile['migrationWeek']); ?>
									value		: '<?php echo $mDate->format('m/d/Y'); ?>'
								}
							]
						};
						
						var formTwo<?php echo $siteNumber; ?> = {
							xtype		: 'fieldset',
							title		: 'Site Info',
							flex		: 1,
							border		: false,
							style		: 'margin-left: 10px; margin-right: 10px;',
							defaultType	: 'textfield',
							items	: [
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									xtype		: 'combo',
									fieldLabel	: 'Project Manager',
									name		: 'pm',
									store		: pmStore,
									displayField: 'name',
									valueField	: 'id',
									typeAhead	: false,
									mode		: 'local',
									value		: '<?php echo $profile['pm'] ?>'
								},
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'PIS',
									name		: 'pis',
									readOnly	: false,
									value		: '<?php echo $profile['pis'] ?>'
								},
								{
									/* style		: 'padding-top: 2px; padding-left: 10px;', */ //Without this padding the label is on the same line 
									style		: 'padding-top: 2px; padding-left: 10px;',
									xtype		 : 'combo',
									fieldLabel	 : 'Legacy Host',
									name		 : 'legacy',
									store		 : legacyStore,
									displayField : 'legacyName',
									valueField	 : 'id',
									
									typeAhead	 : true,
									mode		 : 'local',
									value		 : '<?php echo $profile['legacySystem']; ?>'
								},
								{
									/* style		: 'padding-top: 2px; padding-left: 10px;', */ //Without this padding the label is on the same line 
									style		: 'padding-top: 2px; padding-left: 10px;',
									xtype		 : 'combo',
									fieldLabel	 : 'CC Processor',
									name		 : 'clearinghouse',
									store		 : clearinghouseStore,
									displayField : 'houseName',
									valueField	 : 'id',
									
									typeAhead	 : false,
									mode		 : 'local',
									value		 : '<?php echo $profile['clearingHouse']; ?>'
								}
								
							]
						};
						
						var formThree<?php echo $siteNumber; ?> = {
							xtype		: 'fieldset',
							title		: 'Site Specific',
							flex		: 1,
							border		: false,
							style		: 'margin-left: 10px; margin-right: 10px;',
							defaultType	: 'textfield',
							items	: [
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'Pages Contracted',
									name		: 'patsToAdd',
									value		: '<?php echo $profile['patientsToAdd'] ?>'
								},
								{
									style		: 'padding-top: 2px; padding-left: 10px;',
									fieldLabel	: 'Site Industry',
									name		: 'numberToStartPats',
									value		: '<?php echo $profile['patientIdStartNumber'] ?>'
								}
							]
						};
						
						var theForms<?php echo $siteNumber; ?> = {
							xtype	: 'fieldset',
							style			: 'margin-left: 10px; margin-right: 10px;padding-top: 20px; margin-top: 20px;',
							flex	: 1,
							layout	: 'hbox',
							items	: [
								formOne<?php echo $siteNumber; ?>
								,formTwo<?php echo $siteNumber; ?>
								,formThree<?php echo $siteNumber; ?> 
							]
						
						};
						
						var theEditor<?php echo $siteNumber; ?> = {
							xtype			: 'htmleditor',
							name			: 'legacyNotes',
							title			: 'Legacy System Instructions',
							style			: 'margin-left: 10px; margin-right: 10px;',
							/* height			: 100,
							width			: 100, */
							flex			: 1,
							enableColors	: false,
							value			: '<?php echo $legacyNotes; ?>'
							
						};
						
						
						var updateLegacyNotes<?php echo $siteNumber; ?> = {
							xtype		: 'checkbox',
							boxLabel	: 'Update Legacy System Notes?',
							id			: 'updateLegacyNotes<?php echo $siteNumber; ?>',
							name		: 'updateLegacyNotes',
							inputValue	: 'true',
							flex		: 2,
							style		: 'margin-left: 10px; margin-right: 10px;',
							
						};
						
						/*************************************************************/
						/*******************UPDATE NOTES - BEGIN**********************/
						/*************************************************************/
						var noteSaveHandler<?php echo $siteNumber; ?> = function(btn) {
							var notes = Ext.getCmp('updateNotesForm<?php echo $siteNumber; ?>').getForm();
							
							notes.submit({
								success	: function(form, action) {
									Ext.MessageBox.show({
										title	: '<?php echo $siteNumber; ?> - <?php echo $siteName; ?>',
										msg		: action.result.message,
										icon	: Ext.MessageBox.INFO,
										buttons	: Ext.Msg.OK,
										fn		: function () {
											updateNotesWindow<?php echo $siteNumber; ?>.hide();
											trainingOpenTheNotesBox.hide();
											trainingTutorialFinished();
										}
									});
								},
								failure	: function(form, action) {
									Ext.MessageBox.show({
										title	: '<?php echo $siteNumber; ?> - <?php echo $siteName; ?>',
										msg		: 'ERROR: ' + action.result.message,
										icon	: Ext.MessageBox.ERROR,
										buttons	: Ext.Msg.OK,
										fn		: function () {
											updateNotesWindow<?php echo $siteNumber; ?>.hide();
										}
									});
								}
							});
							
						}
						
						
						var updateNotesForm<?php echo $siteNumber; ?> = Ext.create('Ext.form.Panel', {
							id			: 'updateNotesForm<?php echo $siteNumber; ?>',
							url			: 'controllers/updateNotes.php',
							
							height		: 590,
							width		: 490,
							items		: [
								{
									xtype		: 'htmleditor',
									name		: 'profileNotes',
									height		: 550,
									width		: 480,
									enableColors: true,
									value		: '<?php echo $profile["profileNotes"]; ?>'
								},
								{
									xtype	: 'hiddenfield',
									name	: 'noteType',
									value	: 'profileNote',
								},
								{
									xtype	: 'hiddenfield',
									name	: 'siteId',
									value	: '<?php echo $siteId; ?>'
								}
							],
							buttons	: [
								{
									text	: 'Save Note',
									handler	: noteSaveHandler<?php echo $siteNumber; ?>
								}
							]
						});
						
						
						//The window for adding a new Site
						var updateNotesWindow<?php echo $siteNumber; ?>;
						var updateNotesWin<?php echo $siteNumber; ?> = function(btn) {
							
							if(!updateNotesWindow<?php echo $siteNumber; ?>) {
								
								updateNotesWindow<?php echo $siteNumber; ?> = new Ext.Window({
									animateTarget	: btn.el,
									closeAction		: 'hide',
									title			: '<?php echo $siteNumber; ?> - <?php echo $siteName; ?> Notes',
									id				: 'updateNotesWindow<?php echo $siteNumber; ?>',
									height			: 650,
									width			: 500,
									constrain		: true,
									items			: [
										updateNotesForm<?php echo $siteNumber; ?>
									]
								});
							}
							//FOR TRAINING PURPOSES
							//trainingOpenTheNotesBox.hide();
							//trainingOpenTheNotesBox.update('<b>Next: </b>Enter some text and click \"Save Note\"');
							//
							trainingOpenTheNotesBox.show({
								title	:	'Save a Note',
								msg		: '<b>Next: </b>Enter some text and click \"Save Note\"',
								icon	: Ext.MessageBox.INFO,
								
							
							});
							
				
				
							updateNotesWindow<?php echo $siteNumber; ?>.show();
							
							var saveNoteX = updateNotesWindow<?php echo $siteNumber; ?>.getPosition()[0];
							trainingOpenTheNotesBox.setPosition(saveNoteX - 350, calcNewNoteY, true);
							
							
							
						}
						
						
						var updateNotesButton<?php echo $siteNumber; ?> = Ext.create('Ext.Button', {
							text	: 'Site Notes',
							scale	: 'large',
							<?php if($loggedIn) { ?>
							disabled: false,
							<?php } else { ?>
							disabled: true,
							<?php } ?>
							
							
							style	: 'margin-left: 10px',
							handler	: updateNotesWin<?php echo $siteNumber; ?>
						});
						
						/*************************************************************/
						/*******************UPDATE NOTES- END*************************/
						/*************************************************************/
						
											
						
						var sitePanel<?php echo $siteNumber; ?> = new Ext.create('Ext.form.Panel', {
							xtype		: 'fieldset',
							/* flex		: 1, */
							
							id			: 'sitePanel<?php echo $siteNumber; ?>',
							title		: '<?php echo $siteNumber; ?> - <?php echo $siteName ?>',
							/* layout		: 'fit', */
							defaultType	: 'textfield',
							url			: 'controllers/profileHandler.php',
							style		: 'border: 1px solid #000;',
							defaults	: {
								 msgTarget	: 'side',
								anchor		: '-20' 
							}, 
							frame		: false, //makes background white or colored
							items	: [
								theForms<?php echo $siteNumber; ?>
								//,theEditor<?php echo $siteNumber; ?>
								//,updateLegacyNotes<?php echo $siteNumber; ?>
								
								
								
							],
							buttons	: [
								updateButton<?php echo $siteNumber; ?>
								,updateNotesButton<?php echo $siteNumber; ?>
							]
							
						});
						<?php //Add the ExtJS form object name to the list
							if($counter == 0) {
								$siteItems = $siteItems . 'sitePanel' . $siteNumber;
								$counter = 1;
							} else {
								$siteItems = $siteItems . ', sitePanel' . $siteNumber;
								
							}
							
						?>
				
					<?php } //End if statement for isArchive test ?>
					
			<?php	}// end foreach profiles ?>
				
			/*************************************************************/
			/*******************FORM FIELDS - END*************************/
			/*************************************************************/
				
				
				
				
			/*************************************************************/
			/**************MAIN PANEL - BEGIN*****************************/
			/*************************************************************/
			
			var profileDiv = Ext.get('siteProfile');
			
			
			/* profiles = new Ext.Panel({ */
			var	profiles = new Ext.form.FormPanel({
				layout		: 'accordion',
				autoScroll	: true,
				region		: 'east',
				id			: 'mainProfileForm',
				width		: 700,
				height		: 300,
				 url		: 'profileHandler.php', 
				title		: 'Profiles',
				/* renderTo	: Ext.getBody(),  */
				collapsible	: true,
				collapsed	: true,
				style		: 'padding-left: 5px', 
				bbar		: bottomToolbar,
				items		: [
					<?php echo $siteItems; ?>
				],
				
			});
			
/*************************************************************/
/*********************SITE PROFILE - END***********************/
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
				title		: 'Site Tasks',
				region		: 'center',
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
			/*********************TOP PANEL - BEGIN***********************/
			/*************************************************************/
				
				
				/*
					Add a new task to the database. When we add a new task, we also need to create
					a record in the siteTasks table for every site in the database.  
				*/
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
						success	: function(form, action) {
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
						failure	: function(form, action) {
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
				/*
				Add a new site to the database.  When we add a new site, we also need to create
				a record in the siteTasks table for every Task in the database.  This makes the code 
				longer and a little more complicated.
				*/
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
						emptyText	: 'Enter a Site Number',
						regex		: /[0-9]/,
						invalidText	: 'Numbers Only',
						msgTarget	: 'under'
					},
					{
						fieldLabel	: 'Site Name',
						name		: 'siteName',
						allowBlank	: false,
						emptyText	: 'Enter a Site Name',
						regex		: /^[a-zA-Z0-9_ -]*$/,
						invalidText	: 'AlphaNumerics Only',
						msgTarget	: 'under'
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
				
				//For training purposes let's hide the tutorial box here
				trainingNewSiteBox.hide();
			}
			
			
			var siteButton = Ext.create('Ext.Button', {
				text	: 'New Site',
				scale	: 'large',
				handler	: newSiteButton
				
			});
			
			/*************************************************************/
			/*********************New Site Button - END*****************/
			/*************************************************************/
			
			/*************************************************************/
			/*********************Login Form  - BEGIN*********************/
			/*************************************************************/
			
			
			//The Login Handler
			var loginHandler = function(btn, otherVar) {
					var login = Ext.getCmp('loginForm').getForm();
					
					if(login.isValid()) {
						login.submit({
							
							success	: function(form, action) {
								
								loginForm.hide();
								login.reset();
								logoutButton.setText('Hello, ' + action.result.userName + ' | Logout');
								logoutButton.show();
								location.reload(true);
							},
							failure	: function(form, action) {
								Ext.MessageBox.show({
									title	: 'Try Again',
									msg		: 'You could not login',
									icon	: Ext.MessageBox.ERROR,
									buttons	: Ext.Msg.OK
								});
								login.reset();
							}
					
						});
					} else {
						Ext.MessageBox.show({
							title	: 'Invalid Form',
							msg		: 'Form is not valid',
							icon	: Ext.MessageBox.ERROR,
							buttons	: Ext.Msg.OK
						});
					}
				
				}
			
			//Login Form displays when not logged in 
			var loginForm = Ext.create('Ext.form.Panel', {
					id			: 'loginForm',
					url			: 'loginLogout.php',
					title		: 'Login',
					width		: 380,
					height		: 100,
					frame		: true,
					bodyPadding	: 5,
					dock		: 'right',
					layout		: 'hbox',
					defaultType	: 'textfield',
					items		: [
						{
							fieldLabel	: 'User',
							labelWidth	: 30,
							style		: 'margin-right: 10px',
							labelStyle	: 'font-size: 14px',
							name		: 'user',
							allowBlank	: false,
							flex		: 1
						},
						{
							fieldLabel		: 'Pass',
							labelWidth		: 30,
							labelStyle		: 'font-size: 14px',
							name			: 'pass',
							inputType		: 'password',
							allowBlank		: false,
							flex			: 1,
							enableKeyEvents	: true,
							listeners		: {
								specialkey	: function(field, e) {
									if(e.getKey() == e.ENTER) {
										Ext.getCmp('loginButtonClicker').handler.call(Ext.getCmp('loginButtonClicker').scope)
									}
								}
							}
						}
					],
					buttons		: [
						{
							id		: 'loginButtonClicker',
							text	: 'Login',
							handler	: loginHandler
						},
						{
							text	: 'Reset',
							handler	: function() {
								this.up('form').getForm().reset();
							}
						}
					],
					keys	: [
						{
							key		: [Ext.EventObject.ENTER],
							handler	: loginHandler
						}
					]/*,
					listeners	: {
						beforeaction	: function() {
							var fieldValues = this.getForm().getFieldValues();
							var ePass = fieldValues['pass'];
							this.getForm().setValues({id:'pass', value: 'what is up'})
							
						}
					}*/
				
			<?php if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { ?>
				}).hide();
			<?php } else { ?>
				});
			<?php } ?>
			
			//Logout Button displays when logged in
			var logoutButton = Ext.create('Ext.Button', {
				text	: 'Hello, <?php echo $userName; ?> | Logout',
				handler	: function() {
					Ext.Ajax.request({
						url		: 'loginLogout.php',
						params	: {
							logout	: 1
						},
						success	: function(response) {
							
							loginForm.show();
							logoutButton.hide();
							location.reload();
						}
					});
				}
			<?php if(isset($_SESSION['SESS_MEMBER_ID']) && isset($_SESSION['SESS_FIRST_NAME'])) { ?>
				});
			<?php } else { ?>
				}).hide();
			<?php } ?>
			
			
						
			/*************************************************************/
			/*********************Login Form  - END***********************/
			/*************************************************************/
			
			
			
			/*************************************************************/
			/*********************Training Section  - BEGIN***************/
			/*************************************************************/
			
			var trainingTutorialFinished = function() {
				Ext.MessageBox.show({
					title	: 'Tutorial Complete',
					msg		: '<b>Congratulations!</b> You have completed the tutorial. <br /> Click on the status of any task to change the status.',
					icon	: Ext.MessageBox.INFO,
					buttons	: Ext.Msg.OK,
					
				});	
				profiles.toggleCollapse();
			};
			
			var trainingChooseProfileBox;
			var trainingChooseNewProfile = function() {
			
				//TRAINING MESSAGE BOX
				trainingChooseProfileBox = Ext.MessageBox.show({
					title	: 'Adding a Profile',
					msg		: '<b>Next:</b> <br /><ul><li>Choose your site from dropdown</li><li>Fill in the form fields</li><li>Click Save Profile</li></ul>',
					icon	: Ext.MessageBox.INFO,
					modal	: false,
					width	: 200
					
				});	
				
				var profileX = trainingChooseProfileBox.getPosition()[0];
				var profileY = trainingChooseProfileBox.getPosition()[1];
				
				trainingChooseProfileBox.setPosition(profileX * 0.15, profileY, true);
			
			
			
			};
			
			var trainingOpenTheNotesBox;
			var calcNewNoteY;
			var trainingOpenTheNotes = function(btn, text) {
				
				trainingOpenTheNotesBox = Ext.MessageBox.show({
					title	: 'Create a Note',
					msg	: 'Click the site you have just added. <br /> Click the <b>\"Site Notes\"</b> button to save a note',
					icon	: Ext.MessageBox.INFO,
					//buttons	: Ext.Msg.OK,
					modal	: false
				});	
				
				var newNoteX = trainingOpenTheNotesBox.getPosition()[0];
				var newNoteY = trainingOpenTheNotesBox.getPosition()[1];
				
				var calcNewNoteX = (newNoteX * 2) - (newNoteX * 0.21);
				var calcNewNoteY = (newNoteY * 2) - (newNoteY * 0.21);
												
				//Set the new Location of the Training New Site Box
				trainingOpenTheNotesBox.setPosition(calcNewNoteX, calcNewNoteY, true);
			};
			
			
			var trainingNewSiteBox;
			var trainingNewSite = function(btn, text) {
					
				
				trainingNewSiteBox = Ext.MessageBox.show({
						title	: 'Tutorial',
						msg		: 'Click the \'New Site\' button',
						icon	: Ext.MessageBox.INFO,
						//buttons	: Ext.Msg.OK,
						modal	: false
					});	
				
				var newSiteX = trainingNewSiteBox.getPosition()[0];
				var newSiteY = trainingNewSiteBox.getPosition()[1];
				
				var calcNewSiteX = newSiteX * 0.21;
				var calcNewSiteY = newSiteY * 0.20;
												
				//Set the new Location of the Training New Site Box
				trainingNewSiteBox.setPosition(calcNewSiteX, calcNewSiteY, true);
				
			}
			
			var trainingGoToNewProfileBox;
			var trainingGoToNewProfile = function(btn, text) {
				
				trainingGoToNewProfileBox = Ext.MessageBox.show({
					title	: 'Tutorial',
					msg		: 'Now click the \'New Profile\' button',
					icon	: Ext.MessageBox.INFO,
					//buttons	: Ext.Msg.OK,
					modal	: false
				});
				
				var trainingGoProfileBoxXPos = trainingGoToNewProfileBox.getPosition()[0];
				var trainingGoProfileBoxYPos = trainingGoToNewProfileBox.getPosition()[1];
				
				//console.log("The X is: " + trainingGoProfileBoxXPos + ". The X is: " + trainingGoProfileBoxYPos);
				var calcNewXPosition = trainingGoProfileBoxXPos * 0.21;
				var calcNewYPosition = trainingGoProfileBoxYPos * 0.25;
				
				
				trainingGoToNewProfileBox.setPosition(calcNewXPosition, calcNewXPosition, true);
				
			}
			
			
			var trainingStart = function(btn, text) {
				
				if(btn == "yes") {
					Ext.MessageBox.show({
						title	: 'Web Site Project Manager',
						msg		: 'This demo is a fully-functioning <b>Sencha</b> JavaScript Framework Application<br />It works the same in all browsers, Android, and the <b>iPad</b>' ,
						icon	: Ext.MessageBox.INFO,
						buttons	: Ext.Msg.OK,
						fn		: trainingNewSite
					});	
				} else {
					Ext.MessageBox.show({
						title	: 'Tutorial',
						msg		: 'Have a nice day',
						icon	: Ext.MessageBox.INFO,
						buttons	: Ext.Msg.OK,
					});	
				}
			};
			
			
			<?php if(isset($_SESSION['PROFILE_ADDED']) && $loggedIn) { ?>
				Ext.MessageBox.show({
					title	: 'Profile Saved',
					msg		: 'The profile is now saved and has been placed in the collapsible pane to the right.',
					icon	: Ext.MessageBox.INFO,
					buttons	: Ext.Msg.OK,
					fn		: function() {
						profiles.toggleCollapse();
						
						
						trainingOpenTheNotes();
					}
					
				});	
			<?php } else if(isset($_SESSION['SITE_ADDED']) && $loggedIn) { ?>
				
				
				Ext.MessageBox.show({
					title	: 'Tutorial',
					msg		: 'Great, Now we will create a new Profile',
					icon	: Ext.MessageBox.INFO,
					buttons	: Ext.Msg.OK,
					fn		: trainingGoToNewProfile
					
				});	
			<?php } else if ($loggedIn){?>
				Ext.MessageBox.show({
					title	: 'Tutorial',
					msg		: 'Do you want to go through the tutorial?',
					icon	: Ext.MessageBox.INFO,
					buttons	: Ext.Msg.YESNO,
					fn		: trainingStart
				});	
			<?php } ?>
			
			
			



			/*************************************************************/
			/*********************Training Section  - END******************/
			/*************************************************************/
			
			
			
			
			
			/*************************************************************/
			/*********************Main Toolbar Components- BEGIN**********/
			/*************************************************************/
			
			
			
			var menuItems = [
				
				{
					<?php if($loggedIn) { ?>
					disabled: false,
					<?php } else { ?>
					disabled: true,
					<?php } ?>
					text	: 'New Site',
					iconCls	: 'icon-add',
					handler	: newSiteButton
				},
				'-',
				{
					<?php if($loggedIn) { ?>
					disabled: false,
					<?php } else { ?>
					disabled: true,
					<?php } ?>
					text	: 'Print Profiles',
					iconCls	: 'icon-printer',
					handler	: function() {
						window.open('controllers/printSites.php');
					}
				},
				'-',
				{
					<?php if($loggedIn && $newProfiles) { ?>
					disabled: false,
					<?php } else { ?>
					disabled: true,
					<?php } ?>
					text	: 'New Profile',
					iconCls	: 'icon-application_add',
					handler	: btnHandler,
					id		: 'newProfileMenu'
				}
			];
			

			var menuItemsTwo = [
				
				{
					<?php if($loggedIn) { ?>
					disabled: false,
					<?php } else { ?>
					disabled: true,
					<?php } ?>
					text	: 'New Task',
					iconCls	: 'icon-application_add',
					handler	: newTaskWindow
				}
				
			];
			
			
			var commandMenu = Ext.create('Ext.menu.Menu', {
				height		: 100,
				floating	: false,
				items		: menuItems,
				dock		: 'left',
				listeners	: {
					'beforehide'	: function() {
						return false;
					}
				}
				
			});

			var commandMenuTwo = Ext.create('Ext.menu.Menu', {
				height		: 100,
				floating	: false,
				items		: menuItemsTwo,
				dock		: 'left',
				listeners	: {
					'beforehide'	: function() {
						return false;
					}
				}
			});
			
						
			var loginPanel = Ext.create('Ext.panel.Panel', {
				
				dock	: 'right',
				items	: [
					loginForm,
					
					logoutButton
				]
			});
			
			
			var topPanel = Ext.create('Ext.panel.Panel', {
				title		: 'Main Command Bar',
				layout		: 'hbox',
				height		: 150,
				bodyPadding	: 5,
				dockedItems		: [
					commandMenu,
					commandMenuTwo,
					loginPanel
				]
				
			});
			
			
			/*************************************************************/
			/*********************Main Toolbar Components- END************/
			/*************************************************************/
		
			
			
			/*************************************************************/
			/*********************NORTH PANEL - BEGIN*********************/
			/*************************************************************/
			var northPanel = Ext.create('Ext.panel.Panel', {
							
							region	: 'north',
							
							split	: true,
							height	: 127,
							items	: [
								topPanel
							]
			});
			
			/*************************************************************/
			/*********************NORTH PANEL - END***********************/
			/*************************************************************/
			
			
			
			/*************************************************************/
			/**************MAIN VIEWPORT - BEGIN**************************/
			/*************************************************************/
			Ext.create('Ext.container.Viewport', {
            		layout	: 'border',
					title	: 'Web Site Manager',
            		items	: [
						northPanel,
						tabs,
						profiles
					]
        	});
			
			
			/*************************************************************/
			/**************MAIN VIEWPORT - END****************************/
			/*************************************************************/
			
			
			
		});
		</script>
			
		
		
		
		



</body>
</html>

