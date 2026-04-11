<?php
namespace Mittwald\Typo3Forum\TextParser;

use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Service\AbstractService;
use Mittwald\Typo3Forum\Utility\TypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service class for parsing text values for display. This service handles
 * for example the rendering of bb codes, smileys, etc.
 */
class TextParserService extends AbstractService
{
    protected array $settings = [];
    /**
     * An array of the parsing services that are to be used to render text input.
     * @var \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService[]
     */
    protected array $parsingServices = [];

    /**
     * An instance of the typo3_forum typoscript reader. Is used to read the
     * text parser's typoscript configuration.
     */
    protected TypoScript $typoscriptReader;

    public function __construct(
        TypoScript $typoScriptReader
    ) {
        $this->typoscriptReader = $typoScriptReader;
    }

    /**
     * Loads the text parser configuration from a certain configuration path.
     *
     * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
     */
    public function loadConfiguration(string $configurationPath = 'plugin.tx_typo3forum.settings.textParsing'): void
    {
        if (count($this->settings) > 0) {
            return;
        }

        $this->settings = $this->typoscriptReader->loadTyposcriptFromPath($configurationPath);
        foreach ($this->settings['enabledServices.'] as $key => $className) {
            if (substr($key, -1, 1) === '.') {
                continue;
            }

            /** @var \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService $newService */
            $newService = GeneralUtility::makeInstance($className);
            if ($newService instanceof \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService) {
                $newService->setSettings((array)$this->settings['enabledServices.'][$key ]);
                //TODO: Controller Context
                //$newService->setControllerContext($this->controllerContext);
                $this->parsingServices[] = $newService;
            } else {
                throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception('Invalid class; expected an instance of \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService!', 1315916625);
            }
        }
    }

    /**
     * Parses a certain input text.
     * @throws \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception
     */
    public function parseText(string $text, ?Post $post = null): string
    {
        if ($this->settings === null) {
            throw new \Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception('The textparser is not configured!', 1284730639);
        }

        foreach ($this->parsingServices as &$parsingService) {
            /** @var $parsingService \Mittwald\Typo3Forum\TextParser\Service\AbstractTextParserService */
            $text = $parsingService->getParsedText($text, $post);
        }
        return $text;
    }
}
