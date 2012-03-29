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
 * Service class for sending plain text emails.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Service_Mailing
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Service_Mailing_PlainMailingService
	extends Tx_MmForum_Service_Mailing_AbstractMailingService
{



	/*
	 * ATTRIBUTES
	 */



	/**
	 * The format in which this service sends mails.
	 *
	 * @var string
	 */
	protected $format = Tx_MmForum_Service_Mailing_AbstractMailingService::MAILING_FORMAT_PLAIN;



	/*
	  * SERVICE METHODS
	  */



	/**
	 *
	 * Sends a mail with a certain subject and bodytext to a recipient in form of a
	 * frontend user.
	 *
	 * @param         Tx_Extbase_Domain_Model_FrontendUser @recipient
	 *                                                     The recipient of the mail. This is a plain
	 *                                                     frontend user.
	 * @param  string $subject                             The mail's subject
	 * @param  string $bodytext                            The mail's bodytext
	 *
	 * @return void
	 *
	 */
	public function sendMail(Tx_Extbase_Domain_Model_FrontendUser $recipient,
	                         $subject, $bodytext)
	{
		if ($recipient->getEmail())
		{
			t3lib_div::plainMailEncoded(
				$recipient->getEmail(), $subject, $bodytext,
				$this->getHeaders($recipient, $subject, $bodytext));
		}
	}



	/*
	 * HELPER METHODS
	 */



	/**
	 *
	 * Generates the e-mail headers for a certain recipient, subject and bodytext.
	 *
	 * @param  Tx_Extbase_Domain_Model_FrontendUser $recipient
	 *                                                           The recipient of the email.
	 * @param  string                               $subject     The mail's subject.
	 * @param  string                               $bodytext    The mail's bodytext.
	 *
	 * @return string              The mail headers.
	 *
	 */
	protected function getHeaders(Tx_Extbase_Domain_Model_FrontendUser $recipient,
	                              $subject, $bodytext)
	{
		$headerArray  = array(
			'From'         => $this->getDefaultSender(),
			'Content-Type' => 'text/plain; charset=' . $this->getCharset()
		);
		$headerString = "";

		foreach ($headerArray as $headerKey => $headerValue)
		{
			$headerString .= "$headerKey: $headerValue\r\n";
		}

		return $headerString;
	}



}
