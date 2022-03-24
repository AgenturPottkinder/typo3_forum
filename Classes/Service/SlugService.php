<?php

/**
 * This file is part of the package netresearch/typo3_forum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mittwald\Typo3Forum\Service;

use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Exception\SiteNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A slug service.
 *
 * @author  Rico Sonntag <rico.sonntag@netresearch.de>
 * @license Netresearch https://www.netresearch.de
 * @link    https://www.netresearch.de
 */
class SlugService
{
    /**
     * @var SlugHelper
     */
    private SlugHelper $slugHelper;

    /**
     * @var string
     */
    private string $table;

    /**
     * @var bool
     */
    private bool $hasToBeUniqueInSite;

    /**
     * @var bool
     */
    private bool $hasToBeUniqueInPid;

    /**
     * Constructor.
     *
     * @param string $table
     * @param string $fieldName
     */
    public function __construct(string $table, string $fieldName = 'slug')
    {
        $this->table = $table;
        $fieldConfig = $GLOBALS['TCA'][$table]['columns'][$fieldName]['config'];

        $evalInfo = !empty($fieldConfig['eval'])
            ? GeneralUtility::trimExplode(',', $fieldConfig['eval'], true)
            : [];

        $this->hasToBeUniqueInSite = in_array('uniqueInSite', $evalInfo, true);
        $this->hasToBeUniqueInPid  = in_array('uniqueInPid', $evalInfo, true);

        // Get slug helper instance
        $this->slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $table,
            $fieldName,
            $fieldConfig
        );
    }

    /**
     * Creates a valid slug (path segment) respecting the TCA field configuration.
     *
     * @param array $recordData
     *
     * @return string
     *
     * @throws SiteNotFoundException
     */
    public function generateSlug(array $recordData): string
    {
        $recordId = (int) $recordData['uid'];
        $pid      = (int) $recordData['pid'];
        $slug     = '';

        if (empty($slug)) {
            $slug = $this->slugHelper->generate($recordData, $pid);
        }

        $state = RecordStateFactory::forName($this->table)
            ->fromArray($recordData, $pid, $recordId);

        if ($this->hasToBeUniqueInSite && !$this->slugHelper->isUniqueInSite($slug, $state)) {
            $slug = $this->slugHelper->buildSlugForUniqueInSite($slug, $state);
        }

        if ($this->hasToBeUniqueInPid && !$this->slugHelper->isUniqueInPid($slug, $state)) {
            $slug = $this->slugHelper->buildSlugForUniqueInPid($slug, $state);
        }

        // Limit slug length
        return substr($slug, 0, 2048);
    }

    /**
     * Generate a slug using the record data loaded by the given record UID.
     *
     * @param int $uid
     *
     * @return string
     * @throws SiteNotFoundException
     */
    public function generateSlugByUid(int $uid): string
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($this->table);

        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder
            ->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class));

        // Get all records with empty slugs
        $statement = $queryBuilder
            ->select('*')
            ->from($this->table)
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT)),
            )
            ->setMaxResults(1)
            ->execute();

        $record = $statement->fetch();

        return $this->generateSlug($record);
    }
}
