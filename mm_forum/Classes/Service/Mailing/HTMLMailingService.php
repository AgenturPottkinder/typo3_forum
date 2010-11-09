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
	 * Service class for sending HTML mails.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Service_Mailing
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Service_Mailing_HTMLMailingService
	Extends Tx_MmForum_Service_Mailing_AbstractMailingService {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * An instance of the TYPO3 internal HTML mailing service.
		 * @var t3lib_htmlmail
		 */
	Protected $htmlMail;





		/*
		 * SERVICE METHODS
		 */





		/**
		 *
		 * Creates a new instance of this service.
		 *
		 */

	Public Function __construct() {
		$this->htmlMail =& t3lib_div::makeInstance('t3lib_htmlmail');
	}



		/**
		 *
		 * Sends a mail with a certain subject and bodytext to a recipient in form of a
		 * frontend user.
		 *
		 * @param  Tx_Extbase_Domain_Model_FrontendUser @recipient
		 *                             The recipient of the mail. This is a plain
		 *                             frontend user.
		 * @param  string $subject     The mail's subject
		 * @param  string $bodytext    The mail's bodytext
		 * @return void
		 *
		 */

	Public Function sendMail(Tx_Extbase_Domain_Model_FrontendUser $recipient, $subject, $bodytext) {
		$this->htmlMail->start();
		$this->htmlMail->recipient = $recipient->getName().' <'.$recipient->getEmail().'>';
		$this->htmlMail->subject = $subject;
		$this->htmlMail->from_email = $this->getDefaultSenderAddress();
		$this->htmlMail->from_name = $this->getDefaultSenderName();
		$this->htmlMail->returnPath = $this->getDefaultSenderAddress();
		$this->htmlMail->setHTML($this->htmlMail->encodeMsg($bodytext));
		$this->htmlMail->send($recipient->getEmail());
	}

}

?>