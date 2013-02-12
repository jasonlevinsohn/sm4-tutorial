<!DOCTYPE html>
<html>
<head>
<title>Jason's Projection Project</title>
<link rel="favicon" type="image/x-icon" href="/favicon.ico">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<script type="text/javascript">


</script>
<link rel="stylesheet" type="text/css" href="../projection/extjs/ext-4-0-2a/resources/css/ext-all.css">
<style type="text/css">



</style>
<!-- JavaScript -->
    <script type="text/javascript" src="../projection/extjs/ext-4-0-2a/ext-all-debug.js"></script>
	<script type="text/javascript" src="js/ajax.js"></script>
	
	
</head>

<body>


	
		<script type="text/javascript">
		Ext.onReady(function() {
		
			
			/*************************************************************/
			/*********************TOP PANEL - BEGIN***********************/
			/*************************************************************/
				
				/*************************************************************/
				/*********************Login Form  - BEGIN***************/
				/*************************************************************/
				
				
				
				var loginHandler = function(btn, otherVar) {
					var login = Ext.getCmp('loginForm').getForm();
					console.log(login);
					if(login.isValid()) {
						login.submit({
							
							success	: function(form, action) {
								console.log('You have logged in: ' + action.result.userName);
								loginForm.hide();
								loginDisplayName.setText(action.result.userName);
								loginDisplayName.show();
								login.reset();
								logoutButton.show();
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
							title	: 'Try Again',
							msg		: 'You really could not login',
							icon	: Ext.MessageBox.ERROR,
							buttons	: Ext.Msg.OK
						});
					}
				
				}
				
				var loginForm = Ext.create('Ext.form.Panel', {
					id			: 'loginForm',
					url			: 'loginLogout.php',
					title		: 'Login',
					width		: 280,
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
							fieldLabel	: 'Pass',
							labelWidth	: 30,
							labelStyle	: 'font-size: 14px',
							name		: 'pass',
							inputType	: 'password',
							allowBlank	: false,
							flex		: 1
						}
					],
					buttons		: [
						{
							text	: 'Login',
							handler	: loginHandler
						},
						{
							text	: 'Reset',
							handler	: function() {
								this.up('form').getForm().reset();
							}
						}
					]/*,
					listeners	: {
						beforeaction	: function() {
							var fieldValues = this.getForm().getFieldValues();
							var ePass = fieldValues['pass'];
							this.getForm().setValues({id:'pass', value: 'what is up'})
							console.log(fieldValues);
						}
					}*/
				
				
				});
				
				/*************************************************************/
				/*********************Login Form  - END*****************/
				/*************************************************************/
			
			var logoutButton = Ext.create('Ext.Button', {
				text	: 'Logout',
				handler	: function() {
					Ext.Ajax.request({
						url		: 'loginLogout.php',
						params	: {
							logout	: 1
						},
						success	: function(response) {
							console.log('You have logged out <br />' + response.responseText);
							loginForm.show();
							logoutButton.hide();
						}
					});
				}
			}).hide();
			
			var loginDisplayName = Ext.create('Ext.toolbar.TextItem', {
				text	: 'Username',
				style	: 'font-size: 14px'
			}).hide();
				
			
			var topToolbar = Ext.Toolbar({
				
				items	: [
					
					loginForm,
					loginDisplayName,
					logoutButton
				]
			});
			
			var topPanel = Ext.create('Ext.panel.Panel', {
				title		: 'Jason\'s Projection Project',
				bodyPadding	: 5,
				tbar		: topToolbar,
				renderTo	: Ext.getBody()
				
			});
			
				/*************************************************************/
				/*********************New Site Button - END*****************/
				/*************************************************************/
			
			/*************************************************************/
			/*********************TOP PANEL - END*************************/
			/*************************************************************/
		
			
			
			
		});
		</script>
			
		
		
	</div> <!-- End content div -->
</div><!-- End wrapper div -->


</body>
</html>

