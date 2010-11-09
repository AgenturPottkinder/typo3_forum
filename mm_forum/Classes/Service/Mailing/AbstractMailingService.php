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
	 * Service class for notifications. This service notifies subscribers of
	 * forums and topic about new posts within the subscribed objects.
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

Abstract Class Tx_MmForum_Service_Mailing_AbstractMailingService
	Extends Tx_MmForum_Service_AbstractService {





		/*
		 * CONSTANTS
		 */





	Const MAILING_FORMAT_HTML = 'html';
	Const MAILING_FORMAT_PLAIN = 'txt';





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The format in which this service sends mails. Usually, this would be either
		 * 'html' or 'txt'.
		 * @var string
		 */
	Protected $format = Tx_MmForum_Service_Mailing_AbstractMailingService::MAILING_FORMAT_HTML;





		/*
		 * SERVICE METHODS
		 */





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

	Abstract Public Function sendMail(Tx_Extbase_Domain_Model_FrontendUser $recipient, $subject, $bodytext);



		/**
		 *
		 * Gets the preferred format of this mailing service.
		 * @return string The preferred format of this mailing service.
		 *
		 */

	Public Function getFormat() { Return $this->format; }





		/*
		 * HELPER METHODS
		 */





		/**
		 *
		 * Gets the default sender name. Can be configured in the typoscript setup.
		 * @return string The default sender name.
		 *
		 */

	Protected Function getDefaultSenderName() {
		Return $this->settings['mailing.']['sender.']['name'];
	}



		/**
		 *
		 * Gets the default sender address. Can be configured in the typoscript setup.
		 * @return string The default sender address.
		 *
		 */

	Protected Function getDefaultSenderAddress() {
		Return $this->settings['mailing.']['sender.']['address'];
	}



		/**
		 *
		 * Gets the default sender. This is composed of the default sender name and the
		 * default sender address.
		 * @return string The default sender.
		 *
		 */

	Protected Function getDefaultSender() {
		Return $this->getDefaultSenderName().' <'.$this->getDefaultSenderAddress().'>';
	}



		/**
		 *
		 * Gets the preferred character set for sent mails. This usually is TYPO3's
		 * renderCharset.
		 * @return string The preferred charset.
		 *
		 */
	
	Protected Function getCharset() {
		Return $GLOBALS['TSFE']->renderCharset;
	}

}

?>
