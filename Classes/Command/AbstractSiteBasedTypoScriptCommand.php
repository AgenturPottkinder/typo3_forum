<?php

namespace Mittwald\Typo3Forum\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\TypoScript\TemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\RootlineUtility;

abstract class AbstractSiteBasedTypoScriptCommand extends Command
{
    protected SiteFinder $siteFinder;
    protected TemplateService $templateService;

    protected array $settings = [];
    protected int $storagePage = 0;

    public function injectTyposcriptHelpers(
        SiteFinder $siteFinder,
        TemplateService $templateService
    ): void {
        $this->siteFinder = $siteFinder;
        $this->templateService = $templateService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->siteFinder->getAllSites() as $site) {
            $this->settings = [];

            $rootline = GeneralUtility::makeInstance(
                RootlineUtility::class,
                $site->getRootPageId()
            )->get();
            $this->templateService->runThroughTemplates($rootline);
            $this->templateService->generateConfig();

            $rawTyposcript = $this->templateService->setup['plugin.']['tx_typo3forum.'] ?? [];
            $this->settings = $rawTyposcript['settings.'] ?? [];

            if (count($this->settings) > 0) {
                $storagePage = (int)($rawTyposcript['persistence.']['storagePid'] ?? 0);
                $this->setStoragePage($storagePage);
                $this->storagePage = $storagePage;

                $this->executeForSite($site, $input, $output);
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Override to set the storage page for repositories.
     */
    protected function setStoragePage(int $storagePid): void
    {}

    /**
     * Execute this command for a site. Automatically called for every
     * site whose root page contains typo3_forum TypoScript configuration.
     */
    abstract protected function executeForSite(
        SiteInterface $site,
        InputInterface $input,
        OutputInterface $output
    ): void;
}
