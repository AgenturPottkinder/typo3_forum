<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;

/*                                                                      *
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

use Mittwald\Typo3Forum\Domain\Model\SubscribeableInterface;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\ViewHelpers\IfViewHelper;

/**
 * ViewHelper that renders its contents, when a certain user has subscribed
 * a specific object.
 */
class IfFavSubscribedViewHelper extends IfViewHelper
{
    /**
     * Renders the contents of this view helper, when a user has subscribed a
     * specific subscribeable object.
     *
     * @param SubscribeableInterface $object The object that needs to be subscribed in order for the contents to be rendered.
     * @param FrontendUser $user
     * @return string
     */
    public function render()
    {
        /* @var FrontendUser $user */
        $user = $this->arguments['user'];
        if ($user === null) {
            $user =& GeneralUtility::makeInstance(FrontendUserRepository::class)->findCurrent();
        }
        foreach ($this->arguments['object']->getSubscribers() as $subscriber) {
            if ($subscriber->getUid() == $user->getUid()) {
                return $this->renderThenChild();
            }
        }
        return $this->renderElseChild();
    }
}
