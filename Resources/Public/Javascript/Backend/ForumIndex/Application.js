Ext.ns('Typo3Forum.ForumIndex');

Typo3Forum.ForumIndex.Application = Ext.extend(Ext.TabPanel, {
	id: 'typo3forum-forumindex-application',
	autoWidth: true,
	plain: true,
	activeTab: 0,
	constructor: function(config) {
		config.items = [
			new Typo3Forum.ForumIndex.ForumTree({
				title: TYPO3.l10n.localize("ForumIndex_Grid_Title"),
				dataProvider: Typo3Forum.ForumIndex.DataProvider
			})
		];
		config.plugins = [new Ext.ux.plugins.FitToParent()];
		Typo3Forum.ForumIndex.Application.superclass.constructor.call(this, config);
	}
});