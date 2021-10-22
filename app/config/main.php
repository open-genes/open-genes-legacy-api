<?php


$params = require __DIR__ . '/params.php';

$config = [
    'id' => 'genes',
    'name' => 'Open Longevity Genes',
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-GB', // todo костыль на то, что у нас переводы не в yii-формате ['english phrase' => 'русская фраза'], переделаем?
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'controllerNamespace' => 'app\controllers',
    'vendorPath' => '@app/vendor',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-genes',
            'cookieValidationKey' => '123',
        ],
        'i18n' => [
            'translations' => [
                'main' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => __DIR__ . '/../assets/translations',
//                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'genes',
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
        'assetManager' => [
            'basePath' => __DIR__ . '/../runtime/assets',
            'baseUrl' => '/runtime/assets',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'about' => 'site/about',
                'api/gene/?' => 'api/index',
                'api/gene/by-functional-cluster/<ids>' => 'api/by-functional-cluster',
                'api/by-functional-cluster/<ids>' => 'api/by-functional-cluster', // todo 
                'api/gene/by-expression-change/<expressionChange>' => 'api/by-expression-change',
                'api/by-expression-change/<expressionChange>' => 'api/by-expression-change', // todo 
                'api/gene/by-selection-criteria/<ids>' => 'api/by-selection-criteria',
                'api/gene/by-go-term/<term>' => 'api/by-go-term',
                'api/gene/by-latest' => 'api/latest',
                'api/gene/<symbol:[\w-]+>' => 'api/gene',
                'api/disease/?' => 'api/disease',
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            app\application\service\GeneInfoServiceInterface::class => app\application\service\GeneInfoService::class,
            app\application\service\PhylumInfoServiceInterface::class => app\application\service\PhylumInfoService::class,
            app\application\service\DiseaseInfoServiceInterface::class => \app\application\service\DiseaseInfoService::class,
            app\application\dto\GeneDtoAssemblerInterface::class => app\application\dto\GeneDtoAssembler::class,
            app\application\dto\ResearchDtoAssemblerInterface::class => app\application\dto\ResearchDtoAssembler::class,
            app\infrastructure\dataProvider\GeneDataProviderInterface::class => function (\yii\di\Container $container) {
                return new app\infrastructure\dataProvider\GeneDataProvider(Yii::$app->language);
            },
            app\infrastructure\dataProvider\GeneExpressionDataProviderInterface::class => app\infrastructure\dataProvider\GeneExpressionDataProvider::class,
            app\infrastructure\dataProvider\DiseaseDataProviderInterface::class => app\infrastructure\dataProvider\DiseaseDataProvider::class,
            app\infrastructure\dataProvider\GeneResearchesDataProviderInterface::class => app\infrastructure\dataProvider\GeneResearchesDataProvider::class,
            app\infrastructure\dataProvider\PhylumDataProviderInterface::class => app\infrastructure\dataProvider\PhylumDataProvider::class,
            app\application\service\GeneOntologyServiceInterface::class => app\application\service\GeneOntologyService::class

        ]
    ],
    'defaultRoute' => 'site/index',
    'params' => $params,
    'runtimePath' => __DIR__ . '/../runtime',
    'on beforeAction' => function ($event) { // todo привести язык на фронте к стандарту ln-LN
        $language = $_GET['lang'] ?? $_COOKIE['lang'] ?? Yii::$app->language;
        $language = (new app\helpers\LanguageMapHelper())->getMappedLanguage($language);
        if (Yii::$app->language != $language) {
            Yii::$app->language = $language;
        }
        if (!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $language) {
            setcookie('lang', $language, $expire = 0, $path = "/");
        }
    },
];


if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

return $config;