<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;
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
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield;
use Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield;
use TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper;


/**
 *
 * ViewHelper that renders the value of a specific userfield for a user.
 *
 */
class UserfieldViewHelper extends CObjectViewHelper {

	/**
	 *
	 * Renders the userfield value.
	 *
	 * @param FrontendUser $user The user for whom the userfield value is to be rendered.
	 * @param AbstractUserfield $userfield The userfield
	 * @return string HTML content
	 *
	 */
	public function render(FrontendUser $user, AbstractUserfield $userfield) {
		if (!$userfield instanceof TyposcriptUserfield) {
			return new \InvalidArgumentException('Only userfields of type TyposcriptUserField are supported', 1435048481);
		}
		$data = $userfield->getValueForUser($user);
		$data = $this->convertDataToString($data);
		return parent::render($userfield->getTyposcriptPath() . '.output', implode(' ', $data));
	}

	/**
	 *
	 * Helper method that converts any type of variable to a string.
	 *
	 * @param mixed $data Anything
	 * @return string Anything converted to a string
	 *
	 */
	protected function convertDataToString($data) {
		if (is_array($data)) {
			foreach ($data as $k => &$v) {
				$v = $this->convertDataToString($v);
			}
			return $data;
		} else {
			if ($data instanceof \DateTime) {
				return $data->format('U');
			} else {
				return $data;
			}
		}
	}
}
