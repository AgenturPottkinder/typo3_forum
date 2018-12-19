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

use TYPO3\CMS\Scheduler\Task\AbstractTask;

class SessionResetter extends AbstractDatabaseTask
{

    /**
     * @var int
     */
    protected $userPid;

    /**
     * @return int
     */
    public function getUserPid()
    {
        return $this->userPid;
    }

    /**
     * @param int $userPid
     */
    public function setUserPid($userPid)
    {
        $this->userPid = $userPid;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        if ((int)$this->getUserPid() === 0) {
            return false;
        }

        $updateArray = [
            'tx_typo3forum_helpful_count_session' => 0,
            'tx_typo3forum_post_count_session' => 0,
        ];


        $queryBuilder = $this->getDatabaseConnection('fe_users');
        $queryBuilder->update('fe_users', 'users');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq(
                'users.pid',
                $queryBuilder->createNamedParameter($this->getUserPid(), \PDO::PARAM_INT)
            )
        );

        foreach ($updateArray as $key => $value) {
            $queryBuilder->set($key, $value);
        }

        $res = $queryBuilder->execute();

        if (!$res) {
            return false;
        } else {
            return true;
        }
    }
}
