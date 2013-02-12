Ext.onReady(function() {
	
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
	
	
	
	
	
	
	
	
	
});


	
	
						
						
						
						