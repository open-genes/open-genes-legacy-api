<?php

namespace app\application\service;

use app\application\dto\GeneDtoAssemblerInterface;
use app\application\dto\GeneFullViewDto;
use app\application\dto\ResearchDtoAssemblerInterface;
use app\infrastructure\dataProvider\GeneDataProviderInterface;
use app\infrastructure\dataProvider\GeneExpressionDataProviderInterface;
use app\infrastructure\dataProvider\GeneResearchesDataProviderInterface;
use Exception;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use Yii;

class GeneInfoService implements GeneInfoServiceInterface
{
    /** @var GeneDataProviderInterface */
    private $geneDataProvider;

    /** @var GeneExpressionDataProviderInterface */
    private $geneExpressionDataProvider;

    /** @var GeneResearchesDataProviderInterface */
    private $geneResearchesDataProvider;

    /** @var GeneDtoAssemblerInterface */
    private $geneDtoAssembler;

    /** @var ResearchDtoAssemblerInterface */
    private $researchDtoAssembler;

    public function __construct(
        GeneDataProviderInterface $geneRepository,
        GeneExpressionDataProviderInterface $geneExpressionDataProvider,
        GeneResearchesDataProviderInterface $geneResearchesDataProvider,
        GeneDtoAssemblerInterface $geneDtoAssembler,
        ResearchDtoAssemblerInterface $researchDtoAssembler
    ) {
        $this->geneDataProvider = $geneRepository;
        $this->geneExpressionDataProvider = $geneExpressionDataProvider;
        $this->geneResearchesDataProvider = $geneResearchesDataProvider;
        $this->geneDtoAssembler = $geneDtoAssembler;
        $this->researchDtoAssembler = $researchDtoAssembler;
    }

    public function getGeneViewInfo(string $geneSymbol, string $lang = 'en-US'): GeneFullViewDto
    {
        try {
            Yii::beginProfile('getGeneViewInfo');

            if (is_numeric($geneSymbol)) {
                $geneArray = $this->geneDataProvider->getGene($geneSymbol);
            } else {
                $geneArray = $this->geneDataProvider->getGeneBySymbol($geneSymbol);
            }

            $geneDto = $this->geneDtoAssembler->mapViewDto($geneArray, $lang);
            $geneDto->expression = $this->geneExpressionDataProvider->getByGeneId($geneArray['id'], $lang);
            $geneDto->researches = $this->getGeneResearches($geneArray['id'], $lang);

            $geneOntologyService = new GeneOntologyService();
            $pagination = new Pagination(['totalCount' => $geneOntologyService->getCountForGene($geneDto->ncbiId), 'pageSize' => 20]);
            $geneDto->terms = $geneOntologyService->getFunctionsForGene($geneDto->ncbiId, $pagination->offset, $pagination->limit);

            Yii::endProfile('getGeneViewInfo');

            return $geneDto;
        } catch (Exception $e) {
            Yii::error("Error in getGeneViewInfo: " . $e->getMessage(), __METHOD__);
            throw $e;
        }
    }

    public function getLatestGenes(int $count, string $lang = 'en-US'): array
    {
        $latestGenesArray = $this->geneDataProvider->getLatestGenes($count);
        $geneDtos = [];
        foreach ($latestGenesArray as $latestGene) {
            $geneDtos[] = $this->geneDtoAssembler->mapLatestViewDto($latestGene);
        }

        return $geneDtos;
    }

    public function getAllGenes(int $count = null, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getAllGenes($count);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDtos[] = $this->geneDtoAssembler->mapListViewDto($gene, $lang);
        }
        return $geneDtos;
    }

    public function getGenesMethylation(int $count = null, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getGenesMethylation($count);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDto = $this->geneDtoAssembler->mapShortListViewDto($gene, $lang);
            unset($geneDto->researches);
            $geneDtos[] = $geneDto;
        }
        return $geneDtos;
    }

    public function getIncreaseLifespan(int $count = null, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getIncreaseLifespan($count);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDto = $this->geneDtoAssembler->mapShortListViewDto($gene, $lang);
            $geneDto->researches = ['increaseLifespan' => $this->getGeneResearches($geneDto->id, $lang)->increaseLifespan];
            $geneDtos[] = $geneDto;
        }

        return $geneDtos;
    }

    public function getByFunctionalClustersIds(array $functionalClustersIds, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getByFunctionalClustersIds($functionalClustersIds);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDtos[] = $this->geneDtoAssembler->mapListViewDto($gene, $lang);
        }

        return $geneDtos;
    }

    public function getBySelectionCriteriaIds(array $selectionCriteriaIds, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getBySelectionCriteriaIds($selectionCriteriaIds);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDtos[] = $this->geneDtoAssembler->mapListViewDto($gene, $lang);
        }

        return $geneDtos;
    }

    public function getByExpressionChange(int $expressionChange, string $lang = 'en-US'): array
    {
        $genesArray = $this->geneDataProvider->getByExpressionChange($expressionChange);
        $geneDtos = [];
        foreach ($genesArray as $gene) {
            $geneDtos[] = $this->geneDtoAssembler->mapListViewDto($gene, $lang);
        }

        return $geneDtos;
    }

    public function getByGoTerm(array $params, string $lang = 'en-US'): ActiveDataProvider
    {
        $query = $this->geneDataProvider->getByGoTerm($params['term']);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 20,
                'page' => $params['page']
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        $geneDtos = [];
        foreach ($provider->getModels() as $gene) {
            $geneDtos[] = $this->geneDtoAssembler->mapListViewWithTermsDto($gene, $lang);
        }
        $provider->setModels($geneDtos);

        return $provider;
    }

    private function getGeneResearches($geneId, $lang)
    {
        $lifespanExperiments = $this->geneResearchesDataProvider->getGeneralLifespanExperimentsByGeneId($geneId, $lang);
        $geneToProgerias = $this->geneResearchesDataProvider->getGeneToProgeriasByGeneId($geneId, $lang);
        $geneToLongevityEffects = $this->geneResearchesDataProvider->getGeneToLongevityEffectsByGeneId($geneId, $lang);
        $ageRelatedChanges = $this->geneResearchesDataProvider->getAgeRelatedChangesByGeneId($geneId, $lang);
        $interventionResultForVitalProcesses = $this->geneResearchesDataProvider->getGeneInterventionToVitalProcessByGeneId($geneId, $lang);
        $proteinToGenes = $this->geneResearchesDataProvider->getProteinToGenesByGeneId($geneId, $lang);
        $additionalEvidences = $this->geneResearchesDataProvider->getGeneToAdditionalEvidencesByGeneId($geneId, $lang);

        return $this->researchDtoAssembler->mapResearchDto(
            $lifespanExperiments,
            $geneToProgerias,
            $geneToLongevityEffects,
            $ageRelatedChanges,
            $interventionResultForVitalProcesses,
            $proteinToGenes,
            $additionalEvidences,
            $lang
        );
    }
}

class GeneOntologyService
{
    public function getFunctionsForGene(int $ncbiId, int $offset, int $limit)
    {
        $query = GeneFunction::find()->where(['ncbi_id' => $ncbiId]);
        $query->offset($offset)->limit($limit);

        return $query->all();
    }

    public function getCountForGene(int $ncbiId)
    {
        return GeneFunction::find()->where(['ncbi_id' => $ncbiId])->count();
    }
}
