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
 * A syntax highlighting element. This class implements the abstract
 * AbstractTextParserElement class.
 */
class SyntaxHighlighting extends BBCode
{

    /**
     * The language key.
     * @var string
     */
    protected $language;

    /**
     * The default smiley directory.
     * @var string
     */
    protected $defaultIconDir = 'Syntax/';

    /**
     * Return the left (opening) bb code tag.
     * @return string The left bb code tag.
     */
    public function getLeftBBCode()
    {
        return "[code language={$this->language}]\n";
    }

    /**
     * Return the right (closing) bb code tag.
     * @return string The right bb code tag.
     */
    public function getRightBBCode()
    {
        return '[/code]';
    }
}
