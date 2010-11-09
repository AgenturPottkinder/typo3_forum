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
	 * A file attachment. These attachments can be attached to any forum post.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Model_Format
	 * @version    $Id$
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Model_Forum_Attachment Extends Tx_Extbase_DomainObject_AbstractEntity {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * The attachment file name
		 * @var string
		 */
	Protected $filename;

		/**
		 * The MIME type of the attachment
		 * @var string
		 */
	Protected $mimeType;

		/**
		 * A download counter
		 * @var integer
		 */
	Protected $downloadCount;





		/*
		 * GETTERS
		 */





		/**
		 *
		 * Gets the attachment's filename
		 * @return string The attachment's filename
		 *
		 */

	Public Function getFilename() {
		Return $this->filename;
	}



		/**
		 *
		 * Gets the absolute filename of this attachment
		 * @return string The absolute filename of this attachment
		 *
		 */

	Public Function getAbsoluteFilename() {
		global $TCA;
		$GLOBALS['TSFE']->includeTCA();
		t3lib_div::loadTCA('tx_mmforum_domain_model_forum_attachment');

		$uploadPath = $TCA['tx_mmforum_domain_model_forum_attachment']['columns']['filename']['config']['uploadfolder'];
		Return $uploadPath . $this->getFilename();
	}



		/**
		 *
		 * Gets the filesize.
		 * @return integer The filesize
		 *
		 */

	Public Function getFilesize() {
		Return filesize($this->getAbsoluteFilename());
	}



		/**
		 *
		 * Gets the MIME type.
		 * @return string The MIME type
		 *
		 */

	Public Function getMimeType() {
		Return $this->mimeType;
	}



		/**
		 *
		 * Gets the download count.
		 * @return integer The download count
		 *
		 */

	Public Function getDownloadCount() {
		Return $this->downloadCount;
	}





		/*
		 * SETTERS
		 */





		/**
		 *
		 * Sets the filename.
		 * @param string $filename The filename
		 * @return void
		 *
		 */

	Public Function setFilename($filename) {
		$this->filename = $filename;
	}

		/**
		 *
		 * Sets the MIME type.
		 * @param string $mimeType The MIME type
		 * @return void
		 *
		 */

	Public Function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}



		/**
		 *
		 * Increases the download counter by 1.
		 * @return void
		 *
		 */
	
	Public Function increaseDownloadCount() {
		$this->downloadCount ++;
	}

}

?>