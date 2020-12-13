<?php
return [
    'frontend' => [
        'typo3forum-preview' => [
            'target' => \Mittwald\Typo3Forum\Middlewares\PreviewMiddleware::class,
            'description' => 'Allows Previewing of Posts',
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ],
            'before' => [
                'typo3/cms-frontend/shortcut-and-mountpoint-redirect'
            ]
        ]
    ]
];
