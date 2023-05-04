<?php
/**
 * COPYRIGHT NOTICE
 *
 *  (c) 2018 Mittwald CM Service GmbH & Co KG
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published
 *  by the Free Software Foundation; either version 2 of the License,
 *  or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */
namespace Mittwald\Typo3Forum\Domain\Model;

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Trait ConfigurableEntityTrait
 * Use this trait if an entity needs TypoScript settings and use ConfigurableInterface
 */
trait ConfigurableEntityTrait
{
    protected array $settings = [];

    public function injectSettings(ConfigurationBuilder $configurationBuilder): void
    {
        $this->settings = $configurationBuilder->getSettings();
    }

    /**
     * getSettings.
     * @return array
     */
    public function getSettings()
    {
        if (count($this->settings) === 0) {
            $this->settings = GeneralUtility::makeInstance(ConfigurationBuilder::class)->getSettings();
        }

        return $this->settings;
    }
}
