<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

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

use Mittwald\Typo3Forum\Utility\File;

class QuoteParserService extends AbstractTextParserService {

	/**
	 * The post repository.
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\PostRepository
	 * @inject
	 */
	protected $postRepository;

	/**
	 * A standalone fluid view, used to render each individual quote.
	 * @var \TYPO3\CMS\Fluid\View\StandaloneView
	 * @inject
	 */
	protected $view;

	/**
	 * Renders the parsed text.
	 *
	 * @param string $text The text to be parsed.
	 * @return string The parsed text.
	 */
	public function getParsedText($text) {
		do {
			$text = preg_replace_callback('/\[quote](.*?)\[\/quote\]\w*/is', [$this, 'replaceSingleCallback'], $text, -1, $c);
		} while ($c > 0);
		do {
			$text = preg_replace_callback('/\[quote=([0-9]+)\](.*?)\[\/quote\]\w*/is', [$this, 'replaceCallback'], $text, -1, $c);
		} while ($c > 0);
		return $text;
	}

	/**
	 * Callback function for rendering quotes.
	 *
	 * @param string $matches PCRE matches.
	 * @return string The quote content.
	 */
	protected function replaceSingleCallback($matches) {
		$this->view->setControllerContext($this->controllerContext);
		$this->view->setTemplatePathAndFilename(File::replaceSiteRelPath($this->settings['template']));
		$this->view->assign('quote', trim($matches[1]));
		$this->view->assign('post', null);
		return $this->view->render();
	}

	/**
	 * Callback function for rendering quotes.
	 *
	 * @param string $matches PCRE matches.
	 * @return string  The quote content.
	 */
	protected function replaceCallback($matches) {
		$this->view->setControllerContext($this->controllerContext);
		$this->view->setTemplatePathAndFilename(File::replaceSiteRelPath($this->settings['template']));

		$tmp = $this->postRepository->findByUid((int)$matches[1]);
		if (!empty($tmp)) {
			$this->view->assign('post', $tmp);
		}

		$this->view->assign('quote', trim($matches[2]));
		return $this->view->render();
	}

}
