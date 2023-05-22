<?php
namespace Mittwald\Typo3Forum\TextParser;

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Service\AbstractService;
use Mittwald\Typo3Forum\Utility\TypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;
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

use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;

/**
 * Service class for parsing text values for display. This service handles
 * for example the rendering of bb codes, smileys, etc.
 */
class TextParserService extends AbstractService
{
    protected array $settings = [];
    /**
     * An array of the parsing services that are to be used to render text input.
     * @var \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService[]
     */
    protected array $parsingServices = [];

    /**
     * An instance of the typo3_forum typoscript reader. Is used to read the
     * text parser's typoscript configuration.
     */
    protected TypoScript $typoscriptReader;
    protected ControllerContext $controllerContext;

    public function __construct(
        TypoScript $typoScriptReader
    ) {
        $this->typoscriptReader = $typoScriptReader;
    }

    /**
     * Sets the current Extbase controller context.
     */
    public function setControllerContext(ControllerContext $controllerContext): self
    {
        $this->controllerContext = $controllerContext;

        return $this;
    }

    /**
     * Loads the text parser configuration from a certain configuration path.
     *
     * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
     */
    public function loadConfiguration(string $configurationPath = 'plugin.tx_typo3forum.settings.textParsing'): void
    {
        if (count($this->settings) > 0) {
            return;
        }

        $this->settings = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
        foreach ($this->settings['enabledServices.'] as $key => $className) {
            if (substr($key, -1, 1) === '.') {
                continue;
            }

            /** @var \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService $newService */
            $newService = GeneralUtility::makeInstance($className);
            if ($newService instanceof \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService) {
                $newService->setSettings((array)($this->settings['enabledServices.'][$key . '.'] ?? []));
                $newService->setControllerContext($this->controllerContext);
                $this->parsingServices[] = $newService;
            } else {
                throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception('Invalid class; expected an instance of \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService!', 1315916625);
            }
        }
    }

    /**
     * Parses a certain input text.
     * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
     */
    public function parseText(string $text, ?Post $post = null): string
    {
        if ($this->settings === null) {
            throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception('The textparser is not configured!', 1284730639);
        }

        foreach ($this->parsingServices as &$parsingService) {
            /** @var $parsingService \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService */
            $text = $parsingService->getParsedText($text, $post);
        }
        return $text;
    }
}
