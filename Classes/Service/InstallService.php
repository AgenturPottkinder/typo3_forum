<?php
/**
 *
 * COPYRIGHT NOTICE
 *
 *  (c) 2016 Mittwald CM Service GmbH & Co KG
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published
 *  by the Free Software Foundation; either version 2 of the License,
 *  or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */

namespace Mittwald\Typo3Forum\Service;


use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class InstallService
 * @package Mittwald\Typo3Forum\Service
 */
class InstallService
{

    /**
     * @var string
     */
    private $extensionKey = 'typo3_forum';

    /**
     * @var string
     */
    protected $messageQueueByIdentifier = 'extbase.flashmessages.tx_extensionmanager_tools_extensionmanagerextensionmanager';

    /**
     * @param null $extensionKey
     */
    public function checkForMigrationOption($extensionKey = null)
    {

        if (($extensionKey === $this->extensionKey) && ($this->isUseful())) {
            $flashMessage = GeneralUtility::makeInstance(
                'TYPO3\\CMS\\Core\\Messaging\\FlashMessage',
                'Use update script for typo3_forum',
                'Use of mm_forum detected. You can use the update script of typo3_forum in extension manager',
                FlashMessage::NOTICE,
                true
            );
            $this->addFlashMessage($flashMessage);

            return;
        }
    }

    /**
     * @todo Implement database analyzer for mm_forum tables
     * @return bool
     */
    protected function isUseful()
    {
        return true;
    }

    /**
     * Adds a Flash Message to the Flash Message Queue
     *
     * @param FlashMessage $flashMessage
     * @return void
     */
    protected function addFlashMessage(FlashMessage $flashMessage)
    {
        if ($flashMessage) {
            /** @var $flashMessageService \TYPO3\CMS\Core\Messaging\FlashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            /** @var $flashMessageQueue \TYPO3\CMS\Core\Messaging\FlashMessageQueue */
            $flashMessageQueue = $flashMessageService->getMessageQueueByIdentifier($this->messageQueueByIdentifier);

            return $flashMessageQueue->enqueue($flashMessage);
        }
    }
}