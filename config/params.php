<?php

return [
    'adminEmail' => 'support@validbook.org',
    'localization' => require(dirname(__DIR__) . '/messages/en/main.php'),
    'allowedDomains' => [
        'http://localhost:3000',
        'https://validbook.org',
        'http://api-test.validbook.org',
        'https://api.validbook.org',
        'http://drive-futurama11001111.validbook.org',
        'http://54.214.3.164',
        'http://futurama1x.validbook.org',
        'http://api-futurama1x.validbook.org',
        'http://localhost:8080'
    ],
    'defaultAvatarUrl32' => getenv('DEFAULT_AVATAR_URL_32'),
    'defaultAvatarUrl48' => getenv('DEFAULT_AVATAR_URL_48'),
    'defaultAvatarUrl100' => getenv('DEFAULT_AVATAR_URL_100'),
    'defaultAvatarUrl230' => getenv('DEFAULT_AVATAR_URL_230'),

    'itemsPerPage' => 10,
    'documentation_url' => 'https://github.com/Drabiv/Validbook-Backend/blob/development/web/documentation/',
    'defaultUserCoverColor' => getenv('DEFAULT_COVER_USER'),
    'defaultBookCoverColor' => getenv('DEFAULT_COVER_BOOK'),
    'apiDomain' => getenv('API_DOMAIN'),
    'siteUrl' => getenv('SITE_URL'),
    'environment' => getenv('ENVIRONMENT'),
    'closedDocumentIcon' => getenv('DEFAULT_COVER_DOCUMENT'),
    'validbookVCId' => 'did:vb:'
];
