<?php
namespace Mittwald\MmForum\Domain\Factory\User;


/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * Factory class for post reports.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Factory_User
 * @version    $Id$
 *
 * @copyright  Ruven Fehling <r.fehling@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class PrivateMessagesFactory extends \Mittwald\MmForum\Domain\Factory\AbstractFactory {



	/*
	 * ATTRIBUTES
	 */




	/*
	 * FACTORY METHODS
	 */



	/**
	 *
	 * Creates a new report.
	 *
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $opponent
	 * @param \Mittwald\MmForum\Domain\Model\User\FrontendUser $feUser
	 * @param \Mittwald\MmForum\Domain\Model\User\PrivateMessagesText $text
	 * @param int $type
	 * @param int $userRead
	 * @return \Mittwald\MmForum\Domain\Model\User\PrivateMessages
	 *                             The new private message.
	 *
	 */
	public function createPrivateMessage(\Mittwald\MmForum\Domain\Model\User\FrontendUser $opponent,
										 \Mittwald\MmForum\Domain\Model\User\FrontendUser $feUser,
										 \Mittwald\MmForum\Domain\Model\User\PrivateMessagesText $text,
										 $type,
										 $userRead) {
		$pm = $this->getClassInstance();
		$pm->setFeuser($feUser);
		$pm->setOpponent($opponent);
		$pm->setType($type);
		$pm->setCrdate(new DateTime());
		$pm->setUserRead($userRead);
		$pm->setMessage($text);
		return $pm;
	}



}

?>