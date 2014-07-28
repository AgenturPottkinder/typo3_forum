<?php
namespace Mittwald\MmForum\Domain\Factory;


/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2012 Martin Helmich <m.helmich@mittwald.de>                     *
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
 * Abstract factory class. Base class for all mm_forum factory classes.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @package    MmForum
 * @subpackage Domain_Factory
 * @version    $Id$
 *
 * @copyright  2012 Martin Helmich <m.helmich@mittwald.de>
 *             Mittwald CM Service GmbH & Co. KG
 *             http://www.mittwald.de
 * @license    GNU Public License, version 2
 *             http://opensource.org/licenses/gpl-license.php
 *
 */
abstract class AbstractFactory implements \TYPO3\CMS\Core\SingletonInterface {



	/*
	 * ATTRIBUTES
	 */



	/**
	 * A reference to the frontend user repository.
	 * @var \Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;


	/**
	 * An instance of the extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;


	/**
	 * An instance of the mm_forum authentication service.
	 * @var TYPO3\CMS\Extbase\Service\TypoScriptService
	 */
	protected $typoScriptService = NULL;


	/**
	 * Whole TypoScript mm_forum settings
	 * @var array
	 */
	protected $settings;


	/*
	  * DEPENDENCY INJECTORS
	  */



	/**
	 * Injects an instance of the frontend user repository.
	 * @param \Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository $frontendUserRepository
	 */
	public function injectFrontendUserRepository(\Mittwald\MmForum\Domain\Repository\User\FrontendUserRepository $frontendUserRepository) {
		$this->frontendUserRepository = $frontendUserRepository;
	}



	/**
	 * Injects an instance of the extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}


	/**
	 * Injects an instance of the \TYPO3\CMS\Extbase\Service\TypoScriptService.
	 * @param \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService
	 */
	public function injectTyposcriptService(\TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService) {
		$this->typoScriptService = $typoScriptService;
		$ts = $this->typoScriptService->convertTypoScriptArrayToPlainArray(\TYPO3\CMS\Extbase\Configuration\FrontendConfigurationManager::getTypoScriptSetup());
		$this->settings = $ts['plugin']['tx_mmforum']['settings'];
	}

	/*
	 * METHODS
	 */



	/**
	 *
	 * Determines the class name of the domain object this factory is used for.
	 * @return string The class name
	 *
	 */
	protected function getClassName() {
		$thisClass = get_class($this);
		$thisClass = preg_replace('/_Factory_/', '_Model_', $thisClass);
		$thisClass = preg_replace('/Factory$/', '', $thisClass);
		return $thisClass;
	}



	/**
	 *
	 * Creates an instance of the domain object class.
	 * @return Tx_Extbase_DomainObject_AbstractDomainObject
	 *                             An instance of the domain object.
	 *
	 */
	protected function getClassInstance() {
		return $this->objectManager->create($this->getClassName());
	}



	/**
	 *
	 * Gets the currently logged in user. Convenience wrapper for the findCurrent
	 * method of the frontend user repository.
	 *
	 * @return \Mittwald\MmForum\Domain\Model\User\FrontendUser
	 *                             The user that is currently logged in.
	 *
	 */
	protected function getCurrentUser() {
		return $this->frontendUserRepository->findCurrent();
	}



}
