<?php
namespace genes\controllers;

use genes\application\service\GeneInfoServiceInterface;
use genes\helpers\LanguageMapHelper;
use Yii;
use yii\filters\Cors;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class ApiController extends Controller
{
    /** @var string */
    private $language;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'OPTIONS'],
                ],

            ],
        ];
    }

    /**
     * @param $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $language = Yii::$app->request->getQueryParam('lang', 'en-US');
        $this->language = (new LanguageMapHelper())->getMappedLanguage($language);
        return parent::beforeAction($action);
    }

    public function actionReference()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        return $this->render('reference');
    }

    public function actionIndex()
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        $geneDtos = $geneInfoService->getAllGenes(null, $this->language);
        return $geneDtos;
    }

    /**
     * @param $id
     * @return \genes\application\dto\GeneFullViewDto
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionGene($id)
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        $geneDto = $geneInfoService->getGeneViewInfo($id, $this->language);
        return $geneDto;
    }

    public function actionLatest()
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getLatestGenes(4, $this->language);
    }

    public function actionByFunctionalCluster($ids)
    {
        $functionalClusterIds = explode(',', $ids);
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getByFunctionalClustersIds($functionalClusterIds, $this->language);
    }

    public function actionByExpressionChange($expressionChange)
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        if(!in_array($expressionChange, ['increased', 'decreased', 'mixed'])) { // todo сделать форму с валидацией
            throw new BadRequestHttpException('expressionChange must be one of \'increased\', \'decreased\', \'mixed\'');
        }
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getByExpressionChange($expressionChange, $this->language);
    }

}