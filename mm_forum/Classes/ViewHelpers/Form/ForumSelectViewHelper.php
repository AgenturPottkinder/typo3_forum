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
	 * ViewHelper that renders a selectbox with a hierarchical list of all forums.
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

Class Tx_MmForum_ViewHelpers_Form_ForumSelectViewHelper
	Extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The forum repository.
		 * @var Tx_MmForum_Domain_Repository_Forum_ForumRepository
		 */
	Protected $forumRepository = NULL;





		/*
		 * INITIALIZATION
		 */





		/**
		 *
		 * Initializes the view helper.
		 * @return void
		 *
		 */

	Public Function initialize() {
		parent::initialize();
		$this->forumRepository =&
			t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_Forum_ForumRepository');
	}



		/**
		 *
		 * Initializses the view helper arguments.
		 * @return void
		 *
		 */

	Public Function initializeArguments() {
		Tx_Fluid_ViewHelpers_Form_AbstractFormFieldViewHelper::initializeArguments();
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('multiple', 'string', 'if set, multiple select field');
		$this->registerTagAttribute('size', 'string', 'Size of input field');
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
	}





		/*
		 * RENDERING METHODS
		 */





		/**
		 *
		 * Loads the option rows for this select field.
		 * @return array All option rows.
		 *
		 */

	Protected Function getOptions() {
		$rootForums = $this->forumRepository->findRootForums();
		$values = array();

		ForEach($rootForums As $rootForum)
			$values[] = $this->getForumOptionRow($rootForum, TRUE);
		Return $values;
	}



		/**
		 *
		 * Recursively generates option rows for a forum and each subforum of this forum.
		 *
		 * @param  Tx_MmForum_Domain_Model_Forum_Forum $forum
		 *                             The forum for which to generate the option row.
		 * @param  boolean $isRoot     TRUE, if the forum is a root category, otherwise
		 *                             FALSE.
		 * @return array               An option row for the specified forum.
		 *
		 */

	Protected Function getForumOptionRow(Tx_MmForum_Domain_Model_Forum_Forum $forum, $isRoot=FALSE) {
		$result = Array ( 'name'      => $forum->getTitle(),
		                  'uid'       => $forum->getUid(),
		                  '_isRoot'   => $isRoot,
		                  '_children' => Array() );
		ForEach($forum->getChildren() As $childForum)
			$result['_children'][] = $this->getForumOptionRow($childForum, FALSE);
		Return $result;
	}



		/**
		 *
		 * Recursively renders all option tags.
		 *
		 * @param    array $options      All option rows.
		 * @param  integer $nestingLevel The current nesting level. Required for correct
		 *                               formatting.
		 * @return  string
		 *
		 */

	Protected Function renderOptionTags($options, $nestingLevel=1) {
		$content = '';
		ForEach($options As $option) {
			If($option['_isRoot']) {
				$content .= '<optgroup label="'.htmlspecialchars($option['name']).'">' . chr(10);
				$content .= $this->renderOptionTags($option['_children'], $nestingLevel+1);
				$content .= '</optgroup>';
			} Else {
				$isSelected = $this->isSelected($option['uid']);
				$indent = ($nestingLevel-1)*20;
				$style = 'padding-left: '.$indent.'px;';
				$content .= '<option style="'.$style.'" value="'.$option['uid'].'" '.($isSelected?'selected="selected"':'').'>'.htmlspecialchars($option['name']).'</option>' . chr(10);
				$content .= $this->renderOptionTags($option['_children'], $nestingLevel+1);
			}
		} Return $content;
	}

}

?>