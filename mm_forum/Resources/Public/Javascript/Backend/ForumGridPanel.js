Ext.ns('MM.Forum');

MM.Forum.ForumGridPanel = Ext.extend(Ext.grid.GridPanel, {
	title: 'Forenverwaltung',
	cm: [
	     new Ext.grid.Column({
	    	 header: "Name",
	    	 dataIndex: "name"
	     })
	]
});