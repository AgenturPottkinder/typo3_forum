<?php
class Tx_MmForum_Service_TagService implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;


	/**
	 * Injects an instance of the extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}


	/**
	 * Converts string of tags to an object
	 * @param string $tags
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function initTags($tags){
		/* @var Tx_MmForum_Domain_Model_Forum_Tag */
		$objTags = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		$tagArray = explode(' ',$tags);
		foreach($tagArray AS $tagName) {
			$tag = $this->objectManager->create('Tx_MmForum_Domain_Model_Forum_Tag');
			$tag->setName($tagName);
			$tag->setCrdate(new DateTime());
			$tag->increaseTopicCount();
			$objTags->attach($tag);
		}
		return $objTags;
	}

}