<?php
/*                                                                    - *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2013 Ruven Fehling <r.fehling@mittwald.de>                     *
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
 * Additional field provider for the notification generator task
 *
 * @author	Ruven Fehling <r.fehling@mittwald.de>
 * @package	TYPO3
 * @subpackage	mm_forum
 */
class tx_mmforum_scheduler_notification_additionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

	/**
	 * Lorem
	 *
	 * @param	array														$taskInfo: reference to the array containing the info used in the add/edit form
	 * @param	tx_scheduler_Task											$task: when editing, reference to the current task object. Null when adding.
	 * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController	$schedulerModule: reference to the calling object (Scheduler's BE module)
	 * @return	array														Array containg all the information pertaining to the additional fields
	 *																		The array is multidimensional, keyed to the task class name and each field's id
	 *																		For each field it provides an associative sub-array with the following:
	 */
	public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$additionalFields = array();

		if ($schedulerModule->CMD == 'add') {
			$taskInfo['forumPids'] = 1337;
			$taskInfo['userPids'] = 1337;
		}

		if ($schedulerModule->CMD == 'edit') {
			$taskInfo['forumPids'] = $task->getForumPids();
			$taskInfo['userPids'] = $task->getUserPids();
		}

		$additionalFields['forumPids'] = array(
			'code'     => '<input type="text" name="tx_scheduler[forumPids]" value="' . htmlspecialchars($taskInfo['forumPids']) . '" />',
			'label'    => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_notification_forumPid',
			'cshKey'   => '',
			'cshLabel' => ''
		);

		$additionalFields['userPids'] = array(
			'code'     => '<input type="text" name="tx_scheduler[userPids]" value="' . htmlspecialchars($taskInfo['userPids']) . '" />',
			'label'    => 'LLL:EXT:mm_forum/Resources/Private/Language/locallang.xml:tx_mmforum_scheduler_notification_userPid',
			'cshKey'   => '',
			'cshLabel' => ''
		);

		return $additionalFields;
	}

	/**
	 * Checks any additional data that is relevant to this task. If the task
	 * class is not relevant, the method is expected to return TRUE
	 *
	 * @param	array														$submittedData: reference to the array containing the data submitted by the user
	 * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController	$schedulerModule: reference to the calling object (Scheduler's BE module)
	 * @return	boolean														True if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$submittedData['forumPids'] = htmlspecialchars($submittedData['forumPids']);
		$submittedData['userPids'] = htmlspecialchars($submittedData['userPids']);

		return true;
	}

	/**
	 * Saves any additional input into the current task object if the task
	 * class matches.
	 *
	 * @param	array									$submittedData: array containing the data submitted by the user
	 * @param	\TYPO3\CMS\Scheduler\Task\AbstractTask	$task: reference to the current task object
	 */
	public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
		$task->setUserPids($submittedData['userPids']);
		$task->setForumPids($submittedData['forumPids']);
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_notification_additionalFieldProvider.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/mm_forum/Scheduler/class.tx_mmforum_scheduler_notification_additionalFieldProvider.php']);
}

?>