/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <typo3@martin-helmich.de>                   *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General Public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General Public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General Public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */


Ext.ns('MmForum.ForumIndex')


/**
 *
 * ...
 *
 * @author     Martin Helmich <typo3@martin-helmich.de>
 * @package    MmForum
 * @subpackage Controller
 * @version    $Id: ForumController.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 3
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
//MmForum.ForumIndex.ForumTree = Ext.extend(Ext.ux.tree.TreeGrid, {
MmForum.ForumIndex.ForumTree = Ext.extend(Ext.tree.TreePanel, {

	border    : false,
	plain     : false,
	bodyStyle : 'padding: 10px;',
	stripeRows: true,
	enableDD  : true,
	ddGroup   : 'tx-mmforum-forumtree',

	columns    : [
		{
			header   : TYPO3.l10n.localize("ForumIndex_Grid_Column_Title"),
			dataIndex: 'title',
			width    : 300,
			sortable : false
		},
		{
			xtype    : 'numbercolumn',
			header   : TYPO3.l10n.localize("ForumIndex_Grid_Column_TopicCount"),
			dataIndex: 'topicCount',
			width    : 100,
			format   : '0.000/i',
			sortable : false
		},
		{
			xtype    : 'numbercolumn',
			header   : TYPO3.l10n.localize("ForumIndex_Grid_Column_PostCount"),
			dataIndex: 'postCount',
			width    : 100,
			format   : '0.000/i',
			sortable : false
		}
	],
	root       : {
		id         : 'forum-root',
		text       : TYPO3.l10n.localize("ForumIndex_Tree_Root"),
		expanded   : true,
		collapsible: false,
		iconCls    : 'tx-mmforum-icon-16-forum-root'
	},
	rootVisible: true,

	constructor: function (config)
	{
		config.loader = new Ext.tree.TreeLoader({
			directFn: config.dataProvider.getTreeNode
		});
		config.bbar = new Ext.Toolbar({
			plain    : true,
			ddGroup  : this.ddGroup,
			items    : [
				{
					text: 'New Forum (drag me!)'
				}
			],
			listeners: {
				render: this.createNewForumDragZone
			}
		});

		MmForum.ForumIndex.ForumTree.superclass.constructor.call(this, config);

		this.contextMenu = new MmForum.ForumIndex.ForumTreeContextMenu({
			tree: this
		});
		this.treeEditor = new Ext.tree.TreeEditor(this);
		this.treeEditor.on('complete', function (editor, newValue, oldValue)
		{
			if (newValue != oldValue)
			{
				var node = this.treeEditor.editNode;
				node.disable();
				this.dataProvider.updateForumTitle(this.treeEditor.editNode.attributes['__identity'], newValue, function ()
				{
					node.enable();
				});
			}
		}, this);

		this.on('contextmenu', function (node, event)
		{
			if (node.id != 'forum-root')
			{
				node.select();
				this.contextMenu.selectedNode = node;
				this.contextMenu.showAt(event.getXY());
			}
		}, this);

		this.on('nodedrop', function (dropEvent)
		{
			console.log(dropEvent);
			this.dataProvider.moveForum(
				dropEvent.dropNode.attributes['__identity'],
				dropEvent.target.attributes['__identity'],
				dropEvent.point,
				function ()
				{

				});
		}, this);

	},

	createNewForumDragZone: function ()
	{
		this.dragZone = new Ext.dd.DragZone(this.getEl(), {
			ddGroup    : this.ddGroup,
			getDragData: function (event)
			{
				this.proxyElement = document.createElement('div');
				var node = Ext.getCmp(event.getTarget('.x-btn').id);
				node.shouldCreateNewNode = true;

				return {
					ddel: this.proxyElement,
					item: node
				}
			},
			onInitDrag : function ()
			{
//				this.topPanel.app.activeTree.dontSetOverClass = true;
//				var clickedButton = this.dragData.item;
//				var cls = clickedButton.initialConfig.iconCls;

				this.proxyElement.shadow = false;
				this.proxyElement.innerHTML = '<div class="x-dd-drag-ghost-pagetree">' +
					'<span class="x-dd-drag-ghost-pagetree-icon tx-mmforum-icon-16-forum">&nbsp;</span>' +
					'<span class="x-dd-drag-ghost-pagetree-text"> New Forum' + '' + '</span>' +
					'</div>';

				this.proxy.update(this.proxyElement);
			}

		});
	},

	editForum: function (node)
	{
		var forumForm = new MmForum.ForumIndex.ForumForm({
			dataProvider: this.dataProvider,
			tree        : this,
			treeNode    : node
		});
		var forumWindow = new Ext.Window({
			title     : TYPO3.l10n.localize("ForumIndex_EditForum_Title"),
			modal     : true,
			items     : [forumForm],
			width     : '70%',
			autoHeight: true
		});

		forumWindow.show();
		forumForm.getForm().load({
			params : {
				'__identity': node.attributes['__identity']
			},
			waitMsg: TYPO3.l10n.localize("Loading")
		});

	}
});