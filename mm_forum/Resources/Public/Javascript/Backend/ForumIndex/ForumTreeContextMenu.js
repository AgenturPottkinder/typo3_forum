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

MmForum.ForumIndex.ForumTreeContextMenu = Ext.extend(Ext.menu.Menu, {

	constructor: function (config)
	{
		config.items = [
			new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_UpdateTitle'),
				iconCls: 'tx-mmforum-icon-16-forum-edit',
				scope  : this,
				handler: function ()
				{
					this.tree.treeEditor.triggerEdit(this.selectedNode);
				}
			}),
			new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_Edit'),
				iconCls: 'tx-mmforum-icon-16-edit',
				scope  : this,
				handler: function ()
				{
					this.tree.editForum(this.selectedNode);
				}
			}),
			new Ext.menu.Item({
				text   : TYPO3.l10n.localize('ForumIndex_Grid_Context_EditAcls'),
				iconCls: 'tx-mmforum-icon-16-forum-acledit'
			})
		]

		MmForum.ForumIndex.ForumTreeContextMenu.superclass.constructor.call(this, config);
	}
});