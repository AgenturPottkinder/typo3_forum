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

use TYPO3\CMS\Fluid\ViewHelpers\Form\TextareaViewHelper;

/**
 * ViewHelper that renders a textarea with additional bb code buttons.
 */
class BbCodeEditorViewHelper extends TextareaViewHelper {

	/**
	 * cache
	 *
	 * @var \Mittwald\Typo3Forum\Cache\Cache
	 * @inject
	 */
	protected $cache = NULL;

	/**
	 * Instance of the typo3_forum TypoScript reader class. This class is used
	 * to read a bbcode editor's configuration from the typoscript setup.
	 * @var \Mittwald\Typo3Forum\Utility\TypoScript
	 * @inject
	 */
	protected $typoscriptReader = NULL;

	/**
	 * Configuration array. This array is read from the typoscript setup by
	 * the typoscript reader instance (see above).
	 * @var array
	 */
	protected $configuration = NULL;

	/**
	 * Panels that contain bb code buttons.
	 * @var array<\Mittwald\Typo3Forum\TextParser\Panel\AbstractPanel>
	 */
	protected $panels = [];

	/**
	 * An Instance of the Extbase Object Manager class.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager = NULL;

    /**
     * @var string
     */
	protected $javascriptSetup;

    /**
	 * Initializes the view helper arguments.
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('configuration', 'string', 'Path to TS configuration', FALSE,
		                        'plugin.tx_typo3forum.settings.textParsing.editorPanel');
	}

	/**
	 * Loads the editor configuration
	 *
	 * @param string $configurationPath The typoscript setup path in which the
	 *                                   editor configuration is stored.
	 *
	 * @return string
	 * @throws \TYPO3\CMS\Extbase\Object\InvalidClassException
	 */
	protected function initializeJavascriptSetupFromConfiguration($configurationPath) {
		$this->configuration = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
		if ($this->cache->has('bbcodeeditor-jsonconfig')) {
			return $this->javascriptSetup = $this->cache->get('bbcodeeditor-jsonconfig');
		}

		foreach ($this->configuration['panels.'] as $key => $panelConfiguration) {
			$panel = $this->objectManager->get($panelConfiguration['className']);
			if (!$panel instanceof \Mittwald\Typo3Forum\TextParser\Panel\PanelInterface) {
				throw new \TYPO3\CMS\Extbase\Object\InvalidClassException('Expected an implementation of the \Mittwald\Typo3Forum\TextParser\Panel\PanelInterface interface!', 1315835842);
			}
			$panel->setSettings($panelConfiguration);
			$this->panels[] = $panel;
		}

		$this->javascriptSetup = '<script>' .
            'var bbcodeSettings = ' .
            json_encode($this->getPanelSettings()) . ';' .
            '$(document).ready(function() {' .
            '$(\'#' . $this->arguments['id'] . '\').markItUp(bbcodeSettings);' .
            '});</script>';
		$this->cache->set('bbcodeeditor-jsonconfig', $this->javascriptSetup);
		return $this->javascriptSetup;
	}

	/**
	 * Renders the editor. This method first adds some javascript inclusions to the
	 * page header, then renders the options panel and finally renders the main
	 * textarea using the inherited render() method.
	 *
	 * @return string HTML content
	 */
    public function render()
    {
        $this->initializeJavascriptSetupFromConfiguration($this->arguments['configuration']);
        return $this->javascriptSetup . parent::render();
    }

	/**
	 * getPanelSettings
	 *
	 * @return array
	 */
	protected function getPanelSettings() {
		$settings = [];
		foreach ($this->panels as $panel) {
			$items = $panel->getItems();
			if (!empty($items)) {
				$settings   = array_merge($settings, $items);
				$settings[] = ['separator' => '---------------'];
			}
		}

        $settings[] = [
            'name' => 'Preview',
            'className' => 'preview',
            'call' => 'preview'
        ];

		/* @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder */
        $uriBuilder = $this->renderingContext->getControllerContext()->getUriBuilder();
        $uri = $uriBuilder
            ->reset()
            ->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setArguments(['type' => 43568275])
            ->uriFor('preview', [], 'Ajax', 'Typo3Forum', 'Ajax');

        $editorSettings = [
            'previewParserPath' => $uri,
            'previewParserVar' => 'tx_typo3forum_ajax[text]',
            'markupSet' => $settings
        ];

		if (isset($this->configuration['editorSettings.']) && is_array($this->configuration['editorSettings.'])) {
			$editorSettings = array_merge($editorSettings, $this->configuration['editorSettings.']);
		}

		return $editorSettings;
	}
}
