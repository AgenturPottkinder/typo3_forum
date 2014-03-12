<?php
namespace Mittwald\MmForum\ViewHelpers\Bootstrap\Form;


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



/**
 *
 * ViewHelper that renders a form row.
 *
 * @author     Martin Helmich <typo3@martin-helmich.de>
 * @package    MmForum
 * @subpackage ViewHelpers_Control
 * @version    $Id: BigButtonViewHelper.php 52309 2011-09-20 18:54:26Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <typo3@martin-helmich.de>
 *             http://www.martin-helmich.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class RowViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {



	protected $tagName = 'div';



	/**
	 * @return void
	 */
	public function initialize() {
		parent::initialize();
	}



	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('llLabel', 'string', 'Locallang key for label.', FALSE, '');
		$this->registerArgument('label', 'string', 'Hardcoded label (better to use llLabel instead).', FALSE, '');
		$this->registerArgument('error', 'string', 'Error property path.', FALSE);
		$this->registerArgument('errorLLPrefix', 'string', 'Error label locallang prefix.', FALSE);
	}



	public function render() {
		$class = 'control-group';

		if ($this->arguments['llLabel']) {
			$label = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($this->arguments['llLabel'], 'mm_forum');
		} else {
			$label = $this->arguments['label'];
		}

		if ($this->arguments['error']) {
			$results = $this->controllerContext->getRequest()->getOriginalRequestMappingResults()->getSubResults();

			$propertyPath = explode('.', $this->arguments['error']);
			foreach ($propertyPath as $currentPropertyName) {
				$errors = $this->getErrorsForProperty($currentPropertyName, $results);
			}

			if (count($errors) > 0) {
				$class .= ' error';
				$errorContent = '';
				foreach ($errors as $error) {
					$errorText = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($this->arguments['errorLLPrefix'] . '_' . $error->getCode(),
					                                                        'mm_forum');
					if (!$errorText) {
						$errorText = 'TRANSLATE: ' . $this->arguments['errorLLPrefix'] . '_' . $error->getCode();
					}
					$errorContent .= '<p class="help-block">' . $errorText . '</p>';
				}
			}
		} else {
			$errorText = '';
		}

		$label   = '<label>' . $label . '</label>';
		$content = '<div>' . $this->renderChildren() . $errorContent . '</div>';

		$this->tag->addAttribute('class', $class);
		$this->tag->setContent($label . $content);

		return $this->tag->render();
	}



	/**
	 * Find errors for a specific property in the given errors array
	 *
	 * @param string $propertyName The property name to look up
	 * @param array  $errors       An array of Tx_Fluid_Error_Error objects
	 * @return array An array of errors for $propertyName
	 */
	protected function getErrorsForProperty($propertyName, $errors) {
		foreach ($errors as $name => $error) {
			if ($name === $propertyName) {
				return array_unique($error->getErrors());
			}
		}
		return array();
	}



}
