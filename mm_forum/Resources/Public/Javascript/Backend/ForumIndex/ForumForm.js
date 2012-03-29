Ext.ns('MmForum.ForumIndex');

MmForum.ForumIndex.ForumForm = Ext.extend(Ext.form.FormPanel, {
	plain: true,
	frame: false,
	bodyStyle: 'padding: 10px;',
	defaults: {width: '90%'},
	waitMsgTarget: true,
	constructor: function(config) {
		config.items = [
			new Ext.form.TextField({
				fieldLabel: TYPO3.l10n.localize("ForumIndex_EditForum_Field_Title"),
				name: 'forum[title]',
				itemId: 'title',
				allowBlank: false
			}),
			new Ext.form.TextArea({
				fieldLabel: TYPO3.l10n.localize("ForumIndex_EditForum_Field_Description"),
				name: 'forum[description]',
				itemId: 'description',
				allowBlank: false
			}),
			new Ext.form.Hidden({
				name: 'forum[__identity]',
				itemId: '__identity'
			})
		];
		config.buttons = [
			new Ext.Button({
				text: TYPO3.l10n.localize("ForumIndex_EditForum_Save"),
				scope: this,
				handler: function() {
					this.getForm().submit({
						waitMsg: TYPO3.l10n.localize("ForumIndex_EditForum_Saving"),
						success: function() {
							TYPO3.Flashmessage.display(
								TYPO3.Severity.ok, TYPO3.l10n.localize('ForumIndex_EditForum_Success_Title'), TYPO3.l10n.localize('ForumIndex_EditForum_Success'));
							this.findParentByType('window').destroy();
							this.tree.getLoader().load(this.treeNode.parentNode);
						},
						scope: this
					});
				}
			}),
			new Ext.Button({
				text: TYPO3.l10n.localize("ForumIndex_EditForum_Cancel"),
				scope: this,
				handler: function() {
					this.findParentByType('window').destroy();
				}
			})
		];
		config.api = {
            load: config.dataProvider.getForum,
            submit: config.dataProvider.saveForum
        }
		config.paramOrder = ['__identity'];

		MmForum.ForumIndex.ForumForm.superclass.constructor.call(this, config);
	}
});