<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Form;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\Forum\Forum;
use TYPO3\CMS\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\Form\SelectViewHelper;

/**
 * ViewHelper that renders a selectbox with a hierarchical list of all forums.
 */
class ForumSelectViewHelper extends SelectViewHelper {

	/**
	 * The forum repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\ForumRepository
	 * @inject
	 */
	protected $forumRepository = NULL;

	/**
	 * Initializses the view helper arguments.
	 * @return void
	 */
	public function initializeArguments() {
		AbstractFormFieldViewHelper::initializeArguments();
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('multiple', 'string', 'if set, multiple select field');
		$this->registerTagAttribute('size', 'string', 'Size of input field');
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
	}

	/**
	 * Loads the option rows for this select field.
	 * @return array All option rows.
	 */
	protected function getOptions() {
		$rootForums = $this->forumRepository->findRootForums();
		$values = [];

		foreach ($rootForums As $rootForum) {
			$values[] = $this->getForumOptionRow($rootForum, TRUE);
		}
		return $values;
	}

	/**
	 * Recursively generates option rows for a forum and each subforum of this forum.
	 *
	 * @param Forum $forum The forum for which to generate the option row.
	 * @param boolean $isRoot TRUE, if the forum is a root category, otherwise FALSE.
	 * @return array An option row for the specified forum.
	 */
	protected function getForumOptionRow(Forum $forum, $isRoot = FALSE) {
		$result = [
			'name' => $forum->getTitle(),
			'uid' => $forum->getUid(),
			'_isRoot' => $isRoot,
			'_children' => []
		];
		foreach ($forum->getChildren() As $childForum) {
			$result['_children'][] = $this->getForumOptionRow($childForum, FALSE);
		}
		return $result;
	}

	/**
	 * Recursively renders all option tags.
	 *
	 * @param   array $options All option rows.
	 * @param integer $nestingLevel The current nesting level. Required for correct formatting.
	 * @return string
	 */
	protected function renderOptionTags($options, $nestingLevel = 1) {
		$content = '';
		foreach ($options as $option) {
			if ($option['_isRoot']) {
				$content .= '<optgroup label="' . htmlspecialchars($option['name']) . '">' . chr(10);
				$content .= $this->renderOptionTags($option['_children'], $nestingLevel + 1);
				$content .= '</optgroup>';
			} else {
				$isSelected = $this->isSelected($option['uid']);
				$indent = ($nestingLevel - 1) * 20;
				$style = 'padding-left: ' . $indent . 'px;';
				$content .= '<option style="' . $style . '" value="' . $option['uid'] . '" ' . ($isSelected ? 'selected="selected"' : '') . '>' . htmlspecialchars($option['name']) . '</option>' . chr(10);
				$content .= $this->renderOptionTags($option['_children'], $nestingLevel + 1);
			}
		}
		return $content;
	}
}
