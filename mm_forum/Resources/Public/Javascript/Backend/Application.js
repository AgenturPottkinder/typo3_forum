Ext.ns('MM.Forum');

MM.Forum.Application = {
	
	init: function(config) {
		this.tabPanel = new Ext.TabPanel({
			activeTab: 0,
			items: [
			    new MM.Forum.ForumGridPanel({
			    	title: "Foren"
			    })
			]
		})
	},
		
	run: function(config) {
		this.init(config)
		
		this.tabPanel.renderTo(config.renderTo);
		
		Ext.Msg.alert("Geladen!", "mm_forum wurde geladen!");
	}
		
}