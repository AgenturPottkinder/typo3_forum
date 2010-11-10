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
	 * Repository class for additional userfields. Unlike other repositories,
	 * this one creates part of its data not from the database, but load it
	 * from the extension's typoscript configuration instead.
	 *
	 * This is done e.g. for the so-called core-userfields, that are to be
	 * displayed in the user profile view independently of whether they are
	 * configured in the database.
	 *
	 * @author     Martin Helmich <m.helmich@mittwald.de>
	 * @package    MmForum
	 * @subpackage Domain_Repository_User
	 * @version    $Id$
	 *
	 * @copyright  2010 Martin Helmich <m.helmich@mittwald.de>
	 *             Mittwald CM Service GmbH & Co. KG
	 *             http://www.mittwald.de
	 * @license    GNU Public License, version 2
	 *             http://opensource.org/licenses/gpl-license.php
	 *
	 */

Class Tx_MmForum_Domain_Repository_User_UserfieldRepository
	Extends Tx_MmForum_Domain_Repository_AbstractRepository {





		/*
		 * ATTRIBUTES
		 */





		/**
		 * A list of core userfields.
		 * @var Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield
		 */
	Private $coreUserfields = NULL;





		/*
		 * CONSTRUCTOR
		 */





		/**
		 *
		 * Creates a new instance of the userfield repository.
		 *
		 */

	Public Function __construct() {
		parent::__construct();
		$this->objectType = 'Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield';
	}





		/*
		 * REPOSITORY METHODS
		 */





		/**
		 *
		 * Finds all core userfields. These are stored in the typoscript setting
		 * plugin.tx_mmforum.settings.userfields.core_fields.
		 *
		 * @return Array<Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield>
		 *                             The core userfields that are generated from the
		 *                             typoscript configuration.
		 *
		 */

	Protected Function findCoreUserfields() {

		If($this->coreUserfields === NULL) {
			$conf = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
			$this->coreUserfields = Array();

			ForEach($conf['settings']['userfields']['core_fields'] As $coreFieldKey => $coreFieldValues) {
				$className = $coreFieldValues['class'];
				If(!class_exists($className))
					Throw New Tx_Extbase_Object_UnknownClass ("The class $className does not exist!", 1287756385);
				$object = New $className;
				If(!$object InstanceOf Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield)
					Throw New Tx_Extbase_Object_InvalidClass ("The class $className is not a subclass of Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield", 1287756386);
				Else ForEach($coreFieldValues['properties'] As $propertyName => $propertyValue) {
					If($object->_hasProperty($propertyName)) $object->_setProperty($propertyName, $propertyValue);
				}
				$this->coreUserfields[] = $object;
			}
		} Return $this->coreUserfields;
	}



		/**
		 *
		 * Finds all userfields. This method load all userfields from the database and
		 * merges the result with the core userfields that are loaded from the typoscript
		 * setup.
		 *
		 * @return Array<Tx_MmForum_Domain_Model_User_Userfield_AbstractUserfield>
		 *                             All userfields, both from the database and the
		 *                             core typoscript setup.
		 *
		 */

	Public Function findAll() {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		return array_merge($this->findCoreUserfields(), $query->execute());
	}

}
?>