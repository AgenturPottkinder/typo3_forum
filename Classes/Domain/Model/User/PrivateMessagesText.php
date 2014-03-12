<?php
namespace Mittwald\MmForum\Domain\Model\User;

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
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Model_User
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php

 */

class PrivateMessagesText extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * The submitted text
	 * @var string
	 */
	public $messageText;


	/**
	 * Get the text of this pm
	 * @return string The text
	 */
	public function getMessageText() {
		return $this->messageText;
	}


	/**
	 * Get the short text of this pm
	 * @return string The short text
	 */
	public function getShortMessageText() {
		$limit = 80;
		$text = $this->getMessageText();
		if(strlen($text) < $limit) {
			return $text;
		} else {
			return substr($text,0,$limit)."...";
		}
	}

	/**
	 * Sets the text
	 * @param string $messageText
	 * @return void
	 */
	public function setMessageText($messageText) {
		$this->messageText = $messageText;
	}
}