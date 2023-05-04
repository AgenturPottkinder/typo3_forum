<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Tag;

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

use Mittwald\Typo3Forum\Domain\Model\User\AnonymousFrontendUser;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

class IfCanCreateViewHelper extends AbstractConditionViewHelper
{
    protected static ?FrontendUser $currentUser = null;

    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('user', FrontendUser::class, 'User to check identity of.', false, null);
    }

    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        $authenticationService = GeneralUtility::makeInstance(AuthenticationService::class);
        if (static::$currentUser === null) {
            static::$currentUser = $authenticationService->getUser();
        }

        if (static::$currentUser === null || static::$currentUser instanceof AnonymousFrontendUser) {
            return false;
        }

        return static::$currentUser->canCreateTags();
    }
}
