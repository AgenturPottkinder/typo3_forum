<?php
namespace Mittwald\MmForum\Domain\Model;


/*                                                                      *
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
 * Interface definition for objects that can be subscribed by users.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_User
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
interface SubscribeableInterface {



	/**
	 * Returns all users that have subscribed to this object.
	 * @return Tx_Extbase_Persistence_ObjectStorage<\Mittwald\MmForum\Domain\Model\User\FrontendUser> All subscribers.
	 */
	public function getSubscribers();



	/**
	 * Returns this object's title.
	 * @return string This object's title.
	 */
	public function getTitle();



	/**
	 * Adds a new subscriber.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user The new subscriber.
	 * @return void
	 */
	public function addSubscriber(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user);



	/**
	 * Removes a subscriber.
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $user The subscriber to be removed.
	 */
	public function removeSubscriber(\Mittwald\MmForum\Domain\Model\User\FrontendUser $user);

}
