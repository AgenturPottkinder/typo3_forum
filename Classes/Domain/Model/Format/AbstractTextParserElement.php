<?php
namespace Mittwald\Typo3Forum\Domain\Model\Format;

/*                                                                    - *
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

use TYPO3\CMS\Extbase\DomainObject\AbstractValueObject;

/**
 * An abstract text parser element. This may later be a bb code, a smiley or anything
 * you want.
 */
abstract class AbstractTextParserElement extends AbstractValueObject
{
    /**
     * The path to the element's icon (in the panel and the output in case of smileys).
     */
    protected string $imagePath = 'undefined';
    protected string $editorIconClass = 'tx-typo3forum-miu-undefined';

    /**
     * The name of this element. Can also be a locallang label.
     */
    protected string $name = '#undefined#';
    protected string $groups = '';

    /**
     * Gets the icon file.
     */
    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * Sets the icon path
     */
    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Gets the text parser element name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the element name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdsOfGroups(): array
    {
        return explode(',', $this->groups);
    }

    /**
     * Get the value of editorIconClass
     */
    public function getEditorIconClass(): string
    {
        return $this->editorIconClass;
    }

    /**
     * Set the value of editorIconClass
     */
    public function setEditorIconClass(string $editorIconClass): self
    {
        $this->editorIconClass = $editorIconClass;

        return $this;
    }
}
