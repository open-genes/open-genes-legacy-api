<?php
namespace app\controllers;

use app\application\service\DiseaseInfoServiceInterface;
use app\models\Gene;
use app\application\dto\GeneFullViewDto;
use app\application\service\GeneInfoServiceInterface;
use app\application\service\GeneOntologyServiceInterface;
use app\application\service\PhylumInfoServiceInterface;
use app\helpers\LanguageMapHelper;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\Cors;
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

    public function actionIndex(): array
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getAllGenes(null, $this->language);
    }

    public function actionMethylation(): array
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getGenesMethylation(null, $this->language);
    }

    public function actionIncreaseLifespan(): array
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getIncreaseLifespan(null, $this->language);
    }

    /**
     * @param string $symbol
     * @return GeneFullViewDto
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionGene($symbol)
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getGeneViewInfo($symbol, $this->language);
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

    public function actionBySelectionCriteria($ids)
    {
        $selectionCriteriaIds = explode(',', $ids);
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getBySelectionCriteriaIds($selectionCriteriaIds, $this->language);
    }

    public function actionByExpressionChange($expressionChange)
    {
        /** @var GeneInfoServiceInterface $geneInfoService */
        $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
        return $geneInfoService->getByExpressionChange((int)$expressionChange, $this->language);
    }

    public function actionPhyla()
    {
        /** @var PhylumInfoServiceInterface $phylumInfoService */
        $phylumInfoService = Yii::$container->get(PhylumInfoServiceInterface::class);
        return $phylumInfoService->getAllPhyla();
    }
    
    public function actionByGoTerm($term)
    {
        if (Yii::$app->request->isGet) {
            $params = Yii::$app->request->get();
            $term = strip_tags(trim($term));
            if (strlen($term) < 3) {
                return [];
            }

            /** @var GeneInfoServiceInterface $geneInfoService */
            $geneInfoService = Yii::$container->get(GeneInfoServiceInterface::class);
            /**
             * @see GeneInfoService::getByGoTerm
             * @var ArrayDataProvider $provider
             */
            $provider = $geneInfoService->getByGoTerm($term, $this->language);

            if (isset($params['page'])) {
                $provider->pagination->page = $params['page'] == 1 ? 0 : (int)($params['page'] - 1);
            }
            if (isset($params['pageSize'])) {
                $provider->pagination->pageSize = (int)$params['pageSize'];
            }

            return [
                'items' => $provider->getModels(),
                'options' => (object)[
                    'objTotal' => count($provider->getModels()),
                    'total' => $provider->getTotalCount(),
                    'pagination' => (object)[
                        'page' => $provider->pagination->getPage() + 1,
                        'pageSize' => $provider->pagination->getPageSize(),
                        'pageTotal' => $provider->pagination->getPageCount()
                    ]
                ]
            ];
        }
        return [];
    }

    public function actionDisease()
    {
        /** @var DiseaseInfoServiceInterface $diseaseInfoService */
        $diseaseInfoService = Yii::$container->get(DiseaseInfoServiceInterface::class);
        return $diseaseInfoService->getAllDiseases($this->language);
    }

    public function actionDiseaseCategory()
    {
        /** @var DiseaseInfoServiceInterface $diseaseInfoService */
        $diseaseInfoService = Yii::$container->get(DiseaseInfoServiceInterface::class);
        return $diseaseInfoService->getDiseaseCategories($this->language);
    }

}
