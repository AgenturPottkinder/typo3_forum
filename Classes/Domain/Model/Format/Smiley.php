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

use Mittwald\Typo3Forum\TextParser\Panel\MarkItUpExportableInterface;

/**
 * A smiley. This class implements the abstract AbstractTextParserElement class.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    $Id$
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 */
class Smiley extends AbstractTextParserElement implements MarkItUpExportableInterface
{
    /**
     * The smiley shortcut, e.g. ":)" or ":/"
     */
    protected string $smileyShortcut  = '#undefined#';

    /**
     * Exports this smiley object as a plain array, that can be used in
     * a MarkItUp configuration object.
     */
    public function exportForMarkItUp(): array
    {
        return [
            'name' => $this->getName(),
            'className' => $this->getEditorIconClass(),
            'replaceWith' => $this->getSmileyShortcut()
        ];
    }

    /**
     * Gets the smiley shortcut.
     */
    public function getSmileyShortcut(): string
    {
        return $this->smileyShortcut;
    }
}
