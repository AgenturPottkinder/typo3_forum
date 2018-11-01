<?php
namespace Mittwald\Typo3Forum\Configuration;

/***************************************************************
 *  Copyright (C) 2017 punkt.de GmbH
 *  Authors: el_equipo <el_equipo@punkt.de>
 *
 *  This script is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published
 *  by the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationBuilder implements SingletonInterface {

    /**
     * @var \TYPO3\CMS\Core\TypoScript\TypoScriptService
     * @inject
     */
    protected $typoScriptService;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var array
     */
    protected $persistenceSettings = [];

    /**
     * @return array
     */
    public function getSettings()
    {
        if (!count($this->settings)) {
            $this->loadTypoScript();
        }

        return $this->settings;
    }


    /**
     * @return array
     */
    public function getPersistenceSettings()
    {
        if (!count($this->persistenceSettings)) {
            $this->loadTypoScript();
        }

        return $this->persistenceSettings;
    }


    protected function loadTypoScript()
    {
        $typoScript = $this->getTypoScriptService()->convertTypoScriptArrayToPlainArray($GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_typo3forum.']);
        $this->settings = $typoScript['settings'];
        $this->persistenceSettings = $typoScript['persistence'];
    }


    /**
     * this method is taken from the old implementation in AbstractRepository. The reason this exists is that if somehow the
     * inject doesn't work, we still have a working TypoScriptService
     *
     * @return TypoScriptService
     */
    protected function getTypoScriptService()
    {
        if (is_null($this->typoScriptService)) {
            $this->typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);
        }

        return $this->typoScriptService;
    }
}
