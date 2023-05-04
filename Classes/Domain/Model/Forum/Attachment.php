<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

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

use Mittwald\Typo3Forum\Domain\Model\ConfigurableEntityTrait;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Attachment extends AbstractEntity implements ConfigurableInterface
{
    use ConfigurableEntityTrait;

    /**
     * The file this attachment represents.
     * @var ObjectStorage<FileReference> $referencedFiles
     */
    protected ObjectStorage $referencedFiles;
    protected ?Post $post = null;
    protected int $downloadCount = 0;
    protected string $name = '';

    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->referencedFiles = GeneralUtility::makeInstance(ObjectStorage::class);
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getReferencedFiles(): ObjectStorage
    {
        return $this->referencedFiles;
    }

    public function getFileReference(): ?FileReference
    {
        return $this->referencedFiles->offsetExists(0) ? $this->referencedFiles->offsetGet(0) : null;
    }

    public function setFileReference(FileReference $fileReference): self
    {
        $this->referencedFiles = GeneralUtility::makeInstance(ObjectStorage::class);
        $this->referencedFiles->attach($fileReference);

        return $this;
    }

    public function getDownloadCount(): int
    {
        return $this->downloadCount;
    }

    /**
     * Increases the download counter by 1.
     */
    public function increaseDownloadCount(): self
    {
        $this->downloadCount++;
        return $this;
    }

    /**
     * Gets the allowed mime types.
     */
    public function getAllowedMimeTypes(): array
    {
        $mime_types = explode(',', $this->getSettings()['attachment']['allowedMimeTypes']);
        if (empty($mime_types)) {
            $res = ['text/plain'];
        } else {
            foreach ($mime_types as $mime_type) {
                $res[] = trim($mime_type);
            }
        }

        return $res;
    }

    /**
     * Gets the allowed max size of a attachment.
     */
    public function getAllowedMaxSize(): int
    {
        if ($this->getSettings()['attachment']['allowedSizeInByte'] == false) {
            return 4194304;
        }
        return (int)$this->getSettings()['attachment']['allowedSizeInByte'];
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
