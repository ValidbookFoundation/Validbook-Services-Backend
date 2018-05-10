<?php
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
$params = require(__DIR__ . '/params.php');
$dotenv = new Dotenv\Dotenv(dirname(__DIR__));
$dotenv->load();

$config = [
    'id' =>  getenv("APP_ID"),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => getenv("REDIS_HOST"),
            'port' => getenv("REDIS_PORT"),
            'database' => getenv("REDIS_DATABASE"),
        ],
        'mailer' => [
            'class' => 'nickcv\mandrill\Mailer',
            'apikey' => getenv('MANDRILL_API_KEY'),
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'redis' => 'redis', // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
            'ttr' => 5 * 60, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
            'as log' => \yii\queue\LogBehavior::class
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
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
        'olddb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('OLD_DB_HOST') . ';dbname=' . getenv('OLD_DB_NAME'),
            'username' => getenv('OLD_DB_USER'),
            'password' => getenv('OLD_DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'mutex' => [
            'class' => 'yii\mutex\FileMutex'
        ],
        's3' => [
            'class' => 'frostealth\yii2\aws\s3\Service',
            'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
                'key' => getenv('AWS_KEY'),
                'secret' => getenv('AWS_SECRET'),
            ],
            'region' => getenv('AWS_REGION' ),
            'defaultBucket' => getenv('S3_BUCKET'),
            'defaultAcl' => 'public-read',
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@app/migrations',
                '@app/migrations/archive',
            ],
        ],
    ],
    'params' => $params
];

return $config;
