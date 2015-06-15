<?php
class Tx_Typo3Forum_Service_AttachmentService implements \TYPO3\CMS\Core\SingletonInterface {


	/**
	 * An instance of the Extbase object manager.
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = NULL;

	/**
	 * Injects an instance of the extbase object manager.
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}


	/**
	 * Converts HTML-array to an object
	 * @param array $attachments
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
	 */
	public function initAttachments(array $attachments){
		/* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Attachment */
		$objAttachments = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		foreach($attachments AS $attachmentID => $attachment) {
			if($attachment['name'] == '') continue;
			$attachmentObj = $this->objectManager->create('\Mittwald\Typo3Forum\Domain\Model\Forum\Attachment');
			$tmp_name = $_FILES['tx_typo3forum_pi1']['tmp_name']['attachments'][$attachmentID];
			$mime_type = mime_content_type($tmp_name);

			//Save in ObjectStorage and in file system
			$attachmentObj->setFilename($attachment['name']);
			$attachmentObj->setRealFilename(sha1($attachment['name'].time()));
			$attachmentObj->setMimeType($mime_type);

			//Create dir if not exists
			$tca = $attachmentObj->getTCAConfig();
			$path = $tca['columns']['real_filename']['config']['uploadfolder'];
			if(!file_exists($path)) {
				mkdir($path,'0777',true);
			}

			//upload file and put in object storage
			$res = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmp_name,$attachmentObj->getAbsoluteFilename());
			if($res === true) {
				$objAttachments->attach($attachmentObj);
			}
		}
		return $objAttachments;
	}

}