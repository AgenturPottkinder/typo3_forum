<?php
/**
 *
 * COPYRIGHT NOTICE
 *
 *  (c) 2017 Mittwald CM Service GmbH & Co KG
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published
 *  by the Free Software Foundation; either version 2 of the License,
 *  or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 *
 */

namespace Mittwald\Typo3Forum\Service\Migration;


class AttachmentMigrationService extends AbstractMigrationService
{

    /**
     * @return array
     */
    public function getFieldsDefinition()
    {
        return [
            'uid' => 'uid',
            'pid' => 'pid',
            'file_type' => 'mime_type',
            'file_name' => 'filename',
            'file_path' => 'real_filename',
            'downloads' => 'download_count',
            'post_id' => 'post',
            'tstamp' => 'tstamp',
            'crdate' => 'crdate',
            'deleted' => 'deleted',
            'hidden' => 'hidden',
        ];
    }

    /**
     * @return string
     */
    public function getOldTableName()
    {
        return 'tx_mmforum_attachments';
    }

    /**
     * @return string
     */
    public function getNewTableName()
    {
        return 'tx_typo3forum_domain_model_forum_attachment';
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return 'ATTACHMENTS';
    }
}