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


Ext.ns('Typo3Forum.ForumIndex')

Typo3Forum.ForumIndex.ForumTreeContextMenu = Ext.extend(Ext.menu.Menu, {

	constructor: function (config)
	{
		config.items = [
			new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_New'),
				iconCls: 'tx-typo3forum-icon-16-forum-new',
				scope  : this,
				handler: function ()
				{
					var newForumNode = new Ext.tree.TreeNode({
						text    : TYPO3.l10n.localize('ForumIndex_NewForum_DefaultTitle'),
						iconCls : 'tx-typo3forum-icon-16-forum',
						children: []
					})
					this.selectedNode.appendChild(newForumNode);
					this.tree.treeEditor.triggerEdit(newForumNode);
				}
			}),
			new Ext.menu.Separator({}),
			this.updateTitleItem = new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_UpdateTitle'),
				iconCls: 'tx-typo3forum-icon-16-forum-edit',
				scope  : this,
				handler: function ()
				{
					this.tree.treeEditor.triggerEdit(this.selectedNode);
				}
			}),
			this.editItem = new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_Edit'),
				iconCls: 'tx-typo3forum-icon-16-edit',
				scope  : this,
				handler: function ()
				{
					this.tree.editForum(this.selectedNode);
				}
			}),
			this.editAclsItem = new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_EditAcls'),
				iconCls: 'tx-typo3forum-icon-16-forum-acledit'
			})
		]

		Typo3Forum.ForumIndex.ForumTreeContextMenu.superclass.constructor.call(this, config);
	},

	setSelectedNode: function (node)
	{
		this.selectedNode = node;
		console.log(this.items);

		if (node.id == 'forum-root')
		{
			this.editItem.disable();
			this.updateTitleItem.disable();
			this.editAclsItem.disable();
		}
		else
		{
			this.editItem.enable();
			this.updateTitleItem.enable();
			this.editAclsItem.enable();
		}
	}
});