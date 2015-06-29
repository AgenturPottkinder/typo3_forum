<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

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

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUserGroup;
use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 *
 * Models a single ACL entry. This entry grants or denies access to a specific
 * operation (read, write posts, create topics...) to a single user group. These
 * ACL entries can be assigned to any \Mittwald\Typo3Forum\Domain\Model\Forum\Forum object and
 * are inherited down the forum tree unto each single post.
 *
 * Every object that implements the \Mittwald\Typo3Forum\Domain\Model\AccessibleInterface
 * provides methods to check the ACLs of the parent forums.
 */
class Access extends AbstractValueObject {

	/**
	 * Anyone.
	 */
	const LOGIN_LEVEL_EVERYONE = 0;


	/**
	 * Any logged in user
	 */
	const LOGIN_LEVEL_ANYLOGIN = 1;


	/**
	 * A specifiy user group
	 */
	const LOGIN_LEVEL_SPECIFIC = 2;

	/**
	 * The operation that is to be granted or denied.
	 * @var string
	 */
	protected $operation;


	/**
	 * Whether this entry is negated
	 * @var boolean
	 */
	protected $negate;


	/**
	 * The required login level. See the LOGIN_LEVEL_* constants.
	 * @var integer
	 */
	protected $loginLevel;


	/**
	 * The user group that is affected by this ACL entry. This property is only
	 * relevant if $loginLevel == LOGIN_LEVEL_SPECIFIC.
	 *
	 * @var FrontendUserGroup
	 */
	protected $affectedGroup;


	public function __construct($operation = NULL, $level = NULL, FrontendUserGroup $group = NULL) {
		$this->operation = $operation;
		$this->loginLevel = $level;
		$this->affectedGroup = $group;
	}

	/**
	 * Gets the affected operation.
	 * @return string The affected operation.
	 */
	public function getOperation() {
		return $this->operation;
	}

	/**
	 * Sets the affected operation.
	 *
	 * @param string $operation The affected operation
	 *
	 * @return void
	 */
	public function setOperation($operation) {
		$this->operation = $operation;
	}

	/**
	 * Determines if this ACL entry is negated.
	 * @return boolean TRUE, if this entry is negated.
	 */
	public function getNegated() {
		return $this->negate;
	}

	/**
	 * Determines if this ACL entry is negated.
	 * @return boolean TRUE, if this entry is negated.
	 */
	public function isNegated() {
		return $this->negate;
	}

	/**
	 * Gets the group for this entry.
	 * @return FrontendUserGroup group The group
	 */
	public function getGroup() {
		return $this->affectedGroup;
	}

	/**
	 * Determines whether this entry affects all visitors.
	 * @return boolean TRUE, when this entry affects all visitors, otherwise FALSE.
	 */

	public function isEveryone() {
		return $this->loginLevel == Access::LOGIN_LEVEL_EVERYONE;
	}

	/**
	 * Determines whether this entry requires any login.
	 * @return boolean TRUE when this entry requires any login, otherwise FALSE.
	 */
	public function isAnyLogin() {
		return $this->loginLevel == Access::LOGIN_LEVEL_ANYLOGIN;
	}

	/**
	 * Matches a certain user against this access rule.
	 *
	 * @param  \Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user
	 *                                 The user to be matched. Can also be NULL (for anonymous
	 *                                 users).
	 *
	 * @return bool                    TRUE if this access rule matches the given user, otherwise
	 *                                 FALSE. This result may be negated using the "negate" property.
	 */
	public function matches(\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser $user = NULL) {
		$result = FALSE;
		if ($this->loginLevel === self::LOGIN_LEVEL_EVERYONE) {
			$result = TRUE;
		}

		if ($this->loginLevel === self::LOGIN_LEVEL_ANYLOGIN && $user !== NULL && !$user->isAnonymous()) {
			$result = TRUE;
		}

		if ($this->loginLevel === self::LOGIN_LEVEL_SPECIFIC) {
			if (!is_null($user)) {
				foreach ($user->getUsergroup() as $group) {
					/** @var $group FrontendUserGroup */
					if ($group->getUid() === $this->affectedGroup->getUid()) {
						$result = TRUE;
						break;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * Negates this entry.
	 *
	 * @param boolean $negate TRUE to negate
	 *
	 * @return void
	 */
	public function setNegated($negate) {
		$this->negate = $negate;
	}

	/**
	 * Sets the group.
	 *
	 * @param FrontendUserGroup $group The group
	 *
	 * @return void
	 */
	public function setAffectedGroup(FrontendUserGroup $group) {
		$this->affectedGroup = $group;
	}
}
