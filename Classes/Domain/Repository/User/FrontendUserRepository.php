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
 * Repository class for frontend suers.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
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
class Tx_MmForum_Domain_Repository_User_FrontendUserRepository
	extends \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * An instance of the mm_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;

	/**
	 * Whole TypoScript mm_forum settings
	 * @var array
	 */
	protected $settings;


	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$this->typoScriptSetup = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
	}

	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 *
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 * @return void
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		$typoScriptArray = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		if(is_array($typoScriptArray)) {
			$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray($typoScriptArray);
			$this->settings = $ts['plugin']['tx_mmforum']['settings'];
		}
	}


	/**
	 * Finds the user that is currently logged in, or NULL if no user is logged in.
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser
	 *                             The user that is currently logged in, or NULL if
	 *                             no user is logged in.
	 */
	public function findCurrent() {
		$currentUserUid = (int)$GLOBALS['TSFE']->fe_user->user['uid'];
		return $currentUserUid ? $this->findByUid($currentUserUid) : new Tx_MmForum_Domain_Model_User_AnonymousFrontendUser();
	}


	/**
	 *
	 * Finds users for a specific filterset. Page navigation is possible.
	 *
	 * @param  integer $limit
	 * @param  array $orderings
	 * @param  boolean $onlyOnline
	 * @param  array $uids
	 * @return Array<Tx_MmForum_Domain_Model_User_FrontendUser>
	 *                               The selected subset of posts
	 *
	 */
	public function findByFilter($limit = '', $orderings = array(), $onlyOnline = FALSE, $uids = array()) {

		$query = $this->createQuery();
		$constraints = array();
		if (!empty($limit)) {
			$query->setLimit($limit);
		}
		if (!empty($orderings)) {
			$query->setOrderings($orderings);
		}
		if(!empty($onlyOnline)){
			$constraints[] = $query->greaterThan('is_online', time()-$this->settings['widgets']['onlinebox']['timeInterval']);
		}
		if(!empty($uids)) {
			$constraints[] = $query->in('uid', $uids);
		}
		if(!empty($constraints)){
			$query->matching($query->logicalAnd($constraints));
		}


		return  $query->execute();
	}


	public function countByFilter($onlyOnline = FALSE){
		$query = $this->createQuery();
		if(!empty($onlyOnline)){
			$query->matching($query->greaterThan('is_online', time()-$this->settings['widgets']['onlinebox']['timeInterval']));
		}
		return $query->execute()->count();
	}


	/**
	 * Returns an anonymous frontend user.
	 * @return Tx_MmForum_Domain_Model_User_AnonymousFrontendUser An anonymous frontend user.
	 */
	public function findAnonymous() {
		return new Tx_MmForum_Domain_Model_User_AnonymousFrontendUser();
	}



	/**
	 * Find all user with a part of $username in his name
	 * @param $part Part of the users nickname
	 * @param $filter Order by which field?
	 * @param $order ASC or DESC ordering
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser[] The frontend users with the specified username.
	 */
	public function findLikeUsername($part=NULL,$filter=NULL,$order=NULL) {
		$query = $this->createQuery();
		if($part !== NULL) {
			$query->matching($query->like('username','%'.$part.'%'));
		}
		if($filter === NULL || $order === NULL) {
			$query->setOrderings(array('username' => 'ASC'));
		} else {
			$query->setOrderings(array($filter => $order));
		}
		return $query->execute();
	}



	/**
	 * Finds users for the user index view. Sorting and page navigation to be
	 * handled in controller/view.
	 *
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser[] All users.
	 */
	public function findForIndex() {
		return $this->findAll();
	}



	/**
	 * Finds users for the top $limit view.
	 *
	 * @param int $limit
	 * @return Tx_MmForum_Domain_Model_User_FrontendUser[] The Top $limit User of this forum.
	 */
	public function findTopUserByPoints($limit=50) {
		$query = $this->createQuery();
		$query->setOrderings(array('tx_mmforum_points' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
									'username' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		$query->setLimit($limit);
		return $query->execute();
	}


}
