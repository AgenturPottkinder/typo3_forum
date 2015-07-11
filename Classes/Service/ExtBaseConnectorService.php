<?php
namespace Mittwald\Typo3Forum\Service;

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

use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Service\ExtensionService;


/**
 * Utility class to simplify the execution of extbase actions from external sources (e.g. from Ext.Direct)
 */
class ExtBaseConnectorService extends Bootstrap {


	/**
	 * Extension Key
	 *
	 * @var string
	 */
	protected $extensionKey;


	/**
	 * Module Key
	 *
	 * @var string
	 */
	protected $moduleOrPluginKey;


	/**
	 * Parameters
	 *
	 * @var array
	 */
	protected $parameters;


	/**
	 * Setter for the extension key
	 *
	 * @param string $extensionKey
	 *
	 * @return void
	 */
	public function setExtensionKey($extensionKey) {
		$this->extensionKey = $extensionKey;
	}


	/**
	 * Setter for the module or plugin key
	 *
	 * @param string $moduleOrPluginKey
	 *
	 * @return void
	 */
	public function setModuleOrPluginKey($moduleOrPluginKey) {
		$this->moduleOrPluginKey = $moduleOrPluginKey;
	}


	/**
	 * Sets the parameters for the configured module/plugin
	 *
	 * @param array $parameters
	 *
	 * @return void
	 */
	public function setParameters(array $parameters) {
		$this->parameters = $parameters;
	}


	/**
	 * Runs the given ExtBase configuration and returns the result
	 *
	 * @param string $controller
	 * @param string $action
	 *
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	public function runControllerAction($controller, $action) {
		if ($controller === '' || $action === '') {
			throw new \InvalidArgumentException('Invalid Controller/Action Combination!');
		}

		$configuration = ['extensionName' => $this->extensionKey,
			'pluginName' => $this->moduleOrPluginKey,
			'switchableControllerActions' => [$controller => [$action]],];

		$this->initialize($configuration);

		/** @var $extensionService ExtensionService */
		$extensionService = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Service\\ExtensionService');
		$parameterNamespace = $extensionService->getPluginNamespace($this->extensionKey, $this->moduleOrPluginKey);

		if (is_array($this->parameters)) {
			$_POST[$parameterNamespace] = $this->parameters;
			$_POST[$parameterNamespace]['format'] = 'json';
		}

		$content = $this->handleWebRequest();

		return $content;
	}


}
