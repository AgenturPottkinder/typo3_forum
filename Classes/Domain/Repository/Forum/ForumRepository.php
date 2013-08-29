<?php

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



/**
 *
 * Repository class for forum objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
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
class Tx_MmForum_Domain_Repository_Forum_ForumRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {



	/**
	 * @var Tx_MmForum_Service_Authentication_AuthenticationServiceInterface
	 */
	protected $authenticationService = NULL;



	/**
	 * @param Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService
	 */
	public function injectAuthenticationService(Tx_MmForum_Service_Authentication_AuthenticationServiceInterface $authenticationService) {
		$this->authenticationService = $authenticationService;
	}



	/**
	 * Finds all forums for the index view.
	 * @return Tx_MmForum_Domain_Model_Forum_Forum[] All forums for the index view.
	 */
	public function findForIndex() {
		return $this->findRootForums();
	}

	/**
	 *
	 * Finds forum for a specific filterset. Page navigation is possible.
	 *
	 * @param  array $uids
	 *
	 * @return Tx_MmForum_Domain_Model_Forum_Topic[]
	 *                               The selected subset of topcis
	 *
	 */
	public function findByUids($uids) {

		$query = $this->createQuery();
		$constraints = array();
		if(!empty($uids)) {
			$constraints[] = $query->in('uid', $uids);
		}
		if(!empty($constraints)){
			$query->matching($query->logicalAnd($constraints));
		}

		return  $query->execute();
	}

	/**
	 * Finds all root forums.
	 * @return Tx_MmForum_Domain_Model_Forum_Forum[] All forums for the index view.
	 */
	public function findRootForums() {
		$query  = $this->createQuery();
		$result = $query
			->matching($query->equals('forum', 0))
			->setOrderings(array('sorting' => 'ASC', 'uid' => 'ASC'))
			->execute();
        return $this->filterByAccess($result, 'read');
	}



	/**
	 * @param Iterator $objects
	 * @param string   $action
	 *
	 * @return array
	 */
	protected function filterByAccess(Iterator $objects, $action = 'read') {
		$result = array();
        foreach ($objects as $forum) {
			if ($this->authenticationService->checkAuthorization($forum, $action)) {
				$result[] = $forum;
			}
		}
		return $result;
	}



	public function findBySubscriber(Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$query = $this->createQuery();
		$query
			->matching($query->contains('subscribers', $user))
			->setOrderings(array('lastPost.crdate' => 'ASC'));
		return $query->execute();
	}


	/**
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 * @param Tx_MmForum_Domain_Model_User_FrontendUser $user
	 * @return bool
	 */
	public function getForumReadByUser(Tx_MmForum_Domain_Model_Forum_Forum $forum, Tx_MmForum_Domain_Model_User_FrontendUser $user) {
		$sql ='SELECT f.uid
			   FROM tx_mmforum_domain_model_forum_forum AS f
			   LEFT JOIN tx_mmforum_domain_model_user_readforum AS rf
					   ON rf.uid_foreign = f.uid AND rf.uid_local = '.intval($user->getUid()).'
			   WHERE rf.uid_local IS NULL AND f.uid='.intval($forum->getUid());
		echo $sql;
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);
		$query->statement($sql);
		$res = $query->execute();
		if($res != false) {
			return true;
		} else {
			return false;
		}
	}



}
