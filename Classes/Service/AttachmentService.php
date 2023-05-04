<?php

namespace Mittwald\Typo3Forum\Service;

use Mittwald\Typo3Forum\Domain\Model\Forum\Attachment;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\FileReference as CoreFileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\ResourceStorage;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class AttachmentService implements SingletonInterface
{
    protected $storage;

    public function __construct(
        ResourceFactory $resourceFactory
    ) {
        $this->storage = $resourceFactory->getDefaultStorage();
    }

    /**
     * Converts HTML-array to an object
     * @param array $attachments
     * @return ObjectStorage
     */
    public function initAttachments(array $uploadedAttachments)
    {
        /* @var \Mittwald\Typo3Forum\Domain\Model\Forum\Attachment */
        $attachmentStorage = new ObjectStorage();

        foreach ($uploadedAttachments as $attachmentData) {
            if (!isset($attachmentData['name']) || $attachmentData['name'] == '') {
                continue;
            }

            // Build extbase file reference object to the uploaded file.
            // TODO: Figure out where to grab the folder name from
            $folderIdentifier = 'frontend_uploads';
            if (!$this->storage->hasFolder($folderIdentifier)) {
                $this->storage->createFolder($folderIdentifier);
            }

            $falFile = $this->storage->addUploadedFile(
                $attachmentData,
                $this->storage->getFolder($folderIdentifier),
                sha1($attachmentData['name'] . time()) . '.' . end(explode('.', $attachmentData['name'])),
                DuplicationBehavior::REPLACE
            );

            $falFileReference = GeneralUtility::makeInstance(
                CoreFileReference::class,
                [
                    'uid_local' => (int)$falFile->getProperty('uid'),
                ]
            );

            $extbaseFileReference = GeneralUtility::makeInstance(ExtbaseFileReference::class);
            $extbaseFileReference->setOriginalResource(
                $falFileReference
            );

            // Hydrate Attachment
            /** @var Attachment $attachment */
            $attachment = GeneralUtility::makeInstance(Attachment::class);

            $attachment
                ->setFileReference($extbaseFileReference)
                ->setName($attachmentData['name'])
            ;

            $attachmentStorage->attach($attachment);
        }

        return $attachmentStorage;
    }
}
