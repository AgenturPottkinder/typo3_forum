<?php
/*                                                                      *
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

namespace Mittwald\Typo3Forum\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Expression\ExpressionBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\AbstractUpdate;

/**
 * Update wizard to migrate anonymous posts without an authorName to have 'Anonymous' as authorName instead and authorNames with less than 3 chars to have
 * 'Anonymous: ' prepended.
 * This is necessary to avoid validation issues for old posts - the authorName validation was disabled for a longer time and has been re-enabled.
 */
class PostsWithoutAuthorNameUpdate extends AbstractUpdate {

	/**
	 * @var string
	 */
	protected $title = '[typo3_forum]: Migrate anonymous posts to have a valid author_name';

	/**
	 * Checks whether updates are required.
	 *
	 * @param string &$description The description for the update
	 * @return bool Whether an update is required (TRUE) or not (FALSE)
	 */
	public function checkForUpdate(&$description) {

		if ($this->isWizardDone()) {
			return FALSE;
		}

		$description = 'Migrate anonymous posts to have a valid author name with three or more characters by setting "Anonymous" for empty author name and
		 prepending "Anonymous: " to author name if consisting of one or two characters.';

		return $this->hasPostsToUpdate();
	}

	/**
	 * Performs the accordant updates.
	 *
	 * @param array &$databaseQueries Queries done in this update
	 * @param string &$customMessage Custom message
	 * @return bool Whether everything went smoothly or not
	 */
	public function performUpdate(array &$databaseQueries, &$customMessage): bool {

		$connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

		// Update empty authorNames
		$queryBuilder = $connectionPool->getQueryBuilderForTable('tx_typo3forum_domain_model_forum_post');
		$queryBuilder->getRestrictions()->removeAll();

		$queryBuilder
			->update('tx_typo3forum_domain_model_forum_post')
			->where($queryBuilder->expr()->eq('author_name', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)))
			->andWhere($queryBuilder->expr()->eq('author', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)))
			->set('author_name', 'Anonymous')
			->execute();
		$databaseQueries[] = $queryBuilder->getSQL();


		// Update short authorNames (fetching and updating is necessary as CONCAT('Anonymous: ', "authorName")) does not work in PostgreSQL
		$connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_typo3forum_domain_model_forum_post');

		$queryBuilder = $connectionPool->getQueryBuilderForTable('tx_typo3forum_domain_model_forum_post');
		$queryBuilder->getRestrictions()->removeAll();

		$selectShortAuthorNameStatement = $queryBuilder
			->select('uid', 'author_name')
			->from('tx_typo3forum_domain_model_forum_post')
			->where(
				$queryBuilder->expr()->comparison(
					$queryBuilder->expr()->length('author_name'),
					ExpressionBuilder::LT,
					$queryBuilder->createNamedParameter(3, Connection::PARAM_INT)
				)
			)
			->andWhere($queryBuilder->expr()->neq('author_name', $queryBuilder->createNamedParameter('', Connection::PARAM_STR)))
			->andWhere($queryBuilder->expr()->eq('author', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)))
			->execute();

		while ($post = $selectShortAuthorNameStatement->fetch()) {
			$connection->update(
				'tx_typo3forum_domain_model_forum_post',
				['author_name' => 'Anonymous: ' . $post['author_name']],
				['uid' => (int)$post['uid']],
				[Connection::PARAM_STR, Connection::PARAM_INT]
			);
		}

		$updateSuccessful = !$this->hasPostsToUpdate();

		if ($updateSuccessful) {
			$this->markWizardAsDone();
		}

		return $updateSuccessful;
	}

	/**
	 * Fetch the status whether there are posts to update
	 *
	 * @return bool
	 */
	private function hasPostsToUpdate() {
		$queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_typo3forum_domain_model_forum_post');
		$queryBuilder->getRestrictions()->removeAll();

		$numberOfPostsToUpdate = $queryBuilder
			->count('*')
			->from('tx_typo3forum_domain_model_forum_post')
			->where(
				$queryBuilder->expr()->comparison(
					$queryBuilder->expr()->length('author_name'),
					ExpressionBuilder::LT,
					$queryBuilder->createNamedParameter(3, Connection::PARAM_INT)
				)
			)
			->andWhere($queryBuilder->expr()->eq('author', $queryBuilder->createNamedParameter(0, Connection::PARAM_INT)))
			->execute()
			->fetchColumn();

		return (int)$numberOfPostsToUpdate > 0;
	}

}
