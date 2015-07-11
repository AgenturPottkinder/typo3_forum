<?php
namespace Mittwald\Typo3Forum\TextParser\Service;

	/*                                                                    - *
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

/**
 * Text parser class for parsing syntax highlighting.
 */
class SyntaxHighlightingParserService extends AbstractTextParserService {

	/**
	 * @var \Mittwald\Typo3Forum\TextParser\Service\AbstractGeshiService
	 * @inject
	 */
	protected $xtGeshi;

	/**
	 * Renders the parsed text.
	 *
	 * @param string $text The text to be parsed.
	 * @return string The parsed text.
	 */
	public function getParsedText($text) {
		return preg_replace_callback(
			',\[code language=([a-z0-9]+)\](.*?)\[\/code\],is',
			[$this, 'parseSourceCode'],
			$text
		);
	}

	/**
	 * Callback function that renders each source code block.
	 *
	 * @param array $matches PCRE matches.
	 * @return string The rendered source code block.
	 */
	protected function parseSourceCode($matches) {
		return $this->xtGeshi->getFormattedText(trim($matches[2]), trim($matches[1]));
	}

}
