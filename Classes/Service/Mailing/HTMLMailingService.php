<?php
namespace Mittwald\Typo3Forum\Service\Mailing;
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
 * @package    Typo3Forum
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

class HTMLMailingService extends \Mittwald\Typo3Forum\Service\Mailing\AbstractMailingService {


	/**
	 *
	 * Sends a mail with a certain subject and bodytext to a recipient in form of a
	 * frontend user.
	 *
	 * @param         \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $recipient
	 *                                                     The recipient of the mail. This is a plain
	 *                                                     frontend user.
	 * @param  string $subject                             The mail's subject
	 * @param  string $bodyText                            The mail's bodytext
	 *
	 * @return void
	 *
	 */

	public function sendMail(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $recipient, $subject, $bodyText) {
		$Typo3_mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');

		$Typo3_mail->setFrom( array( $this->getDefaultSenderAddress() => $this->getDefaultSenderName() ) )
					->setTo($recipient->getEmail())
					->setSubject($subject)
					->setBody($bodyText, 'text/html')
					->send();
	}

}
