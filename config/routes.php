<?php

return [
    //sitemap.xml
    //to make sitemap working, lets uncomment the line below. TO DO: sitemap must be only at production
    //['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],

    '/' => 'site/index',
    '/jwks-keys' => 'site/json-keys',

    //Provider Info For OpenIdClient
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/well-known' => 'v1/well-known'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET openid-configuration' => 'provider-info',
            'GET services-info' => 'services-info'
        ],
    ],
    'OPTIONS well-known/openid-configuration' => 'v1/well-known/options',
    'OPTIONS well-known/services-info' => 'v1/well-known/options',

    //User Auth
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/auth' => '/v1/auth'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            ///'POST connect' => 'connect',
            'POST login' => 'login',
            'GET message-for-sig' => 'get-random',
            'POST authorize-token' => 'authorize-token',
            // 'POST recover-password' => 'recover-password',
            //  'POST submit-recover' => 'submit-recover'
        ],
    ],
    'OPTIONS v1/auth/login' => 'v1/auth/options',
    'OPTIONS v1/auth/message-for-sig' => 'v1/auth/options',
    'OPTIONS v1/auth/authorize-token' => 'v1/auth/options',
    // 'OPTIONS v1/auth/connect' => 'v1/auth/options',
    //  'OPTIONS v1/auth/recover-password' => 'v1/auth/options',
    // 'OPTIONS v1/auth/submit-recover' => 'v1/auth/options',

    'POST v1/registration' => 'v1/registration/index',
    'OPTIONS v1/registration' => 'v1/registration/options',

    //Engagment
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/engagment' => 'v1/engagment'
        ],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST profile' => 'profile',
        ],
    ],
    'OPTIONS v1/engagment/profile' => 'v1/engagment/options',

    //User
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'patterns' => [
            '' => 'options',
        ],
        'extraPatterns' => [
            'GET <userSlug>/channels' => 'channels',
            'GET <userSlug>/stories' => 'stories',
            'GET <userSlug>/profile' => 'profile',
            'GET <userSlug>/original-avatar' => 'original-avatar',
            'GET <userSlug>/requested-user' => 'requested-user',
            'GET authorized-user' => 'authorized-user',
            'POST change-password' => 'change-password',
            'POST deactivate' => 'deactivate',
            'POST logout' => 'logout',
            'POST authorize-client' => 'authorize-client',
            'PATCH change-quiet-mode' => 'change-calm-mode',
        ],
    ],
    'OPTIONS v1/users/change-password' => 'v1/user/options',
    'OPTIONS v1/users/deactivate' => 'v1/user/options',
    'OPTIONS v1/users/logout' => 'v1/user/options',
    'OPTIONS v1/users/authorize-client' => 'v1/user/options',
    'OPTIONS v1/users/change-quiet-mode' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/profile' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/original-avatar' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/requested-user' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/authorized-user' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/stories' => 'v1/user/options',
    'OPTIONS v1/users/<userSlug>/channels' => 'v1/user/options',

    //Book
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/book',
        'patterns' => [
            'POST' => 'create',
            'POST <book_slug>' => 'move',
            'DELETE <book_slug>' => 'delete',
            'PATCH <book_slug>' => 'update',
            'GET' => 'index',
            'GET <book_slug>' => 'view',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET values-for-options' => 'values-for-options',
            'GET tree' => 'tree',
            'PATCH <book_slug>/recover' => 'recover',
        ],
    ],
    'OPTIONS v1/books/move' => 'v1/book/options',
    'OPTIONS v1/books/tree' => 'v1/book/options',
    'OPTIONS v1/books/<book_slug>' => 'v1/book/options',
    'OPTIONS v1/books/<book_slug>/recover' => 'v1/book/options',
    'OPTIONS v1/books/values-for-options' => 'v1/book/options',

    //Box
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/box',
        'patterns' => [
            'GET' => 'index',
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            'PATCH <id>' => 'update',
            'GET <box_slug>' => 'view',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET values-for-options' => 'values-for-options',
            'PATCH <id>/recover' => 'recover',
            'PATCH <id>/move' => 'move',
        ],
    ],
    'OPTIONS v1/boxes' => 'v1/box/options',
    'OPTIONS v1/boxes/<id/>move' => 'v1/box/options',
    'OPTIONS v1/boxes/<box_slug>' => 'v1/box/options',
    'OPTIONS v1/boxes/values-for-options' => 'v1/box/options',
    'OPTIONS v1/boxes/<id>' => 'v1/box/options',
    'OPTIONS v1/boxes/<id>/recover' => 'v1/box/options',


    //KnockOnBook
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/knock-book',
        'patterns' => [
            'GET' => 'index',
            'GET <id>' => 'view',
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST <id>/knock' => 'knock-on-book',
            'PATCH <knockId>/submit' => 'submit',
            'PATCH <knockId>/ignore' => 'ignore',

        ],
    ],
    'OPTIONS v1/knock-books/<id>/knock' => 'v1/knock-book/options',
    'OPTIONS v1/knock-books/<id>' => 'v1/knock-book/options',
    'OPTIONS v1/knock-books>' => 'v1/knock-book/options',
    'OPTIONS v1/knock-books/<knockId>/ignore' => 'v1/knock-book/options',
    'OPTIONS v1/knock-books/<knockId>/submit' => 'v1/knock-book/options',

    //Comment
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/comment',
        'patterns' => [
            'GET <id>' => 'view',
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            'PATCH <id>' => 'update',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET story' => 'story',
        ],
    ],
    'OPTIONS v1/comments' => 'v1/comment/options',
    'OPTIONS v1/comments/<id>' => 'v1/comment/options',
    'OPTIONS v1/comments/story' => 'v1/comment/options',

    //Conversation
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/conversation',
        'except' => ['update'],
        'patterns' => [
            'GET' => 'index',
            'GET <id>' => 'view',
            'DELETE <id>' => 'delete-conversation',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET by-users' => 'conversation-by-user',
            'POST read/<id>' => 'mark-read',
            'PATCH add-member/<id>' => 'add-member-to-group',
            'PATCH change-delete-time/<id>' => 'change-hours',
            'PATCH left/<id>' => 'left-conversation',
            'POST read-all' => 'mark-read-all',
            'POST seen-all' => 'mark-seen-all',
        ],
    ],
    'OPTIONS v1/conversations/<id>' => 'v1/conversation/options',
    'OPTIONS v1/conversations/left/<id>' => 'v1/conversation/options',
    'OPTIONS v1/conversations/change-delete-time/<id>' => 'v1/conversation/options',
    'OPTIONS v1/conversations/add-member/<id>' => 'v1/conversation/options',
    'OPTIONS v1/conversations/read/<id>' => 'v1/conversation/options',
    'OPTIONS v1/conversations/seen-all' => 'v1/conversation/options',
    'OPTIONS v1/conversations/read-all' => 'v1/conversation/options',
    'OPTIONS v1/conversations' => 'v1/conversation/options',
    'OPTIONS v1/conversations/by-users' => 'v1/conversation/options',

    //Document
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/document',
        'patterns' => [
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            'PATCH <id>' => 'update',
            'GET <id>' => 'view',
            '' => 'options',
        ],
        'extraPatterns' => [
            'PATCH <id>/recover' => 'recover',
            'PATCH <id>/remove' => 'remove',
            'PATCH <id>/save-sig' => 'signature',
            'PATCH <id>/move' => 'move',
            'PATCH <id>/copy' => 'copy',
            'PATCH <id>/file-attach' => 'file-attach',
            'DELETE <id>/file-remove' => 'file-remove',
            'PATCH <id>/open-for-sig' => 'open-for-sig',
            'GET <id>/download' => 'download',
            'GET <id>/message-for-sig' => 'message-for-sig',
            'POST upload' => 'upload'
        ],
    ],
    'OPTIONS v1/documents' => 'v1/document/options',
    'OPTIONS v1/documents/<id>' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/open-for-sig' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/recover' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/remove' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/save-sig' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/move' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/copy' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/file-attach' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/file-remove' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/download' => 'v1/document/options',
    'OPTIONS v1/documents/<id>/message-for-sig' => 'v1/document/options',
    'OPTIONS v1/documents/upload' => 'v1/document/options',


    //Card
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/card' => '/v1/card'
        ],
        'except' => ['delete', 'create', 'update'],
        'patterns' => [
            'GET <address>' => 'view',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET <address>/full-card' => 'full',
            'GET <address>/graph-data' => 'get-graph',
            'GET <address>/graph-node' => 'get-graph-node',
            'GET <address>/human-claim-supports' => 'get-human-claim-supports',
            'GET <address>/human-claim-message' => 'human-claim-message',
            'GET <address>/support-human-claim-message' => 'support-human-claim-message',
            'GET <address>/message-for-revoke' => 'message-for-revoke',
            'PATCH <address>/add-property' => 'add-digital-property',
            'PATCH <address>/human-claim-sig' => 'human-claim-sig',
            'PATCH <address>/support-human-claim' => 'support-human-claim',
            'PATCH <address>/revoke' => 'revoke-card',
            'PATCH <address>/revoke-support-signature' => 'revoke-support-signature',
            'PATCH <address>/designate-revoke-addresses' => 'designate-revoke',
            'PATCH <address>/add-digital-property' => 'add-digital-property',
            'PATCH <address>/proof-digital-property' => 'proof-digital-property',
            'PATCH <address>/revoke-digital-property' => 'revoke-digital-property',
        ],
    ],
    'OPTIONS v1/card' => 'v1/card/options',
    'OPTIONS v1/card/<address>/human-claim-sig' => 'v1/card/options',
    'OPTIONS v1/card/<address>/human-claim-message' => 'v1/card/options',
    'OPTIONS v1/card/<address>/graph-data' => 'v1/card/options',
    'OPTIONS v1/card/<address>/graph-node' => 'v1/card/options',
    'OPTIONS v1/card/<address>/human-claim-supports' => 'v1/card/options',
    'OPTIONS v1/card/<address>/full-card' => 'v1/card/options',
    'OPTIONS v1/card/<address>' => 'v1/card/options',
    'OPTIONS v1/card/<address>/add-property' => 'v1/card/options',
    'OPTIONS v1/card/<address>/support-human-claim' => 'v1/card/options',
    'OPTIONS v1/card/<address>/support-human-claim-message' => 'v1/card/options',
    'OPTIONS v1/card/<address>/revoke' => 'v1/card/options',
    'OPTIONS v1/card/<address>/revoke-support-signature' => 'v1/card/options',
    'OPTIONS v1/card/<address>/message-for-revoke' => 'v1/card/options',
    'OPTIONS v1/card/<address>/designate-revoke-addresses' => 'v1/card/options',
    'OPTIONS v1/card/<address>/add-digital-property' => 'v1/card/options',
    'OPTIONS v1/card/<address>/proof-digital-property' => 'v1/card/options',
    'OPTIONS v1/card/<address>/revoke-digital-property' => 'v1/card/options',

    //Message
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/message',
        'except' => ['update', 'view', 'index'],
        'patterns' => [
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET by-receiver' => 'messages-by-receiver',
            'GET conversations' => 'conversation',
        ],
    ],

    'OPTIONS v1/messages/<id>' => 'v1/message/options',
    'OPTIONS v1/messages' => 'v1/message/options',
    'OPTIONS v1/messages/by-receiver' => 'v1/message/options',
    'OPTIONS v1/messages/conversations' => 'v1/message/options',

    //Channel
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/channel',
        'patterns' => [
            'GET' => 'index',
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            'PATCH <id>' => 'update',
            'GET <channel_slug>' => 'view',
            '' => 'options',
        ],
        'extraPatterns' => [
            'GET <channelId>/following-people' => 'following-list',
            'GET <channelId>/following-books' => 'following-books',
        ],
    ],
    'OPTIONS v1/channels/<id>' => 'v1/channel/options',
    'OPTIONS v1/channels/<channel_slug>' => 'v1/channel/options',
    'OPTIONS v1/channels/<channelId>/following-people' => 'v1/channel/options',
    'OPTIONS v1/channels/<channelId>/following-books' => 'v1/channel/options',
    'OPTIONS v1/channels' => 'v1/channel/options',

    //Story
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/story',
        'patterns' => [
            'POST' => 'create',
            'DELETE <id>' => 'delete',
            'PATCH <id>' => 'update',
            'GET <id>' => 'view',
            '' => 'options',
        ],
        'extraPatterns' => [
            'POST <id>/update-visibility' => 'update-visibility',
            'POST <id>/pin' => 'pin',
            'POST <id>/relog' => 'relog',
            'GET books-visibility' => 'visibility-books',
            'GET <id>/books-tree' => 'books-tree-relog'
        ],
    ],
    'OPTIONS v1/stories/<id>/relog' => 'v1/story/options',
    'OPTIONS v1/stories/<id>/update-visibility' => 'v1/story/options',
    'OPTIONS v1/stories/<id>' => 'v1/story/options',
    'OPTIONS v1/stories/<id>/pin' => 'v1/story/options',
    'OPTIONS v1/stories' => 'v1/story/options',
    'OPTIONS v1/stories/<id>/books-tree' => 'v1/story/options',

    //Follow
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/follow',
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST simple-user-follow' => 'simple-follow-user',
            'POST simple-user-unfollow' => 'simple-unfollow-user',
            'POST edit-follow' => 'follow-diff',
            'POST simple-book-follow' => 'simple-follow-book',
            'POST simple-book-unfollow' => 'simple-unfollow-book',
            'POST edit-follow-book' => 'follow-diff-book',
            'GET who-to-follow' => 'who-to-follow',
            'GET <userId>/popup' => 'get-follow-popup',
            'GET <bookId>/book-popup' => 'get-follow-book-popup',
        ],
    ],
    'OPTIONS v1/follows/simple-user-follow' => 'v1/follow/options',
    'OPTIONS v1/follows' => 'v1/follow/options',
    'OPTIONS v1/follows/<userId>' => 'v1/follow/options',
    'OPTIONS v1/follows/<userId>/popup' => 'v1/follow/options',
    'OPTIONS v1/follows/<userId>/book-popup' => 'v1/follow/options',
    'OPTIONS v1/follows/who-to-follow' => 'v1/follow/options',
    'OPTIONS v1/follows/simple-user-unfollow' => 'v1/follow/options',
    'OPTIONS v1/follows/simple-book-follow' => 'v1/follow/options',
    'OPTIONS v1/follows/simple-book-unfollow' => 'v1/follow/options',
    'OPTIONS v1/follows/edit-follow' => 'v1/follow/options',
    'OPTIONS v1/follows/edit-follow-book' => 'v1/follow/options',

    //People
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/people' => '/v1/people'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET suggested' => 'suggested',
            'GET following' => 'following',
            'GET followers' => 'followers',
            'GET block' => 'block',
            'GET all' => 'all-people',
        ],
    ],
    'OPTIONS v1/people/suggested' => 'v1/people/options',
    'OPTIONS v1/people/following' => 'v1/people/options',
    'OPTIONS v1/people/followers' => 'v1/people/options',
    'OPTIONS v1/people/block' => 'v1/people/options',
    'OPTIONS v1/people/all' => 'v1/people/options',

    //Like
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/like' => '/v1/like'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST story' => 'story',
            'POST photo' => 'photo',
        ],
    ],
    'OPTIONS v1/like/story' => 'v1/like/options',
    'OPTIONS v1/like/photo' => 'v1/like/options',

    //Search
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/search' => '/v1/search'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            'GET' => 'index',
            '' => 'options',
        ],
        'extraPatterns' => [
            'GET books' => 'books',
            'GET stories' => 'stories',
            'GET users' => 'users',
            'GET all' => 'all-tab',
            'GET documents' => 'documents',
        ],
    ],
    'OPTIONS v1/search/all' => 'v1/search/options',
    'OPTIONS v1/search/books' => 'v1/search/options',
    'OPTIONS v1/search/stories' => 'v1/search/options',
    'OPTIONS v1/search/users' => 'v1/search/options',
    'OPTIONS v1/search/documents' => 'v1/search/options',


    //Upload
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/upload' => '/v1/upload'
        ],
        'except' => ['delete', 'create', 'update', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST avatar' => 'avatar',
            'POST user-cover' => 'user-cover',
            'POST book-cover' => 'book-cover',
            'POST story' => 'story-image',
        ],
    ],
    'OPTIONS v1/upload/avatar' => 'v1/upload/options',
    'OPTIONS v1/upload/user-cover' => 'v1/upload/options',
    'OPTIONS v1/upload/book-cover' => 'v1/upload/options',
    'OPTIONS v1/upload/story' => 'v1/upload/options',

    //Notification
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/notification',
        'except' => ['delete', 'update', 'view', 'create'],
        'patterns' => [
            'GET' => 'index',
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET settings' => 'view-settings',
            'GET count-new' => 'count-new-notifications',
            'POST read/<id>' => 'mark-read',
            'POST settings' => 'update-settings',
            'POST read-all' => 'mark-read-all',
            'POST seen-all' => 'mark-seen-all',
        ]
    ],
    'OPTIONS v1/notifications/read/<id>' => 'v1/notification/options',
    'OPTIONS v1/notifications/count-new' => 'v1/notification/options',
    'OPTIONS v1/notifications/settings' => 'v1/notification/options',
    'OPTIONS v1/notifications/seen-all' => 'v1/notification/options',
    'OPTIONS v1/notifications/read-all' => 'v1/notification/options',

    //Photo
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/photo',
        'except' => ['update', 'index', 'create'],
        'patterns' => [
            'GET' => 'view',
            'DELETE <id>' => 'delete',
            '' => 'options',
        ],
        'extraPatterns' => [
            'GET cover' => 'cover',
            'GET avatar' => 'avatar',
            'GET book' => 'book',
        ],
    ],
    'OPTIONS v1/photos' => 'v1/photo/options',
    'OPTIONS v1/photos/<id>' => 'v1/photo/options',
    'OPTIONS v1/photos/cover' => 'v1/photo/options',
    'OPTIONS v1/photos/avatar' => 'v1/photo/options',
    'OPTIONS v1/photos/book' => 'v1/photo/options',

    //Wallet
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/wallet' => '/v1/wallet'
        ],
        'except' => ['update', 'index', 'create'],
        'patterns' => [
            'GET' => 'view',
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST request-drawals' => 'request-drawals',
            'GET custodial-balance' => 'custodial-balance',
            'GET transaction-records' => 'trans-records',
        ],
    ],
    'OPTIONS v1/wallet/request-drawals' => 'v1/wallet/options',
    'OPTIONS v1/wallet/custodial-balance' => 'v1/wallet/options',
    'OPTIONS v1/wallet/transaction-records' => 'v1/wallet/options',

    //Client
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'v1/client' => '/v1/client'
        ],
        'except' => ['update', 'index', 'create', 'view'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET user-info' => 'user-info',
        ],
    ],
    'OPTIONS v1/client/user-info' => 'v1/client/options',

    //Statement
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/statement',
        'except' => ['update', 'index', 'create', 'view', 'delete'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'GET templates' => 'templates',
            'POST html-template' => 'html-template',
            'POST sign' => 'sign',
            'POST verify' => 'verify'
        ],
    ],
    'OPTIONS v1/statements/templates' => 'v1/statement/options',
    'OPTIONS v1/statements/html-template' => 'v1/statement/options',
    'OPTIONS v1/statements/sign' => 'v1/statement/options',
    'OPTIONS v1/statements/verify' => 'v1/statement/options',

    //Identity
    [
    'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/identity',
        'except' => ['update', 'index', 'create', 'delete'],
        'patterns' => [
            '' => 'options'
        ],
        'extraPatterns' => [
            'POST generate-keys' => 'generate-keys',
            'POST save-statement' => 'save-statement',
            'POST create-purpose-key' => 'create-purpose-key',
            'GET statements' => 'statements',
        ],
    ],
    'OPTIONS v1/identity/generate-keys' => 'v1/statement/options',
    'OPTIONS v1/identity/save-statement' => 'v1/statement/options',
    'OPTIONS v1/identity/statements' => 'v1/statement/options',
    'OPTIONS v1/identity/create-purpose-key' => 'v1/statement/options'
];