<?php
namespace Mittwald\Typo3Forum\TextParser\Service;
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
 * Abstract base class for all kinds of text parsing services.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    Typo3Forum
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
abstract class AbstractTextParserService extends \Mittwald\Typo3Forum\Service\AbstractService {



	/**
	 * The configuration of this service.
	 * @var array
	 */
	protected $settings = NULL;



	/**
	 * The current controller context.
	 * @var Tx_Extbase_MVC_Controller_ControllerContext
	 */
	protected $controllerContext = NULL;



	/**
	 * Creates a new instance of this service.
	 */

	public function __construct() {
	}



	/**
	 * Injects this service's configuration.
	 * @param array $settings The configuration for this service.
	 */

	public function setSettings(array $settings) {
		$this->settings = $settings;
	}



	/**
	 * Sets the extbase controller context.
	 * @param \TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext
	 * @return void
	 */
	public function setControllerContext(\TYPO3\CMS\Extbase\Mvc\Controller\ControllerContext $controllerContext) {
		$this->controllerContext = $controllerContext;
	}



	/**
	 * Renders the parsed text.
	 *
	 * @param  string $text The text to be parsed.
	 * @return string       The parsed text.
	 */

	abstract function getParsedText($text);

}
