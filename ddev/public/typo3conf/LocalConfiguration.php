<?php
return [
    'BE' => [
        'debug' => true,
        'explicitADmode' => 'explicitAllow',
        'installToolPassword' => '$argon2i$v=19$m=65536,t=16,p=1$QWVZd1MxYzBCZTVPL2FaZg$MkJ/7XRLiv50SeKvqdOg6XK0aRifACXW+FQGYsHuTLM',
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'DB' => [
        'Connections' => [
            'Default' => [
                'charset' => 'utf8',
                'driver' => 'mysqli',
            ],
        ],
    ],
    'EXTENSIONS' => [
        'backend' => [
            'backendFavicon' => '',
            'backendLogo' => '',
            'loginBackgroundImage' => '',
            'loginFootnote' => '',
            'loginHighlightColor' => '',
            'loginLogo' => '',
            'loginLogoAlt' => '',
        ],
        'bootstrap_package' => [
            'disableCssProcessing' => '0',
            'disableGoogleFontCaching' => '0',
            'disablePageTsBackendLayouts' => '0',
            'disablePageTsContentElements' => '0',
            'disablePageTsRTE' => '0',
            'disablePageTsTCADefaults' => '0',
            'disablePageTsTCEFORM' => '0',
            'disablePageTsTCEMAIN' => '0',
        ],
        'extensionmanager' => [
            'automaticInstallation' => '1',
            'offlineMode' => '0',
        ],
        'femanager' => [
            'disableLog' => '1',
            'disableModule' => '0',
            'enableConfirmationModule' => '0',
            'overrideFeUserCountryFieldWithSelect' => '1',
        ],
        'scheduler' => [
            'maxLifetime' => '1440',
            'showSampleTasks' => '0',
        ],
        'static_info_tables' => [
            'constraints' => [
                'depends' => [
                    'extbase' => '11.5.0-11.5.99',
                    'extensionmanager' => '11.5.0-11.5.99',
                    'typo3' => '11.5.0-11.5.99',
                ],
            ],
            'enableManager' => '0',
            'entities' => [
                'Country',
                'CountryZone',
                'Currency',
                'Language',
                'Territory',
            ],
            'tables' => [
                'static_countries' => [
                    'isocode_field' => [
                        'cn_iso_##',
                    ],
                    'label_fields' => [
                        'cn_short_##' => [
                            'mapOnProperty' => 'shortName##',
                        ],
                        'cn_short_en' => [
                            'mapOnProperty' => 'shortNameEn',
                        ],
                    ],
                ],
                'static_country_zones' => [
                    'isocode_field' => [
                        'zn_code',
                        'zn_country_iso_##',
                    ],
                    'label_fields' => [
                        'zn_name_##' => [
                            'mapOnProperty' => 'name##',
                        ],
                        'zn_name_local' => [
                            'mapOnProperty' => 'localName',
                        ],
                    ],
                ],
                'static_currencies' => [
                    'isocode_field' => [
                        'cu_iso_##',
                    ],
                    'label_fields' => [
                        'cu_name_##' => [
                            'mapOnProperty' => 'name##',
                        ],
                        'cu_name_en' => [
                            'mapOnProperty' => 'nameEn',
                        ],
                    ],
                ],
                'static_languages' => [
                    'isocode_field' => [
                        'lg_iso_##',
                        'lg_country_iso_##',
                    ],
                    'label_fields' => [
                        'lg_name_##' => [
                            'mapOnProperty' => 'name##',
                        ],
                        'lg_name_en' => [
                            'mapOnProperty' => 'nameEn',
                        ],
                    ],
                ],
                'static_territories' => [
                    'isocode_field' => [
                        'tr_iso_##',
                    ],
                    'label_fields' => [
                        'tr_name_##' => [
                            'mapOnProperty' => 'name##',
                        ],
                        'tr_name_en' => [
                            'mapOnProperty' => 'nameEn',
                        ],
                    ],
                ],
            ],
            'version' => '11.5.3',
        ],
    ],
    'FE' => [
        'cacheHash' => [
            'enforceValidation' => true,
        ],
        'debug' => false,
        'disableNoCacheParameter' => true,
        'passwordHashing' => [
            'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
            'options' => [],
        ],
    ],
    'GFX' => [
        'processor' => 'GraphicsMagick',
        'processor_allowTemporaryMasksAsPng' => false,
        'processor_colorspace' => 'RGB',
        'processor_effects' => false,
        'processor_enabled' => true,
        'processor_path' => '/usr/bin/',
        'processor_path_lzw' => '/usr/bin/',
    ],
    'LOG' => [
        'TYPO3' => [
            'CMS' => [
                'deprecations' => [
                    'writerConfiguration' => [
                        'notice' => [
                            'TYPO3\CMS\Core\Log\Writer\FileWriter' => [
                                'disabled' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'MAIL' => [
        'transport' => 'sendmail',
        'transport_sendmail_command' => '/usr/local/bin/mailhog sendmail test@example.org --smtp-addr 127.0.0.1:1025',
        'transport_smtp_encrypt' => '',
        'transport_smtp_password' => '',
        'transport_smtp_server' => '',
        'transport_smtp_username' => '',
    ],
    'SYS' => [
        'caching' => [
            'cacheConfigurations' => [
                'hash' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                ],
                'imagesizes' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pages' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'pagesection' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
                'rootline' => [
                    'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
                    'options' => [
                        'compression' => true,
                    ],
                ],
            ],
        ],
        'devIPmask' => '',
        'displayErrors' => 0,
        'encryptionKey' => '0cf348ba04710010efbba71e15f963414a60b5040383201d2f72e566d302a4fc07ea5dd0d4c9bb7118eff60162c275bb',
        'exceptionalErrors' => 4096,
        'features' => [
            'yamlImportsFollowDeclarationOrder' => true,
        ],
        'sitename' => 'TYPO3 Forum Development',
        'systemMaintainers' => [
            1,
        ],
    ],
];
