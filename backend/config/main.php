<?php
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
$dotenv = new Dotenv\Dotenv(dirname(dirname(__DIR__)));
$dotenv->load();

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'request' => [
            'enableCsrfValidation' => true,
            'cookieValidationKey' => '6Cf7zzJ6nsFssWdjUlCGE1Q1hWw472VQ',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'amnah\yii2\user\components\User',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
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
            'dsn' => 'mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/amnah/yii2-user/views' => ['@backend/views'],
                ],
            ],
        ],
    ],
    'modules' => [

        'user' => [
            'class' => 'amnah\yii2\user\Module',
            'controllerMap' => [
                'admin' => 'backend\controllers\AdminController',
                'default' => 'backend\controllers\DefaultController',
            ],
            'modelClasses'  => [
                'User' => 'backend\models\User', // note: don't forget component user::identityClass above
            ],
            'requireUsername' => false,
            'useUsername' => false,
            'loginUsername' => false
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
