<?php
namespace Mittwald\Typo3Forum\TextParser;

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

use Mittwald\Typo3Forum\Service\AbstractService;
use TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext;

/**
 * Service class for parsing text values for display. This service handles
 * for example the rendering of bb codes, smileys, etc.
 */
class TextParserService extends AbstractService {

	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * An instance of the typo3_forum typoscript reader. Is used to read the
	 * text parser's typoscript configuration.
	 *
	 * @var \Mittwald\Typo3Forum\Utility\TypoScript
	 * @inject
	 */
	protected $typoscriptReader;

	/**
	 * An array of the parsing services that are to be used to render text input.
	 * @var array<\Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService>
	 */
	protected $parsingServices;

	/**
	 * The viewHelper variable container. This needs to be set when this service is
	 * called from a viewHelper context.
	 *
	 * @var \TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperVariableContainer
	 * @inject
	 */
	protected $viewHelperVariableContainer;

	/**
	 * The current controller context.
	 * @var ControllerContext
	 */
	protected $controllerContext;

	/**
	 * Sets the current Extbase controller context.
	 * @param ControllerContext $controllerContext
	 */
	public function setControllerContext(ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}

	/**
	 * Loads the text parser configuration from a certain configuration path.
	 *
	 * @param string $configurationPath The typoscript configuration path.
	 * @return void
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
	 */
	public function loadConfiguration($configurationPath = 'plugin.tx_typo3forum.settings.textParsing') {
		if ($this->settings !== NULL) {
			return;
		}

		$this->settings = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
		foreach ($this->settings['enabledServices.'] as $key => $className) {
			if (substr($key, -1, 1) === '.') {
				continue;
			}

			/** @var $newService \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService */
			$newService = $this->objectManager->get($className);
			if ($newService instanceof \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService) {
				$newService->setSettings((array)$this->settings['enabledServices.'][$key . '.']);
				$newService->setControllerContext($this->controllerContext);
				$this->parsingServices[] = $newService;
			} else {
				throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception('Invalid class; expected an instance of \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService!', 1315916625);
			}
		}
	}



	/**
	 * Parses a certain input text.
	 *
	 * @param string $text The text that is to be parsed.
	 * @return string       The parsed text
	 * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
	 */
	public function parseText($text) {
		if ($this->settings === NULL) {
			throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
			("The textparser is not configured!", 1284730639);
		}

		foreach ($this->parsingServices as &$parsingService) {
			/** @var $parsingService \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService */
			$text = $parsingService->getParsedText($text);
		}
		return $text;
	}

}
