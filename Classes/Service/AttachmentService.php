<?php
class Tx_MmForum_Service_AttachmentService implements t3lib_Singleton {

	public function initAttachments(array $attachments){
		/* @var Tx_MmForum_Domain_Model_Forum_Attachment */
		$objAttachments = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

		foreach($attachments AS $attachmentID => $attachment) {
			if($attachment['name'] == '') continue;
			$attachmentObj = new Tx_MmForum_Domain_Model_Forum_Attachment();
			$tmp_name = $_FILES['tx_mmforum_pi1']['tmp_name']['attachments'][$attachmentID];
			$mime_type = mime_content_type($tmp_name);

			//Save in ObjectStorage and in file system
			$attachmentObj->setFilename($attachment['name']);
			$attachmentObj->setRealFilename(sha1($attachment['name'].time()));
			$attachmentObj->setMimeType($mime_type);

			$res = \TYPO3\CMS\Core\Utility\GeneralUtility::upload_copy_move($tmp_name,$attachmentObj->getAbsoluteFilename());
			if($res === true) {
				$objAttachments->attach($attachmentObj);
			}
		}
		return $objAttachments;
	}
}