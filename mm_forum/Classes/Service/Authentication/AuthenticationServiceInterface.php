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
 * An interface for authentication services, in case anyone wants to
 * implement his own solution... ;)
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Service
 * @version    $Id: AuthenticationServiceInterface.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
interface Tx_MmForum_Service_Authentication_AuthenticationServiceInterface {



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_AccessibleInterface $object
	 */
	public function assertReadAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Forum $forum
	 */
	public function assertNewTopicAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Topic $topic
	 */
	public function assertNewPostAuthorization(Tx_MmForum_Domain_Model_Forum_Topic $topic);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post
	 */
	public function assertEditPostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_Forum_Post $post
	 */
	public function assertDeletePostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_AccessibleInterface $object
	 */
	public function assertModerationAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_AccessibleInterface $object
	 */
	public function assertAdministrationAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object);



	/**
	 * @abstract
	 *
	 * @param Tx_MmForum_Domain_Model_AccessibleInterface $object
	 * @param                                             $action
	 */
	public function checkAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object, $action);



}
