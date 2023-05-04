<?php
namespace Mittwald\Typo3Forum\Service\Mailing;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
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

use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;

/**
 * Interface descriptor for mailing services.
 */
interface MailingServiceInterface
{

    /**
     * Sends a mail with a certain subject and bodytext to a recipient in form of a
     * frontend user.
     *
     * @param FrontendUser $recipient The recipient of the mail. This is a plain frontend user.
     * @param string $subject The mail's subject.
     * @param string $bodytext The mail's bodytext.
     */
    public function sendMail(FrontendUser $recipient, string $subject, string $bodytext): void;

    /**
     * Gets the preferred format of this mailing service.
     * @return string The preferred format of this mailing service.
     */
    public function getFormat(): string;
}
