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
 * Additional field provider for the notification generator task
 */
class NotificationAdditionalFieldProvider implements AdditionalFieldProviderInterface {

	/**
	 *
	 * @param  array $taskInfo : reference to the array containing the info used in the add/edit form
	 * @param  AbstractTask $task : when editing, reference to the current task object. Null when adding.
	 * @param  SchedulerModuleController $schedulerModule : reference to the calling object (Scheduler's BE module)
	 * @return  array                            Array containg all the information pertaining to the additional fields
	 *                                    The array is multidimensional, keyed to the task class name and each field's id
	 *                                    For each field it provides an associative sub-array with the following:
	 */
	public function getAdditionalFields(array &$taskInfo, $task, SchedulerModuleController $schedulerModule) {
		$additionalFields = [];

		if ($schedulerModule->CMD == 'add') {
			$taskInfo['Notification_forumPids'] = 1337;
			$taskInfo['Notification_userPids'] = 1337;
			$taskInfo['Notification_notificationPid'] = 1337;
		}

		if ($schedulerModule->CMD == 'edit') {
			$taskInfo['Notification_forumPids'] = $task->getForumPids();
			$taskInfo['Notification_userPids'] = $task->getUserPids();
			$taskInfo['Notification_notificationPid'] = $task->getNotificationPid();
		}

		$additionalFields['Notification_forumPids'] = [
			'code' => '<input type="text" name="tx_scheduler[Notification_forumPids]" value="' . htmlspecialchars($taskInfo['Notification_forumPids']) . '" />',
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_forumPid',
			'cshKey' => '',
			'cshLabel' => ''
		];

		$additionalFields['Notification_userPids'] = [
			'code' => '<input type="text" name="tx_scheduler[Notification_userPids]" value="' . htmlspecialchars($taskInfo['Notification_userPids']) . '" />',
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_userPid',
			'cshKey' => '',
			'cshLabel' => ''
		];

		$additionalFields['Notification_notificationPid'] = [
			'code' => '<input type="text" name="tx_scheduler[Notification_notificationPid]" value="' . (int)$taskInfo['Notification_notificationPid'] . '" />',
			'label' => 'LLL:EXT:typo3_forum/Resources/Private/Language/locallang.xml:tx_typo3forum_scheduler_notification_notificationPid',
			'cshKey' => '',
			'cshLabel' => ''
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
		$submittedData['Notification_forumPids'] = htmlspecialchars($submittedData['Notification_forumPids']);
		$submittedData['Notification_userPids'] = htmlspecialchars($submittedData['Notification_userPids']);
		$submittedData['Notification_notificationPid'] = (int)$submittedData['Notification_notificationPid'];
		return TRUE;
	}

	/**
	 * Saves any additional input into the current task object if the task
	 * class matches.
	 *
	 * @param array $submittedData : array containing the data submitted by the user
	 * @param AbstractTask $task : reference to the current task object
	 */
	public function saveAdditionalFields(array $submittedData, AbstractTask $task) {
		$task->setUserPids($submittedData['Notification_userPids']);
		$task->setForumPids($submittedData['Notification_forumPids']);
		$task->setNotificationPid($submittedData['Notification_notificationPid']);
	}
}