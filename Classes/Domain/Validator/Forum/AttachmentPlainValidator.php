<?php

/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                   *
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
 * A validator class for author names. This class validates a username ONLY if
 * no user is currently logged in.
 *
 * @author     Ruven Fehling <r.fehling@mittwald.de>
 * @package    MmForum
 * @subpackage Domain\Validator\Forum
 * @version    $Id$
 *
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class Tx_MmForum_Domain_Validator_Forum_AttachmentPlainValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param $value
	 * @return bool
	 */
	public function isValid($value) {
		$result = TRUE;
		$attachmentObj = new Tx_MmForum_Domain_Model_Forum_Attachment();
		foreach($value as $attachment){
			if(empty($attachment['name'])) continue;
			if(array_search($attachment['type'], $attachmentObj->getAllowedMimeTypes()) == false){
				$this->addError('The submitted mime-type is not allowed!.', 1371041777);
				$result = FALSE;
			}
			if($attachment->$attachment['size'] > $attachmentObj->getAllowedMaxSize){
				$this->addError('The submitted file is to big!.', 1371041888);
				$result = FALSE;
			}
		}
		return $result;
	}
}