<?php
namespace Mittwald\Typo3Forum\Service\Mailing;

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Service\AbstractService;

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


abstract class AbstractMailingService extends AbstractService implements MailingServiceInterface
{
    protected array $settings = [];
    protected ConfigurationBuilder $configurationBuilder;

    /**
     * HTML mail format.
     */
    const MAILING_FORMAT_HTML = 'html';
    /**
     * Plaintext mail format.
     */
    const MAILING_FORMAT_PLAIN = 'txt';

    protected string $format = self::MAILING_FORMAT_HTML;

    public function injectConfigurationBuilder(ConfigurationBuilder $configurationBuilder): void
    {
        $this->configurationBuilder = $configurationBuilder;
    }

    public function initializeObject(): void
    {
        $this->settings = $this->configurationBuilder->getSettings();
    }

    /**
     * Gets the preferred format of this mailing service.
     * @return string The preferred format of this mailing service.
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * Gets the default sender name. Can be configured in the typoscript setup.
     * @return string The default sender name.
     */
    protected function getDefaultSenderName(): string
    {
        return trim($this->settings['mailing']['sender']['name']);
    }

    /**
     * Gets the default sender address. Can be configured in the typoscript setup.
     * @return string The default sender address.
     */
    protected function getDefaultSenderAddress(): string
    {
        return trim($this->settings['mailing']['sender']['address']);
    }

    /**
     * Gets the default sender. This is composed of the default sender name and the
     * default sender address.
     *
     * @return string The default sender.
     */
    protected function getDefaultSender(): string
    {
        return $this->getDefaultSenderName() . ' <' . $this->getDefaultSenderAddress() . '>';
    }

    /**
     * Gets the preferred character set for sent mails. This usually is TYPO3's
     * renderCharset.
     *
     * @return string The preferred charset.
     */
    protected function getCharset(): string
    {
        return $GLOBALS['TSFE']->renderCharset;
    }
}
