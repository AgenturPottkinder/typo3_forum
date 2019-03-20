<?php

namespace Mittwald\Typo3Forum\ViewHelpers\Tag;

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

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

class GenerateActionViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * initializeArguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('currentUser', FrontendUser::class, 'Current frontend user', true);
        $this->registerArgument('subscribedUsers', ObjectStorage::class, 'Subscribed users', true);
    }

    /**
     * render.
     * @return string
     */
    public function render()
    {
        $currentUser = $this->arguments['currentUser'];
        $subscribedUsers = $this->arguments['subscribedUsers'];

        return $subscribedUsers->contains($currentUser);
    }
}
