<?php
namespace Mittwald\Typo3Forum\Domain\Model\User;

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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Rank extends AbstractEntity
{

    /**
     * The name of this rank
     * @var string
     */
    public $name;

    /**
     * The limit of points. If a user have less then this limit, this rank will be used.
     * @var int
     */
    public $pointLimit;

    /**
     * The amount of user
     * @var int
     */
    public $userCount;

    /**
     * Get the name of this rank
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of this rank
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get the limit of this rank
     * @return int
     */
    public function getPointLimit()
    {
        return $this->pointLimit;
    }

    /**
     * Set the limit of this rank
     *
     * @param int $pointLimit
     */
    public function setPointLimit($pointLimit)
    {
        $this->pointLimit = $pointLimit;
    }

    /**
     * Get the amount of users of this rank
     * @return int
     */
    public function getUserCount()
    {
        return $this->userCount;
    }

    /**
     * Set the counter of user count +1
     */
    public function increaseUserCount()
    {
        $this->userCount++;
    }

    /**
     * Set the counter of user count -1
     */
    public function decreaseUserCount()
    {
        $this->userCount--;
    }
}
