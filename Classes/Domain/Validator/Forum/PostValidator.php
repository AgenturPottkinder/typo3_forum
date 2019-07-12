<?php
namespace Mittwald\Typo3Forum\Domain\Validator\Forum;

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

class PostValidator extends AbstractValidator {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
	 * @inject
	 */
	protected $userRepository = NULL;

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\Forum\Post $post
	 * @return bool
	 */
	protected function isValid($post) {
		$result = TRUE;

		if (trim($post->getText()) === '') {
			$this->addError('The post can\'t be empty!.', 1221560718);
			$result = FALSE;
		}

		if ($this->userRepository->findCurrent()->isAnonymous()) {
			if (empty($post->getAuthorName())) {
				$this->addError('Author name must be present when post is created by anonymous user.', 1335106565);
				$result = FALSE;
			} elseif (strlen($post->getAuthorName()) < 3) {
				$this->addError('Author name must be at least three characters long.', 1335106566);
				$result = FALSE;
			}
		}

		return $result;
	}

}
