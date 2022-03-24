<?php

/**
 * This file is part of the package netresearch/typo3_forum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mittwald\Typo3Forum\Updates;

use Mittwald\Typo3Forum\Service\SlugService;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

/**
 * Fills the slug fields of the typo3_forum tables with a proper values.
 *
 * @author  Rico Sonntag <rico.sonntag@netresearch.de>
 * @license Netresearch https://www.netresearch.de
 * @link    https://www.netresearch.de
 */
class PopulateTypo3ForumSlugs implements UpgradeWizardInterface
{
    /**
     * @var string
     */
    private $fieldName = 'slug';

    /**
     * Returns the unique identifier of this updater.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'populateTypo3ForumSlugs';
    }

    /**
     * The title of this updater.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return '[typo3_forum]: Introduce URL parts ("slugs") to all typo3_forum tables';
    }

    /**
     * @return string Longer description of this updater
     */
    public function getDescription(): string
    {
        return 'Slugs for typo3_forum tables';
    }

    /**
     * Checks whether updates are required.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {
        // Check if the database table even exists
        return $this->checkIfWizardIsRequired('tx_typo3forum_domain_model_forum_tag')
            || $this->checkIfWizardIsRequired('tx_typo3forum_domain_model_forum_topic')
            || $this->checkIfWizardIsRequired('tx_typo3forum_domain_model_forum_forum');
    }

    /**
     * All new fields and tables must exist.
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }

    /**
     * Performs the accordant updates.
     *
     * @return bool Whether everything went smoothly or not
     *
     * @throws SiteNotFoundException
     */
    public function executeUpdate(): bool
    {
        $this->populateSlugs('tx_typo3forum_domain_model_forum_tag');
        $this->populateSlugs('tx_typo3forum_domain_model_forum_topic');
        $this->populateSlugs('tx_typo3forum_domain_model_forum_forum');
        return true;
    }

    /**
     * Fills the database table with slugs based on the page title and its configuration.
     *
     * @param string $table The table to populate
     *
     * @throws SiteNotFoundException
     */
    private function populateSlugs(string $table): void
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($table);

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        // Get all records with empty slugs
        $statement = $queryBuilder
            ->select('*')
            ->from($table)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq($this->fieldName, $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull($this->fieldName)
                )
            )
            ->execute();

        /** @var SlugService $slugService */
        $slugService = GeneralUtility::makeInstance(SlugService::class, $table, $this->fieldName);

        while ($record = $statement->fetch()) {
            $recordId = (int) $record['uid'];
            $slug     = $slugService->generateSlug($record);

            $connection->update(
                $table,
                [
                    $this->fieldName => $slug,
                ],
                [
                    'uid' => $recordId,
                ]
            );
        }
    }

    /**
     * Check if there are record within database table with an empty "slug" field.
     *
     * @param string $table The table to check
     *
     * @return bool
     */
    private function checkIfWizardIsRequired(string $table): bool
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        $queryBuilder = $connectionPool->getQueryBuilderForTable($table);
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        // Get all records with empty slugs
        $numberOfEntries = $queryBuilder
            ->count('uid')
            ->from($table)
            ->where(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq($this->fieldName, $queryBuilder->createNamedParameter('')),
                    $queryBuilder->expr()->isNull($this->fieldName)
                )
            )
            ->execute()
            ->fetchColumn();

        return $numberOfEntries > 0;
    }
}
