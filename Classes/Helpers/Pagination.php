<?php

namespace Mittwald\Typo3Forum\Helpers;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class Pagination
{
    const DEFAULT_CONFIGURATION = [
        'itemsPerPage' => 10,
        'pageLookAround' => 5,
        'reverseOrder' => false,
        'useEdgeSpacers' => true,
    ];

    protected $objects;

    protected $currentPage;
    protected $itemsPerPage;
    protected $pageLookAround;
    protected $reverseOrder;
    protected $useEdgeSpacers;


    public function __construct(array $objects, array $configuration = [])
    {
        $this->setObjects($objects);
        $this->setConfigurationArray($configuration);
    }

    public function setConfigurationArray(array $configuration): self
    {
        $mergedConfig = static::DEFAULT_CONFIGURATION;
        ArrayUtility::mergeRecursiveWithOverrule($mergedConfig, $configuration, false);

        foreach ($mergedConfig as $configName => $configValue) {
            $this->{'set' . ucfirst($configName)}($configValue);
        }

        return $this;
    }


    public function getOffset(): int
    {
        return ($this->getCurrentPage() - 1) * $this->getItemsPerPage();
    }

    public function getMaxPage(): int
    {
        return ceil($this->getObjectCount() / $this->getItemsPerPage());
    }

    public function getListOfPagesToDisplay(): array
    {
        $lookAround = $this->getPageLookAround();
        $curPage = $this->getCurrentPage();
        $maxPage = $this->getMaxPage();

        // Move curPage away from the edges for the expansive lookAround.
        foreach (['left' => 1, 'right' => $maxPage] as $side => $stopper) {
            $distance = abs($curPage - $stopper);
            if ($distance < $lookAround) {
                $multiplier = $side === 'left' ? 1 : -1;

                $curPage += ($lookAround - $distance) * $multiplier;
            }
        }

        $start = max(1, $curPage - $lookAround);
        $stop = min($maxPage, $curPage + $lookAround);

        $range = range($start, $stop);

        // Add edge pages, to jump to the start or end. Also add spacers if desired (page 0 as placeholder).
        if (!in_array(1, $range)) {
            if ($this->getUseEdgeSpacers() && $start > 2) {
                if ($start === 3) {
                    array_unshift($range, 2);
                }
                else {
                    array_unshift($range, 0);
                }
            }

            array_unshift($range, 1);
        }

        if (!in_array($this->getMaxPage(), $range)) {
            if ($this->getUseEdgeSpacers() && $stop < $maxPage - 1) {
                if ($stop === $maxPage - 2) {
                    array_push($range, $maxPage - 1);
                }
                else {
                    array_push($range, 0);
                }
            }

            array_push($range, $this->getMaxPage());
        }

        return $range;
    }

    public function fetchPage(): array
    {
        $objects = $this->getObjects();
        if ($this->getReverseOrder()) {
            $objects = array_reverse($objects);
        }

        return array_slice($objects, $this->getOffset(), $this->getItemsPerPage());
    }

    ///////////////////
    // Setters and getters

    public function setObjects(array $objects): self
    {
        $this->objects = $objects;
        return $this;
    }

    public function getObjects(): array
    {
        return $this->objects;
    }


    public function setCurrentPage(int $value): self
    {
        $value = max(1, min($this->getMaxPage(), $value));

        $this->currentPage = $value;
        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getObjectCount(): int
    {
        return count($this->getObjects());
    }


    public function setItemsPerPage(int $value): self
    {
        $this->itemsPerPage = $value;
        return $this;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }


    public function setPageLookAround(int $value): self
    {
        $this->pageLookAround = $value;
        return $this;
    }

    public function getPageLookAround(): int
    {
        return $this->pageLookAround;
    }


    public function setReverseOrder(bool $value): self
    {
        $this->reverseOrder = $value;
        return $this;
    }

    public function getReverseOrder(): bool
    {
        return $this->reverseOrder;
    }


    public function setUseEdgeSpacers(bool $value): self
    {
        $this->useEdgeSpacers = $value;
        return $this;
    }

    public function getUseEdgeSpacers(): bool
    {
        return $this->useEdgeSpacers;
    }
}
