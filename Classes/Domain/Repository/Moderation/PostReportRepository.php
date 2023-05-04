<?php
namespace Mittwald\Typo3Forum\Domain\Repository\Moderation;

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

use Mittwald\Typo3Forum\Domain\Model\Moderation\PostReport;
use Mittwald\Typo3Forum\Domain\Repository\AbstractRepository;
use Mittwald\Typo3Forum\Service\Authentication\AuthenticationServiceInterface;

/**
 * Repository class for report objects.
 */
class PostReportRepository extends AbstractRepository
{
    protected AuthenticationServiceInterface $authenticationService;

    public function injectAuthenticationService(AuthenticationServiceInterface $authenticationService): void
    {
        $this->authenticationService = $authenticationService;
    }

    public function findAllAuthorizedToEdit(): array
    {
        return array_filter(
            $this->findAll()->toArray(),
            function (PostReport $postReport): bool {
                return
                    $postReport->getTopic() !== null
                    && $postReport->getTopic()->getForum() !== null
                    && $this->authenticationService->checkModerationAuthorization(
                        $postReport->getTopic()->getForum()
                    )
                ;
            }
        );
    }
}
