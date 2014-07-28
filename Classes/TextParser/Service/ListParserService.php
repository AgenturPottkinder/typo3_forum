<?php
namespace Mittwald\MmForum\TextParser\Service;


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
 * Text parser class for parsing complex lists.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage TextParser_Service
 * @version    $Id$
 *
 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */

class ListParserService extends AbstractTextParserService {



	/*
	 * CONSTANTS
	 */



	/**
	 * The regular expression for matching lists.
	 * @var string
	 */
	const PREG_MATCH_LIST = ',\[list\]\s*(.*?)\s*\[\/list\],is';



	/*
	 * METHODS
	 */



	/**
	 * Parses lists inside a text.
	 *
	 * @param  string $text The text
	 * @return string       The parsed text.
	 */

	public function getParsedText($text) {
		$callback = function($matches) {
			$items = array_filter(\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode('[*]', $matches[1]));
			return '<ul><li>' . implode('</li><li>', $items) . '</li></ul>';
		};
		return preg_replace_callback(self::PREG_MATCH_LIST, $callback, $text);
	}

}

?>