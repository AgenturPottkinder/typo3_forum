<?php

namespace Mittwald\Typo3Forum\ViewHelpers\User;

/*                                                                      *
 *  COPYRIGHT NOTICE                                                    *
 *                                                                      *
 *  (c) 2018 Mittwald CM Service GmbH & Co KG                           *
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

use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * ViewHelper that renders its contents, when a certain user has subscribed
 * a specific object.
 */
class IfSubscribedViewHelper extends AbstractConditionViewHelper
{

    /**
     * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
     */
    protected $frontendUserRepository;


    /**
     * @var
     */
    protected $forumRepository;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;


    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('object', SubscribeableInterface::class, 'Object to check', true);
        $this->registerArgument('user', FrontendUser::class, 'className which object has to be', false, null);
    }

    /**
     * evaluateCondition.
     * @todo get rid of foreach loop
     * @param null $arguments
     * @return bool
     */
    protected static function evaluateCondition($arguments = null): bool
    {
        $user = $arguments['user'];
        $object = $arguments['object'];

        if (!($object instanceof SubscribeableInterface) || !($user instanceof FrontendUser) && !($user = self::getFrontendUserRepository()->findCurrent())) {
            return false;
        }

        foreach ($object->getSubscribers() as $subscriber) {
            if (($subscriber === $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return FrontendUserRepository
     */
    public static function getFrontendUserRepository(): FrontendUserRepository
    {
        return self::getObjectManager()->get(FrontendUserRepository::class);
    }

    /**
     * @return ObjectManagerInterface
     */
    public static function getObjectManager(): ObjectManagerInterface
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
