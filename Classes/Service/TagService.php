<?php
namespace Mittwald\Typo3Forum\Service;

use Mittwald\Typo3Forum\Domain\Model\Forum\Tag;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class TagService implements SingletonInterface
{

    /**
     * An instance of the Extbase object manager.
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager = null;

    /**
     * An instance of the tag repository
     * @var \Mittwald\Typo3Forum\Domain\Repository\Forum\TagRepository
     * @inject
     */
    protected $tagRepository;

    /**
     * Converts string of tags to an object
     * @param string $tags
     * @return ObjectStorage
     */
    public function initTags($tags)
    {
        /* @var Tag */
        $objTags = new ObjectStorage();

        $tagArray = array_unique(explode(',', $tags));
        foreach ($tagArray as $tagName) {
            $tagName = ucfirst(trim($tagName));
            if ($tagName === '') {
                continue;
            }
            $searchResult = $this->tagRepository->findTagWithSpecificName($tagName);
            if ($searchResult[0]) {
                $searchResult[0]->increaseTopicCount();
                $objTags->attach($searchResult[0]);
            } else {
                /* @var Tag $tag */
                $tag = $this->objectManager->get(Tag::class);
                $tag->setName($tagName);
                $tag->setCrdate(new \DateTime());
                $tag->increaseTopicCount();
                $objTags->attach($tag);
            }
        }
        return $objTags;
    }
}
