<?php
namespace Mittwald\Typo3Forum\Service;

use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TagService implements SingletonInterface
{
    protected TagRepository $tagRepository;

    public function __construct(
        TagRepository $tagRepository
    ) {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Converts array of tagUids to an ObjectStorage of Tags
     *
     * @return ObjectStorage<Tag>
     */
    public function hydrateTags(array $tagUids): ObjectStorage
    {
        $tags = new ObjectStorage();

        $tagUids = array_map('intval', array_unique($tagUids));
        foreach ($tagUids as $tagUid) {
            /** @var Tag $tag */
            $tag = $this->tagRepository->findByUid($tagUid);
            if ($tag !== null) {
                $tag->increaseTopicCount();
                $tags->attach($tag);
            }
        }
        return $tags;
    }
}
