<?php
namespace Mittwald\Typo3Forum\Domain\Repository\User;

	/*                                                                    - *
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
use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;


/**
 *
 * Repository class for frontend suers.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Repository_User
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class FrontendUserRepository extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager
	 * @inject
	 */
	protected $frontendConfigurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Service\TypoScriptService
	 * @inject
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript typo3_forum settings
	 * @var array
	 */
	protected $settings;

	public function initializeObject() {
		$typoScriptArray = $this->frontendConfigurationManager->getTypoScriptSetup();
		if (is_array($typoScriptArray)) {
			$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptArray);
			$this->settings = $ts['plugin']['tx_typo3forum']['settings'];
		}
	}

	/**
	 * Finds the user that is currently logged in, or NULL if no user is logged in.
	 *
	 * @return FrontendUser The user that is currently logged in, or AnonymousFrontendUser if no user is logged in.
	 */
	public function findCurrent() {
		$currentUserUid = (int) $GLOBALS['TSFE']->fe_user->user['uid'];
		return $currentUserUid ? $this->findByUid($currentUserUid) : new AnonymousFrontendUser();
	}

	/**
	 *
	 * Finds users for a specific filterset. Page navigation is possible.
	 *
	 * @param integer $limit
	 * @param array   $orderings
	 * @param boolean $onlyOnline
	 * @param array   $uids
	 *
	 * @return Array<\Mittwald\Typo3Forum\Domain\Model\User\FrontendUser>
	 *                               The selected subset of posts
	 *
	 */
	public function findByFilter($limit = 0, $orderings = [], $onlyOnline = FALSE, $uids = []) {
		$query = $this->createQuery();
		$constraints = [];
		if ($limit > 0) {
			$query->setLimit($limit);
		}
		if (!empty($orderings)) {
			$query->setOrderings($orderings);
		}
		if (!empty($onlyOnline)) {
			$constraints[] = $query->greaterThan('is_online', time() - $this->settings['widgets']['onlinebox']['timeInterval']);
		}
		if (!empty($uids)) {
			$constraints[] = $query->in('uid', $uids);
		}
		if (!empty($constraints)) {
			$query->matching($query->logicalAnd($constraints));
		}


		return $query->execute();
	}


	public function countByFilter($onlyOnline = FALSE) {
		$query = $this->createQuery();
		if (!empty($onlyOnline)) {
			$query->matching($query->greaterThan('is_online', time() - $this->settings['widgets']['onlinebox']['timeInterval']));
		}

		return $query->execute()->count();
	}


	/**
	 * Returns an anonymous frontend user.
	 * @return AnonymousFrontendUser An anonymous frontend user.
	 */
	public function findAnonymous() {
		return new AnonymousFrontendUser();
	}


	/**
	 * Find all user with a part of $username in his name
	 *
	 * @param string $part Part of the users nickname
	 * @param string $filter Order by which field?
	 * @param string $order QueryInterface::ORDER_ASCENDING or QueryInterface::ORDER_DESCENDING ordering
	 * @return FrontendUser[] The frontend users with the specified username.
	 */
	public function findLikeUsername($part = NULL, $filter = NULL, $order = NULL) {
		$query = $this->createQuery();
		if ($part !== NULL) {
			$query->matching($query->like('username', '%' . $part . '%'));
		}
		if ($filter === NULL || $order === NULL) {
			$query->setOrderings(['username' => QueryInterface::ORDER_ASCENDING]);
		} else {
			$query->setOrderings([$filter => $order]);
		}
		return $query->execute();
	}


	/**
	 * Finds users for the user index view. Sorting and page navigation to be
	 * handled in controller/view.
	 *
	 * @return FrontendUser[] All users.
	 */
	public function findForIndex() {
		return $this->findAll();
	}


	/**
	 * Finds users for the top $limit view.
	 *
	 * @param int $limit
	 *
	 * @return FrontendUser[] The Top $limit User of this forum.
	 */
	public function findTopUserByPoints($limit = 50) {
		$query = $this->createQuery();
		$query->setOrderings([
			'tx_typo3forum_points' => QueryInterface::ORDER_DESCENDING,
			'username' => QueryInterface::ORDER_ASCENDING,
		]);
		$query->setLimit($limit);

		return $query->execute();
	}


}
