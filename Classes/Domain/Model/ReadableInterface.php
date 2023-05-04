<?php
namespace Mittwald\Typo3Forum\Domain\Model;

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;

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

/**
 * Interface definition for objects that can be read by individual users.
 */
interface ReadableInterface
{

    /**
     * Adds a reader to this object.
     *
     * @param User\FrontendUser $reader The reader.
     */
    public function addReader(User\FrontendUser $reader);

    /**
     * Removes a reader from this object.
     *
     * @param User\FrontendUser $reader The reader.
     */
    public function removeReader(User\FrontendUser $reader);

    /**
     * Removes all readers from this object.
     */
    public function removeAllReaders();

    /**
     * Determines whether a certain user (NULL for anonymous) has read this object.
     */
    public function hasBeenReadByUser(?FrontendUser $reader = null): bool;

    public function hasBeenReadByCurrentUser(): bool;
}
