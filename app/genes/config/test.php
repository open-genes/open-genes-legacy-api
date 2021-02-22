<?php

use genes\models\User;

return [
    'id' => 'app-backend-tests',
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => User::class,
        ],
    ],
];
