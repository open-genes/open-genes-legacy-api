<?php
require __DIR__ . '/../common/vendor/autoload.php';
require __DIR__ . '/../common/vendor/yiisoft/yii2/Yii.php';
require __DIR__ . '/../common/config/bootstrap.php';

$dotenv = Dotenv\Dotenv::create(Yii::getAlias('@common'));
$dotenv->load();

defined('YII_DEBUG') or define('YII_DEBUG', getenv('DEBUG'));
defined('YII_ENV') or define('YII_ENV', getenv('ENV'));


$config = yii\helpers\ArrayHelper::merge(
    require __DIR__ . '/../common/config/main.php',
    require __DIR__ . '/config/main.php'
);

(new yii\web\Application($config))->run();
