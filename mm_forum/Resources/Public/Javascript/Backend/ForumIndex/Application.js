Ext.ns('MmForum.ForumIndex');

MmForum.ForumIndex.Application = Ext.extend(Ext.TabPanel, {
	id: 'mmforum-forumindex-application',
	autoWidth: true,
	plain: true,
	activeTab: 0,
	constructor: function(config) {
		config.items = [
			new MmForum.ForumIndex.ForumTree({
				title: TYPO3.l10n.localize("ForumIndex_Grid_Title"),
				dataProvider: MmForum.ForumIndex.DataProvider
			})
		]
		config.plugins = [new Ext.ux.plugins.FitToParent()];
		MmForum.ForumIndex.Application.superclass.constructor.call(this, config);
	}
});