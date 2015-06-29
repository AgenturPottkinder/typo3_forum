<?php
namespace Mittwald\Typo3Forum\Scheduler;
/*                                                                    - *
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

use TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface;

/**
 * Additional field provider for the counter task
 */
class CounterAdditionalFieldProvider implements AdditionalFieldProviderInterface {

	/**
	 * Lorem
	 *
	 * @param	array														$taskInfo: reference to the array containing the info used in the add/edit form
	 * @param	\TYPO3\CMS\Scheduler\Task\AbstractTask						$task: when editing, reference to the current task object. Null when adding.
	 * @param	\TYPO3\CMS\Scheduler\Controller\SchedulerModuleController	$schedulerModule: reference to the calling object (Scheduler's BE module)
	 * @return	array														Array containg all the information pertaining to the additional fields
	 *																		The array is multidimensional, keyed to the task class name and each field's id
	 *																		For each field it provides an associative sub-array with the following:
	 */
	public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {
		$additionalFields = array();

		if ($schedulerModule->CMD == 'add') {
			$taskInfo['Counter_forumPid'] = 1337;
			$taskInfo['Counter_userPid'] = 1337;
		}

		if ($schedulerModule->CMD == 'edit') {
			$taskInfo['Counter_forumPid'] = $task->getForumPid();
			$taskInfo['Counter_userPid'] = $task->getUserPid();
		}

		$additionalFields['Counter_forumPid'] = array(
			'code'     => '<input type="text" name="tx_scheduler[Counter_forumPid]" value="' . intval($taskInfo['Counter_forumPid']) . '" />',
			'label'    => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_forumPid',
			'cshKey'   => '',
			'cshLabel' => ''
		);

		$additionalFields['Counter_userPid'] = array(
			'code'     => '<input type="text" name="tx_scheduler[Counter_userPid]" value="' . intval($taskInfo['Counter_userPid']) . '" />',
			'label'    => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_userPid',
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
		$submittedData['Counter_forumPid'] = intval($submittedData['Counter_forumPid']);
		$submittedData['Counter_userPid'] = intval($submittedData['Counter_userPid']);
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
		$task->setUserPid($submittedData['Counter_userPid']);
		$task->setForumPid($submittedData['Counter_forumPid']);
	}
}