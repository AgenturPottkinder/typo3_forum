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

use Mittwald\Typo3Forum\Service\AbstractService;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;

/**
 * Abstract base class for all kinds of text parsing services.
 */
abstract class AbstractTextParserService extends AbstractService
{

    /**
     * The configuration of this service.
     * @var array
     */
    protected $settings;

    /**
     * The current controller context.
     * @var ControllerContext
     */
    protected $controllerContext;

    /**
     * Injects this service's configuration.
     * @param array $settings The configuration for this service.
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Sets the extbase controller context.
     * @param ControllerContext $controllerContext
     */
    public function setControllerContext(ControllerContext $controllerContext)
    {
        $this->controllerContext = $controllerContext;
    }

    /**
     * Renders the parsed text.
     *
     * @param string $text The text to be parsed.
     * @return string The parsed text.
     */
    abstract public function getParsedText($text);
}
