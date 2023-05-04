<?php
namespace Mittwald\Typo3Forum\Utility;

use Mittwald\Typo3Forum\Domain\Exception\TextParser\Exception;
use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Slug
{
    /**
     * @throws \TYPO3\CMS\Core\Exception\SiteNotFoundException
     */
    public static function generateUniqueSlug(int $uid, string $tableName, string $slugFieldName): string
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableName)
            ->createQueryBuilder()
        ;
        $record = $queryBuilder
            ->select('*')
            ->from($tableName)
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchAssociative()
        ;

        if ($record === false || count($record) === 0) {
            throw new Exception('Record [#' . $uid . '] in table "' . $tableName . '" could not be found.');
        }

        // Setup SlugHelper with TCA information.
        $fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config'];
        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $tableName,
            $slugFieldName,
            $fieldConfig
        );

        // Generate slug, ignoring uniqueness constraints for now.
        $slug = $slugHelper->generate($record, $record['pid']);
        $state = RecordStateFactory::forName($tableName)
            ->fromArray($record, $record['pid'], $record['uid']);

        // Transform slug if there are uniqueness constraints in the eval configuration.
        $evalInfo = GeneralUtility::trimExplode(',', $fieldConfig['eval'], true);
        if (in_array('uniqueInSite', $evalInfo)) {
            return $slugHelper->buildSlugForUniqueInSite($slug, $state);
        } else if (in_array('uniqueInPid', $evalInfo)) {
            return $slugHelper->buildSlugForUniqueInPid($slug, $state);
        } else if (in_array('unique', $evalInfo)) {
            return $slugHelper->buildSlugForUniqueInTable($slug, $state);
        }
        return $slug;
    }
}
