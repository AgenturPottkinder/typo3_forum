<?php
namespace Mittwald\Typo3Forum\Service\Notification;
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
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Service
 * @version    $Id: NotificationService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
interface NotificationServiceInterface {



	/**
	 * @abstract
	 *
	 * @param \Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface $subscriptionObject
	 * @param Tx_Typo3Forum_Domain_Model_NotifiableInterface    $notificationObject
	 */
	public function notifySubscribers(\Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface $subscriptionObject,
	                                  Tx_Typo3Forum_Domain_Model_NotifiableInterface $notificationObject);



}
