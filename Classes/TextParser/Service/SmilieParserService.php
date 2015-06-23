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

use Mittwald\Typo3Forum\Domain\Model\Format\Smilie;

class SmilieParserService extends AbstractTextParserService {

	/**
	 * @var \Mittwald\Typo3Forum\Domain\Repository\Format\SmilieRepository
	 * @inject
	 */
	protected $smilieRepository;

	/**
	 * All smilies.
	 * @var array<\Mittwald\Typo3Forum\Domain\Model\Format\Smilie>
	 */
	protected $smilies = NULL;

	/**
	 * Renders the parsed text.
	 *
	 * @param  string $text The text to be parsed.
	 * @return string The parsed text.
	 */
	public function getParsedText($text) {
		if ($this->smilies === NULL) {
			$this->smilies = $this->smilieRepository->findAll();
		}
		foreach ($this->smilies as $smilie) {
			$text = str_replace($smilie->getSmilieShortcut(), $this->getSmilieIcon($smilie), $text);
		}
		return $text;
	}



	/**
	 *
	 * Renders a smilie icon.
	 *
	 * @param  Smilie $smilie The smilie that is to be rendered.
	 *
	 * @return string The smilie as HTML code.
	 *
	 */

	protected function getSmilieIcon(Smilie $smilie) {
		return '<i class="' . $smilie->getIconClass() . '"></i>';
	}

}
