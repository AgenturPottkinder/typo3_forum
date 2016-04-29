<?php
namespace Mittwald\Typo3Forum\ViewHelpers\User;

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
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper;

/**
 * ViewHelper that renders a user's avatar.
 */
class AvatarViewHelper extends ImageViewHelper
{

    /**
     * Avatar of user object
     *
     * @see https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Image/
     * @param FrontendUser $user user
     * @param string $src a path to a file, a combined FAL identifier or an uid (int). If $treatIdAsReference is set, the integer is considered the uid of the sys_file_reference record. If you already got a FAL object, consider using the $image parameter instead
     * @param string $width width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param string $height height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.
     * @param int $minWidth minimum width of the image
     * @param int $minHeight minimum height of the image
     * @param int $maxWidth maximum width of the image
     * @param int $maxHeight maximum height of the image
     * @param bool $treatIdAsReference given src argument is a sys_file_reference record
     * @param FileInterface|AbstractFileFolder $image a FAL object
     * @param string|bool $crop overrule cropping of image (setting to FALSE disables the cropping set in FileReference)
     * @param bool $absolute Force absolute URL
     *
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception
     * @return string Rendered tag
     */
    public function render(
        $src = null,
        $width = null,
        $height = null,
        $minWidth = null,
        $minHeight = null,
        $maxWidth = null,
        $maxHeight = null,
        $treatIdAsReference = false,
        $image = null,
        $crop = null,
        $absolute = false,
        FrontendUser $user = null
    ) {
        $avatarFilename = null;

        if (($user != null) && !($user instanceof AnonymousFrontendUser)) {
            $avatarFilename = $user->getImagePath();
        }

        if ($avatarFilename === null) {
            $avatarFilename = ExtensionManagementUtility::siteRelPath('typo3_forum').'Resources/Public/Images/Icons/AvatarEmpty.png';
        }
        if ($height === null) {
            $height = $width;
        }

        return parent::render($avatarFilename, $width, $height);
    }
}
