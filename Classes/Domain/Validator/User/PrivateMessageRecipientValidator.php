<?php
namespace Mittwald\Typo3Forum\Domain\Validator\User;

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

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

/**
 *
 * A validator class for author names. This class validates a username ONLY if
 * no user is currently logged in.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain\Validator\User
 * @version    $Id$
 *
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class PrivateMessageRecipientValidator extends AbstractValidator {


	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 * @inject
	 */
	protected $userRepository = NULL;

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param $value
	 * @return bool
	 */
	protected function isValid($value) {
		$result = TRUE;

		if (!$this->userRepository->findOneByUsername($value)) {
			$this->addError('PM reciepient user not found!', 1372429326);
			$result = FALSE;
		}
		$user = $this->userRepository->findCurrent();
		if ($user->getUsername() == $value) {
			$this->addError('You can\'t write yourself a message :)', 1372682275);
			$result = FALSE;
		}

		return $result;
	}
}
