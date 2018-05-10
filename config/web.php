<?php
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$params = require(__DIR__ . '/params.php');
$routes = require(__DIR__ . '/routes.php');

$config = [
    'id' => getenv("APP_ID"),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv("REDIS_HOST"),
            'port' => getenv("REDIS_PORT"),
            'database' => getenv("REDIS_DATABASE"),
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'redis' => 'redis', // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
            'ttr' => 5 * 60, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
            'as log' => \yii\queue\LogBehavior::class
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\v1\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'nickcv\mandrill\Mailer',
            'apikey' => getenv('MANDRILL_API_KEY'),
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'db2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_POSTFIX_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=' . getenv('SPHINX_HOST') . ';port=' . getenv('SPHINX_PORT') . ';',
            'username' => getenv('SPHINX_USERNAME'),
            'password' => getenv('SPHINX_PASSWORD'),
        ],
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => getenv('AWS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ],
            'region' => getenv('AWS_REGION'),
            'defaultBucket' => getenv('S3_BUCKET'),
            'defaultAcl' => 'public-read',
        ],
        'formatter' => [
            'dateFormat' => 'dd MMM yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'USD',
            'locale' => 'en-US'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => $routes
        ],
        'twitter' => [
            'class' => 'naffiq\twitterapi\TwitterAPI',
            'oauthAccessToken' => getenv("TWITTER_OAUTH_ACCESS_TOKEN"),
            'oauthAccessTokenSecret' => getenv('TWITTER_OAUTH_ACCESS_TOKEN_SECRET'),
            'consumerKey' => getenv('TWITTER_CONSUMER_KEY'),
            'consumerSecret' => getenv('TWITTER_CONSUMER_SECRET')
        ],
        'facebook' => [
            'class' => 'alexandervas\facebook\FbComponent',
            'appId' => getenv("FACEBOOK_APP_ID"),
            'secret' => getenv("FACEBOOK_APP_SECRET")
        ],
        'uuid' => [
            'class' => 'ollieday\uuid\Uuid',
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'app\modules\v1\Module',
        ],
        'sitemap' => [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                // your models
                'app\modules\v1\models\User',
                'app\modules\v1\models\book\Book',
                'app\modules\v1\models\story\Story',
            ],
            'urls' => [
                // your additional urls
            ],
            'enableGzip' => true, // default is false
            'cacheExpire' => 1, // 1 second. Default is 24 hours
        ],
    ],
    'params' => $params,
];

if (getenv("ENVIRONMENT") !== 'prod') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'panels' => [
            'queue' => \yii\queue\debug\Panel::class,
        ],
        'allowedIPs' => [getenv("ALLOWED_IP1"), getenv("ALLOWED_IP2")],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'sphinxModel' => [
                'class' => 'yii\sphinx\gii\model\Generator'
            ]
        ],
    ];
}

if (getenv("ENVIRONMENT") === 'local') {
    $config['aliases'] = [
        '@bower' => '@vendor/bower-asset'
    ];
}

return $config;
