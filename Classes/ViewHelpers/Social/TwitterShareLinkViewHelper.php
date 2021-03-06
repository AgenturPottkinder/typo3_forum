<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Social;
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class TwitterShareLinkViewHelper extends AbstractTagBasedViewHelper  {

	/**
	 * @var	string
	 */
	protected $tagName = 'a';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
	}

	/**
	 * Render a share button
	 *
	 * @param string $title Title for share
	 * @param string $text Title for share
	 * @param string $shareUrl Title for share
	 * @return string
	 */
	public function render($title = NULL,  $text= NULL, $shareUrl = NULL) {

		// check defaults
		if (empty($this->arguments['name'])) {
			$this->tag->addAttribute('name', 'fb_share');
		}

		if (empty($this->arguments['type'])) {
			$this->tag->addAttribute('type', 'link');
		}

		if (empty($this->arguments['target'])) {
			$this->tag->addAttribute('target', '_blank');
		}

		$url = 'https://twitter.com/intent/tweet';

		$url .= '?original_referer='.urldecode(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
		$url .= '&url=';
		if ($shareUrl) {
			$url .= urldecode($shareUrl);
		}else{
			$url .= urldecode(\TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
		}

		if ($title) {
			$url .= '&p[title]='.urldecode($title);
		}

		if ($text) {
			$url .= '&text='.urldecode($text);
		}

		$this->tag->addAttribute('href', $url);
		$this->tag->setContent($this->renderChildren());
		return $this->tag->render();
	}
}