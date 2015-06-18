<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;
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
 * ViewHelper that renders the value of a specific userfield for a user.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage ViewHelpers_User
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class UserfieldViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\CObjectViewHelper {



	/**
	 *
	 * Initializes the view helper arguments.
	 *
	 */

	public function initializeArguments() { /* Empty! Haw, haw! */
	}



	/**
	 *
	 * Renders the userfield value.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser                $user
	 *                             The user for whom the userfield value is to be
	 *                             rendered.
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield $userfield
	 *                             The userfield.
	 * @return string              HTML content
	 *
	 */

	public function render(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user,
	                       \Mittwald\Typo3Forum\Domain\Model\User\Userfield\AbstractUserfield $userfield) {

		if ($userfield instanceof \Mittwald\Typo3Forum\Domain\Model\User\Userfield\TyposcriptUserfield) {
			$data = $userfield->getValueForUser($user);
			$data = $this->convertDataToString($data);
			return parent::render($userfield->getTyposcriptPath() . '.output', implode(' ', $data));
		} else {
			return 'Do not know what to do!';
		}

	}



	/**
	 *
	 * Helper method that converts any type of variable to a string.
	 *
	 * @param   mixed $data Anything
	 * @return string       Anything converted to a string
	 *
	 */

	protected function convertDataToString($data) {
		if (is_array($data)) {
			foreach ($data as $k => &$v) {
				$v = $this->convertDataToString($v);
			}
			return $data;
		} else {
			if ($data instanceof DateTime) {
				return $data->format('U');
			} else {
				return $data;
			}
		}
	}

}

?>