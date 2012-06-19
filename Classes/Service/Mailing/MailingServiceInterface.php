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
 * Interface descriptor for mailing services.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Service_Mailing
 * @version    $Id: AbstractMailingService.php 39978 2010-11-09 14:19:52Z mhelmich $
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

interface Tx_MmForum_Service_Mailing_MailingServiceInterface {



	/**
	 * Sends a mail with a certain subject and bodytext to a recipient in form of a
	 * frontend user.
	 *
	 * @param  Tx_Extbase_Domain_Model_FrontendUser $recipient The recipient of the mail. This is a plain frontend user.
	 * @param  string                               $subject   The mail's subject.
	 * @param  string                               $bodytext  The mail's bodytext.
	 * @return void
	 */
	public function sendMail(Tx_Extbase_Domain_Model_FrontendUser $recipient, $subject, $bodytext);



	/**
	 * Gets the preferred format of this mailing service.
	 * @return string The preferred format of this mailing service.
	 */
	public function getFormat();

}

?>
