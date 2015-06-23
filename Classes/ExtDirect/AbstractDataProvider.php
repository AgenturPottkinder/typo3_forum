<?php
namespace Mittwald\Typo3Forum\ExtDirect;
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2011 domainfactory GmbH (Stefan Galinski <sgalinski@df.eu>      *
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
 * Abstract ExtDirect Data Provider
 *
 *
 * @author  Stefan Galinski <sgalinski@df.eu>
 * @package df_tools
 */
abstract class AbstractDataProvider {



	/**
	 * BootStrap Instance
	 *
	 * @var \Mittwald\Typo3Forum\Service\ExtBaseConnectorService
	 */
	protected $extBaseConnector = NULL;



	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;



	/**
	 * Constructor
	 */
	public function __construct() {
		$key                    = 'web_Typo3ForumTxTypo3forumM1';
		$this->extBaseConnector = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Mittwald\\Typo3Forum\\Service\\ExtBaseConnectorService');
		$this->extBaseConnector->setExtensionKey('Typo3Forum');
		$this->extBaseConnector->setModuleOrPluginKey($key);
		$this->extBaseConnector->initialize(array('extensionName'               => 'Typo3Forum',
		                                         'pluginName'                   => 'web_Typo3ForumTxTypo3forumM1',
		                                         'switchableControllerActions'  => array('Backend' => array('forumIndex')),));

		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}



	/**
	 * Checks the access rights for using Ext.Direct calls
	 *
	 * @throws \Exception if the user has no rights to proceed
	 * @return bool
	 */
	public function hasAccess() {
		return TRUE;
	}



	/**
	 * Handles incoming multiple update requests
	 *
	 * Note: ExtJS transfers a single data object or multiple
	 * ones based on the amount of updated records.
	 *
	 * Note2: An exception causes the whole process to stop. You don't
	 * know on the client side which records are written successfully. Always
	 * update a record once by once!
	 *
	 * @param \stdClass $updatedRecords
	 *
	 * @return array
	 */
	public function update($updatedRecords) {
		$this->hasAccess();

		/** @noinspection PhpUndefinedFieldInspection */
		$updatedRecords = $updatedRecords->records;
		if (!is_array($updatedRecords)) {
			$updatedRecords = array($updatedRecords);
		}

		try {
			$data = array('records' => array(),);

			foreach ($updatedRecords as $updatedRecord) {
				$record = $this->updateRecord((array)$updatedRecord);
				if (is_array($record['records'])) {
					$data['records'] = array_merge_recursive($data['records'], $record['records']);
				}
			}
		} catch (\Exception $exception) {
			$data = array('success' => FALSE,
			              'message' => $exception->getMessage(),
			              'records' => array(),);
		}

		return $data;
	}



	/**
	 * Handles the incoming create record calls
	 *
	 * @param \stdClass $newRecord
	 *
	 * @return array
	 */
	public function create($newRecord) {
		$this->hasAccess();

		return $this->createRecord((array)$newRecord->records);
	}



	/**
	 * Destroys records based on their identifiers
	 *
	 * Note: ExtJS transfers a single identifier object or multiple
	 * ones based on the amount of deleted records.
	 *
	 * @param \stdClass $identifiers
	 *
	 * @return array
	 */
	public function destroy($identifiers) {
		$this->hasAccess();

		$identifiers = $identifiers->records;
		if (!is_array($identifiers)) {
			$identifiers = array($identifiers);
		}

		try {
			$this->destroyRecords($identifiers);
			$data = array('success' => TRUE,
			              'records' => array());
		} catch (\Exception $exception) {
			$data = array('success' => FALSE,
			              'message' => $exception->getMessage(),
			              'records' => array(),);
		}

		return $data;
	}



	/**
	 * Handles an incoming test request for a record
	 *
	 * @param int $identity
	 *
	 * @return array
	 */
	public function runTest($identity) {
		try {
			$data   = $this->runTestForRecord($identity);
			$result = array('success' => TRUE,
			                'data'    => $data,);
		} catch (\Exception $exception) {
			$result = array('success' => FALSE,
			                'data'    => array('testResult'  => \Tx_DfTools_Service_UrlChecker_AbstractService::SEVERITY_EXCEPTION,
			                                   'testMessage' => htmlspecialchars($exception->getMessage()),),);
		}

		return $result;
	}



	/**
	 * @abstract
	 *
	 * @param int $identity
	 *
	 * @return array
	 */
//	abstract protected function runTestForRecord($identity);
//	/**
//	 * @abstract
//	 * @param array $updatedRecord
//	 * @return void
//	 */
//	abstract protected function updateRecord(array $updatedRecord);
//
//
//
//	/**
//	 * @abstract
//	 * @param array $newRecord
//	 * @return void
//	 */
//	abstract protected function createRecord(array $newRecord);
//
//
//
//	/**
//	 * @abstract
//	 * @param array $identifiers
//	 * @return void
//	 */
//	abstract protected function destroyRecords(array $identifiers);



}
