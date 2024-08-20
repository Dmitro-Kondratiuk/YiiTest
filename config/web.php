<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3_Y9EKqqq0517f-CuLgceYcnrvg0V6qX',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@app/logs/application.log', // Указание пути к файлу логов
                    'maxFileSize' => 1024 * 2, // Максимальный размер файла (2 MB)
                    'maxLogFiles' => 5, // Количество файлов для хранения
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'blogs/index',
                'blogs/create' => 'blogs/create',
                'blogs/update/<id:\d+>' => 'blogs/update',
                'blogs/view/<id:\d+>' => 'blogs/view',
                'blogs/delete/<id:\d+>' => 'blogs/delete',
                'blogs' => 'blogs/index',

                'category/create' => 'category/create',
                'category/update/<id:\d+>' => 'category/update',
                'category/view/<id:\d+>' => 'category/view',
                'category/delete/<id:\d+>' => 'category/delete',
                'category' => 'category/index',

                'admin'=>'admin/index',
                'admin/view/<id:\d+>' => 'admin/view',
            ],
        ],

    ],
    'homeUrl' => ['/blogs/index'],
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

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1','192.168.65.1'],
    ];
}

return $config;
