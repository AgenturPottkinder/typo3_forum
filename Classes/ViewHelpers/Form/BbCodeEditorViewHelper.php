<?php
namespace Mittwald\Typo3Forum\ViewHelpers\Form;

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

use Mittwald\Typo3Forum\TextParser\Panel\AbstractPanel;
use Mittwald\Typo3Forum\TextParser\Panel\PanelInterface;
use Mittwald\Typo3Forum\Utility\TypoScript;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\InvalidClassException;
use TYPO3\CMS\Fluid\ViewHelpers\Form\TextareaViewHelper;

/**
 * ViewHelper that renders a textarea with additional bb code buttons.
 */
class BbCodeEditorViewHelper extends TextareaViewHelper
{
    protected FrontendInterface $cache;
    protected TypoScript $typoscriptReader;
    protected UriBuilder $uriBuilder;
    public function __construct(
        FrontendInterface $cache,
        TypoScript $typoscriptReader,
        UriBuilder $uriBuilder
    ) {
        parent::__construct();

        $this->cache = $cache;
        $this->typoscriptReader = $typoscriptReader;
        $this->uriBuilder = $uriBuilder;
    }

    /**
     * Configuration array. This array is read from the typoscript setup by
     * the typoscript reader instance (see above).
     * @var array
     */
    protected $configuration;

    /**
     * Panels that contain bb code buttons.
     * @var AbstractPanel[]
     */
    protected array $panels = [];

    /**
     * @var string
     */
    protected string $javascriptSetup;

    /**
     * Initializes the view helper arguments.
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument(
            'configuration',
            'string',
            'Path to TS configuration',
            false,
            'plugin.tx_typo3forum.settings.textParsing.editorPanel'
        );
    }

    /**
     * Loads the editor configuration
     * @throws \TYPO3\CMS\Extbase\Object\InvalidClassException
     */
    protected function initializeJavascriptSetupFromConfiguration(string $configurationPath): string
    {
        // TODO reenable cache of bbcodeeditor
        if (false && $this->cache->has('bbcodeeditor-jsonconfig')) {
            $this->javascriptSetup = $this->cache->get('bbcodeeditor-jsonconfig');
            return $this->javascriptSetup;
        }

        $this->configuration = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);

        foreach ($this->configuration['panels.'] as $panelConfiguration) {
            $panel = GeneralUtility::makeInstance($panelConfiguration['className']);
            if (!$panel instanceof PanelInterface) {
                throw new InvalidClassException('Expected an implementation of the ' . PanelInterface::class . ' interface!', 1315835842);
            }
            $panel->setSettings($panelConfiguration);
            $this->panels[] = $panel;
        }

        $this->javascriptSetup = '<script>' .
            'var bbcodeSettings = ' .
            json_encode($this->getPanelSettings()) . ';' .
            'window.setTimeout(function(){$(document).ready(function() {' .
            '$(\'#' . $this->arguments['id'] . '\').markItUp(bbcodeSettings);' .
            '});}, 500);</script>';
        $this->cache->set('bbcodeeditor-jsonconfig', $this->javascriptSetup);
        return $this->javascriptSetup;
    }

    /**
     * Renders the editor. This method first adds some javascript inclusions to the
     * page header, then renders the options panel and finally renders the main
     * textarea using the inherited render() method.
     */
    public function render(): string
    {
        $this->initializeJavascriptSetupFromConfiguration($this->arguments['configuration']);
        return $this->javascriptSetup . parent::render();
    }

    /**
     * getPanelSettings
     */
    protected function getPanelSettings(): array
    {
        $settings = [];
        foreach ($this->panels as $panel) {
            $items = $panel->getItems();
            if ($items !== null && count($items) > 0) {
                $settings = array_merge($settings, $items);
                $settings[] = ['separator' => '---------------'];
            }
        }

        $settings[] = [
            'name' => 'Preview',
            'className' => 'preview',
            'call' => 'preview'
        ];

        $uri = $this->uriBuilder
            ->reset()
            ->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setArguments(['type' => 43568275])
            ->uriFor('preview', [], 'Ajax', 'Typo3Forum', 'Ajax');

        $editorSettings = [
            'previewParserPath' => $uri,
            'previewParserVar' => 'tx_typo3forum_ajax[text]',
            'markupSet' => $settings,
        ];

        if (isset($this->configuration['editorSettings.']) && is_array($this->configuration['editorSettings.'])) {
            $editorSettings = array_merge($editorSettings, $this->configuration['editorSettings.']);
        }

        return $editorSettings;
    }
}
