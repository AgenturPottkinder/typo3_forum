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
 * A BBCode element. This class implements the abstract AbstractTextParserElement
 * class.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    $Id$
 * @license    GNU public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
class BBCode extends AbstractTextParserElement implements MarkItUpExportableInterface
{
    /**
     * The regular expression that will be used to match the BBCode.
     */
    protected ?string $regularExpression = null;

    /**
     * The replacement pattern or the regular expression.
     */
    protected ?string $regularExpressionReplacement = null;

    /**
     * The replacement pattern or the regular expression.
     */
    protected ?string $regularExpressionReplacementBlocked = null;

    /**
     * The BBCode wrap. This string specifies which BBCodes are to be inserted into
     * the post text by the BBCode editor.
     */
    protected string $bbcodeWrap;

    /**
     * Get the regular expression.
     */
    public function getRegularExpression(): ?string
    {
        return $this->regularExpression;
    }

    public function setRegularExpression(?string $regularExpression): self
    {
        $this->regularExpression = $regularExpression;

        return $this;
    }

    public function getRegularExpressionReplacement(): ?string
    {
        return $this->regularExpressionReplacement;
    }

    public function setRegularExpressionReplacement(?string $regularExpressionReplacement): self
    {
        $this->regularExpressionReplacement = $regularExpressionReplacement;

        return $this;
    }

    public function getRegularExpressionReplacementBlocked(): ?string
    {
        return $this->regularExpressionReplacementBlocked;
    }

    public function setRegularExpressionReplacementBlocked(?string $regularExpressionReplacementBlocked): self
    {
        $this->regularExpressionReplacementBlocked = $regularExpressionReplacementBlocked;

        return $this;
    }

    /**
     * Exports this BBCode object as a plain array, that can be used in
     * a MarkItUp configuration object.
     */
    public function exportForMarkItUp(): array
    {
        return [
            'name' => $this->getName(),
            'className' => $this->getEditorIconClass(),
            'openWith' => $this->getLeftBBCode(),
            'closeWith' => $this->getRightBBCode()
        ];
    }

    /**
     * Return the left (opening) BBCode tag.
     */
    public function getLeftBBCode(): string
    {
        return array_shift(explode('|', $this->bbcodeWrap));
    }

    /**
     * Return the right (closing) BBCode tag.
     */
    public function getRightBBCode(): string
    {
        return array_pop(explode('|', $this->bbcodeWrap));
    }

    /**
     * @param string $bbcodeWrap
     */
    public function setBbcodeWrap(string $bbcodeWrap): self
    {
        $this->bbcodeWrap = $bbcodeWrap;

        return $this;
    }
}
