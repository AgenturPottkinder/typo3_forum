<?php
namespace Mittwald\Typo3Forum\Domain\Repository\Forum;

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 *
 * Repository class for forum objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Repository_Forum
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class ForumRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {


	/**
	 * @var \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface
	 * @inject
	 */
	protected $authenticationService = NULL;


	/**
	 * @param \Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface $authenticationService
	 */
	public function injectAuthenticationService(\Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}


	/**
	 * Finds all forums for the index view.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum[] All forums for the index view.
	 */
	public function findForIndex() {
		return $this->findRootForums();
	}

	/**
	 * Finds all root forums.
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Forum[] All forums for the index view.
	 */
	public function findRootForums() {
		$query = $this->createQuery();
		$result = $query
			->matching($query->equals('forum', 0))
			->setOrderings(array('sorting' => 'ASC', 'uid' => 'ASC'))
			->execute();

		return $this->filterByAccess($result, 'read');
	}

	/**
	 * @param QueryResultInterface $objects
	 * @param string               $action
	 *
	 * @return array
	 */
	protected function filterByAccess(QueryResultInterface $objects, $action = 'read') {
		$result = array();
		foreach ($objects as $forum) {
			if ($this->authenticationService->checkAuthorization($forum, $action)) {
				$result[] = $forum;
			}
		}

		return $result;
	}

	/**
	 *
	 * Finds forum for a specific filterset. Page navigation is possible.
	 *
	 * @param  array $uids
	 *
	 * @return \Mittwald\Typo3Forum\Domain\Model\Forum\Topic[]
	 *                               The selected subset of topcis
	 *
	 */
	public function findByUids($uids) {

		$query = $this->createQuery();
		$constraints = array();
		if (!empty($uids)) {
			$constraints[] = $query->in('uid', $uids);
		}
		if (!empty($constraints)) {
			$query->matching($query->logicalAnd($constraints));
		}

		return $query->execute();
	}

	/**
	 * @param FrontendUser $user
	 *
	 * @return QueryResultInterface
	 */
	public function findBySubscriber(FrontendUser $user) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('subscribers', $user))
			->setOrderings(array('lastPost.crdate' => 'ASC'));

		return $query->execute();
	}

}
