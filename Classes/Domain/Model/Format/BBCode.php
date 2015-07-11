<?php
namespace Mittwald\Typo3Forum\Domain\Model\Format;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2015 Mittwald CM Service GmbH & Co KG                           *
 *           All rights reserved                                        *
 *                                                                      *
 *  This script is part of the TYPO3 project. The TYPO3 project is      *
 *  free software; you can redistribute it and/or modify                *
 *  it under the terms of the GNU General public License as published   *
 *  by the Free Software Foundation; either version 2 of the License,   *
 *  or (at your option) any later version.                              *
 *                                                                      *
 *  The GNU General public License can be found at                      *
 *  http://www.gnu.org/copyleft/gpl.html.                               *
 *                                                                      *
 *  This script is distributed in the hope that it will be useful,      *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of      *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       *
 *  GNU General public License for more details.                        *
 *                                                                      *
 *  This copyright notice MUST APPEAR in all copies of the script!      *
 *                                                                      */

use Mittwald\Typo3Forum\TextParser\Panel\MarkItUpExportableInterface;

/**
 *
 * A bb code element. This class implements the abstract AbstractTextParserElement
 * class.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
 * @subpackage Domain_Model_Format
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
class BBCode extends AbstractTextParserElement implements MarkItUpExportableInterface {

	/**
	 * The regular expression that will be used to match the bb code.
	 * @var string
	 */
	protected $regularExpression;

	/**
	 * The replacement pattern or the regular expression.
	 * @var string
	 */
	protected $regularExpressionReplacement;

	/**
	 * The bb code wrap. This string specifies which bb codes are to be inserted into
	 * the post text by the bb code editor.
	 * @var string
	 */
	protected $bbcodeWrap;

	/**
	 * Constructor.
	 *
	 * @param string $bbcodeWrap
	 * @param string $regularExpression
	 * @param string $regularExpressionReplacement
	 */
	public function __construct($bbcodeWrap = NULL, $regularExpression = NULL, $regularExpressionReplacement = NULL) {
		$this->bbcodeWrap = $bbcodeWrap;
		$this->regularExpression = $regularExpression;
		$this->regularExpressionReplacement = $regularExpressionReplacement;
	}

	/**
	 * Get the regular expression.
	 * @return string The regular expression
	 */
	public function getRegularExpression() {
		return $this->regularExpression;
	}

	/**
	 * @param string $regularExpression
	 */
	public function setRegularExpression($regularExpression) {
		$this->regularExpression = $regularExpression;
	}

	/**
	 * Gets the replacement pattern for the regular expression.
	 * @return string The replacement pattern for the regular expression.
	 */
	public function getRegularExpressionReplacement() {
		return $this->regularExpressionReplacement;
	}

	/**
	 * @param string $regularExpressionReplacement
	 */
	public function setRegularExpressionReplacement($regularExpressionReplacement) {
		$this->regularExpressionReplacement = $regularExpressionReplacement;
	}

	/**
	 * Exports this bb code object as a plain array, that can be used in
	 * a MarkItUp configuration object.
	 * @return array A plain array describing this bb code
	 */
	public function exportForMarkItUp() {
		return ['name' => $this->getName(),
			'className' => $this->getIconClass(),
			'openWith' => $this->getLeftBBCode(),
			'closeWith' => $this->getRightBBCode()];
	}

	/**
	 * Return the left (opening) bb code tag.
	 * @return string The left bb code tag.
	 */
	public function getLeftBBCode() {
		return array_shift(explode('|', $this->bbcodeWrap));
	}

	/**
	 * Return the right (closing) bb code tag.
	 * @return string The right bb code tag.
	 */
	public function getRightBBCode() {
		return array_pop(explode('|', $this->bbcodeWrap));
	}

	/**
	 * @param string $bbcodeWrap
	 */
	public function setBbcodeWrap($bbcodeWrap) {
		$this->bbcodeWrap = $bbcodeWrap;
	}
}
