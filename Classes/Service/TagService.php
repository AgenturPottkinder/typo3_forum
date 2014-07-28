<?php
namespace Mittwald\MmForum\Service;

class TagService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;

	/**
	 * An instance of the tag repository
	 * @var \Mittwald\MmForum\Domain\Repository\Forum\TagRepository
	 */
	protected $tagRepository;


	/**
	 * Injects an instance of the extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	/**
	 * Injects an instance of the tag repository
	 * @param \Mittwald\MmForum\Domain\Repository\Forum\TagRepository $tagRepository
	 * @return void
	 */
	public function injectTagRepository(\Mittwald\MmForum\Domain\Repository\Forum\TagRepository $tagRepository) {
		$this->tagRepository = $tagRepository;
	}


	/**
	 * Converts string of tags to an object
	 * @param string $tags
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function initTags($tags) {
		/* @var Tx_MmForum_Domain_Model_Forum_Tag */
		$objTags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		$tagArray = array_unique(explode(',', $tags));
		foreach ($tagArray AS $tagName) {
			$tagName = ucfirst(trim($tagName));
			if($tagName == "") continue;
			$searchResult = $this->tagRepository->findTagWithSpecificName($tagName);
			if($searchResult[0] != false) {
				$searchResult[0]->increaseTopicCount();
				$objTags->attach($searchResult[0]);
			} else {
				/* @var Tx_MmForum_Domain_Model_Forum_Tag $tag */
				$tag = $this->objectManager->create('Mittwald\\MmForum\\Domain\\Model\\Forum\\Tag');
				$tag->setName($tagName);
				$tag->setCrdate(new DateTime());
				$tag->increaseTopicCount();
				$objTags->attach($tag);
			}
		}
		return $objTags;
	}

}