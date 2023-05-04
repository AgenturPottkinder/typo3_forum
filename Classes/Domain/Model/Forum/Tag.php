<?php
namespace Mittwald\Typo3Forum\Domain\Model\Forum;

/*                                                                    - *
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

use DateTime;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Tag extends AbstractEntity
{
    protected string $name = '';
    protected ?Color $color = null;
    protected int $topicCount  = 0;
    protected DateTime $tstamp;
    protected DateTime $crdate;

    /**
     * Creates a new Tag.
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->ensureObjectStorages();
    }

    protected function ensureObjectStorages(): void
    {
        if (!isset($this->tstamp)) {
            $this->tstamp = new DateTime();
        }
        if (!isset($this->crdate)) {
            $this->crdate = new DateTime();
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTstamp(): DateTime
    {
        return $this->tstamp;
    }

    public function getCrdate(): DateTime
    {
        return $this->crdate;
    }

    public function setTstamp(DateTime $tstamp): self
    {
        $this->tstamp = $tstamp;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTopicCount(): int
    {
        return $this->topicCount;
    }

    public function setTopicCount(int $topicCount): self
    {
        $this->topicCount = $topicCount;

        return $this;
    }

    public function increaseTopicCount(int $by = 1): self
    {
        $this->topicCount += $by;

        return $this;
    }

    public function decreaseTopicCount(int $by = 1): self
    {
        return $this->increaseTopicCount(-$by);
    }

    public function getColor(): ?Color
    {
        return $this->color ?? Color::getDefaultColor();
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }
}
