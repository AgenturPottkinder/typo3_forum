<?php

namespace Mittwald\Typo3Forum\Command;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

abstract class AbstractDatabaseBasedCommand extends AbstractSiteBasedTypoScriptCommand
{
    protected ConnectionPool $connectionPool;

    public function injectConnectionPool(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    protected function getConnectionPool(): ConnectionPool
    {
        return $this->connectionPool;
    }

    protected function getConnection(string $table): Connection
    {
        return $this->getConnectionPool()->getConnectionForTable($table);
    }

    protected function getQueryBuilder(string $table): QueryBuilder
    {
        return $this->getConnectionPool()->getQueryBuilderForTable($table);
    }
}
