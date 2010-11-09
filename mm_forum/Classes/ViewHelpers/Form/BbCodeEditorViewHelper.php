<?php

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2010 Martin Helmich <m.helmich@mittwald.de>                     *
 *           Mittwald CM Service GmbH & Co KG                           *
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



	/**
	 *
	 * ViewHelper that renders a textarea with additional bb code buttons.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage ViewHelpers_Form
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_ViewHelpers_Form_BbCodeEditorViewHelper
	Extends Tx_Fluid_ViewHelpers_Form_TextareaViewHelper {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * Configuration array
		 * @var array
		 */
	Protected $configuration = NULL;

		/**
		 * Panels that contain bb code buttons.
		 * @var array<Tx_MmForum_TextParser_Panel_AbstractPanel>
		 */
	Protected $panels;





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Initializes the view helper arguments.
		 * @return void
		 *
		 */

	Public Function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('configuration', 'string', 'Path to TS configuration', FALSE, 'plugin.tx_mmforum.settings.textParsing.editorPanel');
	}



		/**
		 *
		 * Loads the editor configuration
		 *
		 * @param  string $configurationPath The typoscript setup path in which the
		 *                                   editor configuration is stored.
		 * @return   void
		 *
		 */

	Protected Function loadConfiguration($configurationPath) {
		If($this->configuration !== NULL) Return;

		$this->configuration = Tx_MmForum_Utility_TypoScript::loadTyposcriptFromPath($configurationPath);
		ForEach($this->configuration['panels.'] As $name => $configuration) {
			$className = $configuration['className'];
			$newService = t3lib_div::makeInstance($className);
			$newService->injectSettings($configuration);
			$newService->setEditorId($this->arguments->offsetGet('id'));
			If(!$newService InstanceOf Tx_MmForum_TextParser_Panel_AbstractPanel)
				Throw New Tx_Extbase_Object_InvalidClass (
					"All classes in $configurationpath.panels must be instances of Tx_MmForum_TextParser_Panel_AbstractPanel!", 1285143384);
			$this->panels[] = $newService;
		}
	}





		/*
		 * METHODS
		 */





		/**
		 *
		 * Renders the editor. This method first adds some javascript inclusions to the
		 * page header, then renders the options panel and finally renders the main
		 * textarea using the inherited render() method.
		 *
		 * @return string HTML content
		 *
		 */

	Public Function render() {

		$this->loadConfiguration($this->arguments['configuration']);

		$GLOBALS['TSFE']->additionalHeaderData['mm_forum_JQuery']
			= '<script src="'.t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Javascript/jquery-1.4.3.min.js" type="text/javascript"></script>';
		$GLOBALS['TSFE']->additionalHeaderData['mm_forum_Editor']
			= '<script src="'.t3lib_extMgm::siteRelPath('mm_forum').'Resources/Public/Javascript/BBCodeEditor.js" type="text/javascript"></script>';

		$content  = $this->getParserOptionsPanel();
		$content .= parent::render();

		Return $content;
	}



		/**
		 *
		 * Renders the bb code panels.
		 * @return string HTML content
		 *
		 */

	Protected Function getParserOptionsPanel() {
		$panelContent = '';

		ForEach($this->panels As $panel) {
			$panelContent .= $panel->render();
		} $panelContent .= '<div style="clear:left;"></div>';

		Return $panelContent;
	}

}

?>