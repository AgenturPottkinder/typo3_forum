<?php
namespace Mittwald\MmForum\Service\Mailing;


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
 * Service class for notifications. This service notifies subscribers of
 * forums and topic about new posts within the subscribed objects.
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
abstract class AbstractMailingService extends \Mittwald\MmForum\Service\AbstractService
	implements MailingServiceInterface {


	/**
	 * An instance of the mm_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;



	/**
	 * Whole TypoScript mm_forum settings
	 * @var array
	 */
	protected $settings;



	/*
	 * CONSTANTS
	 */



	/**
	 * HTML mail format.
	 */
	const MAILING_FORMAT_HTML = 'html';



	/**
	 * Plaintext mail format.
	 */
	const MAILING_FORMAT_PLAIN = 'txt';



	/*
	  * ATTRIBUTES
	  */



	/**
	 * The format in which this service sends mails. Usually, this would be either 'html' or 'txt'.
	 * @var string
	 */
	protected $format = AbstractMailingService::MAILING_FORMAT_HTML;



	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_mmforum']['settings'];
	}


	/*
	  * SERVICE METHODS
	  */



	/**
	 * Gets the preferred format of this mailing service.
	 * @return string The preferred format of this mailing service.
	 */
	public function getFormat() {
		return $this->format;
	}



	/*
	 * HELPER METHODS
	 */



	/**
	 * Gets the default sender name. Can be configured in the typoscript setup.
	 * @return string The default sender name.
	 */
	protected function getDefaultSenderName() {
		return trim($this->settings['mailing']['sender']['name']);
	}



	/**
	 * Gets the default sender address. Can be configured in the typoscript setup.
	 * @return string The default sender address.
	 */
	protected function getDefaultSenderAddress() {
		return trim($this->settings['mailing']['sender']['address']);
	}



	/**
	 * Gets the default sender. This is composed of the default sender name and the
	 * default sender address.
	 *
	 * @return string The default sender.
	 */
	protected function getDefaultSender() {
		return $this->getDefaultSenderName() . ' <' . $this->getDefaultSenderAddress() . '>';
	}



	/**
	 * Gets the preferred character set for sent mails. This usually is TYPO3's
	 * renderCharset.
	 *
	 * @return string The preferred charset.
	 */
	protected function getCharset() {
		return $GLOBALS['TSFE']->renderCharset;
	}



}
