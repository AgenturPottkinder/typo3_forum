<?php

namespace Mittwald\Typo3Forum\Service;


use Mittwald\Typo3Forum\Configuration\ConfigurationBuilder;
use Mittwald\Typo3Forum\Domain\Model\ConfigurableInterface;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

class SettingsHydrator
{
    /**
     * @var ConfigurationBuilder
     */
    private $configurationBuilder;

    public function injectConfigurationBuilder(\Mittwald\Typo3Forum\Configuration\ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;
    }

    public function hydrateSettings(DomainObjectInterface $object)
    {
        if ($object instanceof ConfigurableInterface) {
            $object->injectSettings($this->configurationBuilder);
        }
    }
}