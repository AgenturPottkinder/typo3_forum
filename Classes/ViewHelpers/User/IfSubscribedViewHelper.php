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
use TYPO3\CMS\Fluid\ViewHelpers\IfViewHelper;

/**
 * ViewHelper that renders its contents, when a certain user has subscribed
 * a specific object.
 */
class IfSubscribedViewHelper extends IfViewHelper
{

    /**
     * @var \Mittwald\Typo3Forum\Domain\Repository\User\FrontendUserRepository
     * @inject
     */
    protected $frontendUserRepository;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('object', 'Mittwald\\Typo3Forum\\Domain\\Model\\SubscribeableInterface', 'Object to check', true);
        $this->registerArgument('user', 'Mittwald\\Typo3Forum\\Domain\\Model\\User\\FrontendUser', 'className which object has to be', false, null);
    }

    /**
     * @return string
     */
    public function render()
    {
        $object = $this->getSubscribeableObject();
        $user = $this->arguments['user'];

        if ($user === null) {
            $user = $this->frontendUserRepository->findCurrent();
        }

        foreach ($object->getSubscribers() As $subscriber) {
            if ($subscriber->getUid() == $user->getUid()) {
                return $this->renderThenChild();
            }
        }

        return $this->renderElseChild();
    }

    /**
     * @return SubscribeableInterface
     */
    protected function getSubscribeableObject()
    {
        return $this->arguments['object'];
    }
}