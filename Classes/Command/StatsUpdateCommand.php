<?php

namespace Mittwald\Typo3Forum\Command;

use Mittwald\Typo3Forum\Domain\Model\Forum\Post;
use Mittwald\Typo3Forum\Domain\Model\Forum\Topic;
use Mittwald\Typo3Forum\Domain\Model\User\FrontendUser;
use TYPO3\CMS\Core\Site\Entity\SiteInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatsUpdateCommand extends AbstractDatabaseBasedCommand
{
    protected function executeForSite(SiteInterface $site, InputInterface $input, OutputInterface $output): void
    {
        $deleteQueryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_stats_summary');
        $deleteQueryBuilder
            ->delete('tx_typo3forum_domain_model_stats_summary')
            ->where(
                $deleteQueryBuilder->expr()->eq('pid', $this->storagePage),
                $deleteQueryBuilder->expr()->in('type', [Post::class, Topic::class, FrontendUser::class])
            )
            ->execute()
        ;

        $results = [];

        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_post');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_post', 'post');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere($queryBuilder->expr()->eq('post.pid', $this->storagePage));

        $res = $queryBuilder->execute();
        $row = $res->fetchAssociative();

        $results[Post::class] = (int)$row['counter'];

        $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_forum_topic');
        $queryBuilder->from('tx_typo3forum_domain_model_forum_topic', 'topic');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere($queryBuilder->expr()->eq('topic.pid', $this->storagePage));

        $res = $queryBuilder->execute();
        $row = $res->fetchAssociative();

        $results[Topic::class] = (int)$row['counter'];

        $queryBuilder = $this->getQueryBuilder('fe_users');
        $queryBuilder->from('fe_users', 'users');
        $queryBuilder->addSelectLiteral(
            $queryBuilder->expr()->count('*', 'counter')
        );
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('users.pid', $this->storagePage)
        );

        $res = $queryBuilder->execute();
        $row = $res->fetchAssociative();

        $results[FrontendUser::class] = (int)$row['counter'];

        foreach ($results as $type => $amount) {
            $values = [
                'pid' => $this->storagePage,
                'tstamp' => time(),
                'type' => $type,
                'amount' => (int)$amount,
            ];

            $queryBuilder = $this->getQueryBuilder('tx_typo3forum_domain_model_stats_summary');
            $queryBuilder->insert('tx_typo3forum_domain_model_stats_summary');
            $queryBuilder->values($values);
            $queryBuilder->execute();
        }
    }
}
