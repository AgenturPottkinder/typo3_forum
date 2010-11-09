<?php

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
	 * Abstract factory class. Base class for all mm_forum factory classes.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Factory
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Abstract Class Tx_MmForum_Domain_Factory_AbstractFactory
	Implements t3lib_Singleton {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A reference to the frontend user repository.
		 * @var Tx_MmForum_Domain_Repository_User_FrontendUserRepository
		 */

	Protected $frontendUserRepository = NULL;





		/*
		 * METHODS
		 */





		/**
		 *
		 * Determines the class name of the domain object this factory is used for.
		 * @return string The class name
		 *
		 */

	Protected Function getClassName() {
		$thisClass = get_class($this);
		$thisClass = preg_replace('/_Factory_/', '_Model_', $thisClass);
		$thisClass = preg_replace('/Factory$/', '', $thisClass);
		Return $thisClass;
	}



		/**
		 *
		 * Creates an instance of the domain object class.
		 * @return Tx_Extbase_DomainObject_AbstractDomainObject
		 *                             An instance of the domain object.
		 *
		 */
	
	Protected Function getClassInstance() {
		Return t3lib_div::makeInstance($this->getClassName());
	}



		/**
		 *
		 * Gets the currently logged in user. Convenience wrapper for the findCurrent
		 * method of the frontend user repository.
		 *
		 * @return Tx_MmForum_Domain_Model_User_FrontendUser
		 *                             The user that is currently logged in.
		 *
		 */
	
	Protected Function getCurrentUser() {
		If($this->frontendUserRepository === NULL)
			$this->frontendUserRepository =&
				t3lib_div::makeInstance('Tx_MmForum_Domain_Repository_User_FrontendUserRepository');
		Return $this->frontendUserRepository->findCurrent();
	}

}

?>