<?php

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
	 * An interface for authentication services, in case anyone wants to
	 * implement his own solution... ;)
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Service
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Interface Tx_MmForum_Domain_Service_AuthenticationServiceInterface {

	Public Function injectFrontendUser(Tx_MmForum_Domain_Model_User_FrontendUser $user=NULL);

	Public Function assertReadAuthorization(Tx_MmForum_Domain_Model_AccessibleInterface $object);

	Public Function assertNewTopicAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum);

	Public Function assertNewPostAuthorization(Tx_MmForum_Domain_Model_Forum_Topic $topic);

	Public Function assertEditPostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post);

	Public Function assertDeletePostAuthorization(Tx_MmForum_Domain_Model_Forum_Post $post);

	Public Function assertModerationAuthorization(Tx_MmForum_Domain_Model_Forum_Forum $forum);

}

?>