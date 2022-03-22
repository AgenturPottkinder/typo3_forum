<?php

/**
 * This file is part of the package netresearch/typo3_forum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Mittwald\Typo3Forum\Service;

use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * A frontend user service class.
 *
 * @author  Rico Sonntag <rico.sonntag@netresearch.de>
 * @license Netresearch https://www.netresearch.de
 * @link    https://www.netresearch.de
 */
class FrontendUserService implements SingletonInterface
{
    /**
     * @var Context
     */
    private Context $context;

    /**
     * FrontendUserService constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Returns TRUE if the given frontend user matches the currently logged in frontend user.
     *
     * @param null|FrontendUser $user
     *
     * @return bool
     *
     * @throws AspectNotFoundException
     */
    public function isLoggedIn(FrontendUser $user = null): bool
    {
        return ($user instanceof FrontendUser)
            && ($user->getUid() === $this->getFrontendUserUid());
    }

    /**
     * Returns the current logged in frontend user UID.
     *
     * @return null|int
     *
     * @throws AspectNotFoundException
     */
    private function getFrontendUserUid(): ?int
    {
        /** @var TypoScriptFrontendController $frontendUserController */
        $frontendUserController = $GLOBALS['TSFE'];

        if (!empty($frontendUserController->fe_user->user['uid']) && $this->hasLoggedInFrontendUser()) {
            return (int) $frontendUserController->fe_user->user['uid'];
        }

        return null;
    }

    /**
     * Returns TRUE if there is a currently logged in frontend user.
     *
     * @return bool
     *
     * @throws AspectNotFoundException
     */
    private function hasLoggedInFrontendUser(): bool
    {
        return $this->context->getPropertyFromAspect('frontend.user', 'isLoggedIn');
    }
}
