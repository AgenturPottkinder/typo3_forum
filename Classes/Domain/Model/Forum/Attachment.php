<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

class Attachment extends AbstractEntity {

	/**
	 * The attachment file name.
	 * @var \Mittwald\Typo3Forum\Domain\Model\Forum\Post
	 * @lazy
	 */
	protected $post;

	/**
	 * The attachment file name.
	 * @var string
	 */
	protected $filename;

	/**
	 * The attachment file name on file system.
	 * @var string
	 */
	protected $realFilename;

	/**
	 * The MIME type of the attachment.
	 * @var string
	 */
	protected $mimeType;

	/**
	 * A download counter.
	 * @var integer
	 */
	protected $downloadCount;

	/**
	 * @var \Mittwald\Typo3Forum\Configuration\ConfigurationBuilder
	 * @inject
	 */
	protected $configurationBuilder;

	/**
	 * Whole TypoScript typo3_forum settings
	 * @var array
	 */
	protected $settings;

	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 */
	public function initializeObject() {
		$this->settings = $this->configurationBuilder->getSettings();
	}

	/**
	 * Gets the attachment's filename on file system.
	 * @return Post
	 */
	public function getPost() {
		return $this->post;
	}

	/**
	 * Sets the filename on file system.
	 *
	 * @param Post $post
	 *
	 * @return void
	 */
	public function setPost($post) {
		$this->post = $post;
	}

	/**
	 * Gets the attachment's filename.
	 * @return string The attachment's filename.
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Sets the filename.
	 *
	 * @param string $filename The filename
	 *
	 * @return void
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * Gets the allowed mime types.
	 * @return array The allowed mime types.
	 */
	public function getAllowedMimeTypes() {
		$mime_types = explode(',', $this->settings['attachment']['allowedMimeTypes']);
		if (empty($mime_types)) {
			$res = ['text/plain'];
		} else {
			foreach ($mime_types as $mime_type) {
				$res[] = trim($mime_type);
			}
		}

		return $res;
	}

	/**
	 * Gets the allowed max size of a attachment.
	 * @return int The allowed max size of a attachment.
	 */
	public function getAllowedMaxSize() {
		if ($this->settings['attachment']['allowedSizeInByte'] == false) {
			return 4096;
		} else {
			return (int)$this->settings['attachment']['allowedSizeInByte'];
		}
	}

	/**
	 * Gets the filesize.
	 * @return integer The filesize.
	 */
	public function getFilesize() {
		return filesize($this->getAbsoluteFilename());
	}

	/**
	 * Gets the absolute filename of this attachment.
	 * @return string The absolute filename of this attachment.
	 */
	public function getAbsoluteFilename() {
		$tca = $GLOBALS['TCA']['tx_typo3forum_domain_model_forum_attachment'];
		$uploadPath = $tca['columns']['real_filename']['config']['uploadfolder'];
		return $uploadPath . $this->getRealFilename();
	}

	/**
	 * Gets the attachment's filename on file system.
	 * @return string The attachment's filename on file system.
	 */
	public function getRealFilename() {
		return $this->realFilename;
	}

	/**
	 * Sets the filename on file system.
	 *
	 * @param string $realFilename The filename on file system
	 *
	 * @return void
	 */
	public function setRealFilename($realFilename) {
		$this->realFilename = $realFilename;
	}

	/**
	 * Gets the MIME type.
	 * @return string The MIME type.
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * Sets the MIME type.
	 *
	 * @param string $mimeType The MIME type.
	 *
	 * @return void
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * Gets the download count.
	 * @return integer The download count.
	 */
	public function getDownloadCount() {
		return $this->downloadCount;
	}

	/**
	 * Increases the download counter by 1.
	 * @return void
	 */
	public function increaseDownloadCount() {
		$this->downloadCount++;
	}

    /**
     * Gets the whole TCA config of tx_typo3forum_domain_model_forum_attachment
     * @return array The whole TCA config of tx_typo3forum_domain_model_forum_attachment
     */
    public function getTCAConfig() {
        return $GLOBALS['TCA']['tx_typo3forum_domain_model_forum_attachment'];
    }

}
