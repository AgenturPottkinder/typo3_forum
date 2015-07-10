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
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;
use TYPO3\CMS\Scheduler\Task\AbstractTask;

/**
 * Additional field provider for the counter task
 */
class CounterAdditionalFieldProvider implements AdditionalFieldProviderInterface {

	/**
	 * Lorem
	 *
	 * @param array $taskInfo : reference to the array containing the info used in the add/edit form
	 * @param AbstractTask $task : when editing, reference to the current task object. Null when adding.
	 * @param SchedulerModuleController $schedulerModule : reference to the calling object (Scheduler's BE module)
	 * @return array Array containg all the information pertaining to the additional fields.
	 */
	public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule) {
		$additionalFields = [];

		if ($schedulerModule->CMD == 'add') {
			$taskInfo['Counter_forumPid'] = 1337;
			$taskInfo['Counter_userPid'] = 1337;
		}

		if ($schedulerModule->CMD == 'edit') {
			$taskInfo['Counter_forumPid'] = $task->getForumPid();
			$taskInfo['Counter_userPid'] = $task->getUserPid();
		}

		$additionalFields['Counter_forumPid'] = [
			'code' => '<input type="text" name="tx_scheduler[Counter_forumPid]" value="' . (int)$taskInfo['Counter_forumPid'] . '" />',
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_forumPid',
			'cshKey' => '',
			'cshLabel' => '',
		];

		$additionalFields['Counter_userPid'] = [
			'code' => '<input type="text" name="tx_scheduler[Counter_userPid]" value="' . (int)$taskInfo['Counter_userPid'] . '" />',
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_counter_userPid',
			'cshKey' => '',
			'cshLabel' => '',
		];

		return $additionalFields;
	}

	/**
	 * Checks any additional data that is relevant to this task. If the task
	 * class is not relevant, the method is expected to return TRUE
	 *
	 * @param array $submittedData : reference to the array containing the data submitted by the user
	 * @param SchedulerModuleController $schedulerModule : reference to the calling object (Scheduler's BE module)
	 * @return boolean True if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, SchedulerModuleController $schedulerModule) {
		$submittedData['Counter_forumPid'] = (int)$submittedData['Counter_forumPid'];
		$submittedData['Counter_userPid'] = (int)$submittedData['Counter_userPid'];
		return TRUE;
	}

	/**
	 * Saves any additional input into the current task object if the task
	 * class matches.
	 *
	 * @param  array $submittedData : array containing the data submitted by the user
	 * @param  AbstractTask $task : reference to the current task object
	 */
	public function saveAdditionalFields(array $submittedData, AbstractTask $task) {
		$task->setUserPid($submittedData['Counter_userPid']);
		$task->setForumPid($submittedData['Counter_forumPid']);
	}
}