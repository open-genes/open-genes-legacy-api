<?php
namespace app\infrastructure\dataProvider;

use app\models\Gene;
use app\models\GeneQuery;
use app\models\GeneToCommentCause;
use app\models\GeneToFunctionalCluster;
use app\models\Source;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class GeneDataProvider implements GeneDataProviderInterface
{
    public const STRING_EMPTY_ORTHOLOG = ' | | | ';
    /** @var string */
    private $lang;

    private $fields = [
        'gene.id',
        'gene.symbol',
        'gene.aliases',
        'gene.name',
        'gene.ncbi_id',
        'gene.uniprot',
        'gene.band',
        'gene.locationStart',
        'gene.locationEnd',
        'gene.orientation',
        'gene.accPromoter',
        'gene.accOrf',
        'gene.accCds',
        'gene.orthologs',
        'gene.expressionChange',
        'gene.created_at',
        'gene.updated_at',
        'gene.ensembl',
        'gene.human_protein_atlas',
        'gene.methylation_horvath',
    ];

    private $fieldsEn = [
        'gene.commentEvolutionEN comment_evolution',
        'gene.uniprot_summary_en comment_function',
        'gene.ncbi_summary_en description_ncbi',
        'gene.og_summary_en description_og',
    ];

    private $fieldsRu = [
        'gene.commentEvolution comment_evolution',
        'gene.uniprot_summary_ru comment_function',
        'gene.ncbi_summary_ru description_ncbi',
        'gene.og_summary_ru description_og',
    ];

    private $fieldsIsHidden = [
        'gene.isHidden isHidden'
    ];

    public function __construct($lang = 'en-US')
    {
        $this->lang = $lang;
        $langFields = $lang == 'en-US' ? $this->fieldsEn : $this->fieldsRu;
        $this->fields = array_merge($this->fields, $langFields);
    }

    /** @inheritDoc */
    public function getGene($geneId): array
    {
        $geneArray = Gene::find()
            ->select($this->fields)
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withProteinClasses($this->lang)
            ->withAgingMechanisms($this->lang)
            ->withSources()
            ->where(['gene.id' => $geneId])
            ->withPhylum()
            ->asArray()
            ->groupBy('gene.id')
            ->one();
        if (!$geneArray) {
            throw new NotFoundHttpException("Gene {$geneId} not found");
        }
        return $geneArray;
    }

    /** @inheritDoc */
    public function getGeneBySymbol($geneSymbol): array
    {
        $geneArray = Gene::find()
            ->select($this->fields)
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withProteinClasses($this->lang)
            ->withAgingMechanisms($this->lang)
            ->withSources()
            ->withOrthologs($this->lang)
            ->where(['gene.symbol' => $geneSymbol])
            ->withPhylum()
            ->asArray()
            ->groupBy('gene.id')
            ->one();
        if (!$geneArray) {
            throw new NotFoundHttpException("Gene {$geneSymbol} not found");
        }
        return $geneArray;
    }

    /** @inheritDoc */
    public function getLatestGenes(int $count): array
    {
        return Gene::find()
            ->select($this->fields)
            ->withPhylum()
            ->andWhere('isHidden != 1')
            ->orderBy('gene.updated_at desc')
            ->limit($count)
            ->asArray()
            ->all();
    }

    /** @inheritDoc */
    public function getAllGenes(int $count = null): array
    {
        return \Yii::$app->cache->getOrSet(['all_genes_list', 'count' => $count, 'lang' => $this->lang], // todo temp
            function ($cache) use ($count) { 
            return Gene::find()
                ->select($this->fields)
                ->withPhylum()
                ->withFunctionalClusters($this->lang)
                ->withCommentCause($this->lang)
                ->withSources()
                ->withDiseases($this->lang)
                ->withAgingMechanisms($this->lang)
                ->andWhere('isHidden != 1')
                ->orderBy('family_phylum.order DESC')
                ->limit($count)
                ->groupBy('gene.id')
                ->asArray()->all();
        }, 3600);
    }

    /** @inheritDoc */
    public function getGenesMethylation(int $count = null): array
    {
        $this->fields = array_merge($this->fields, $this->fieldsIsHidden);
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withSources(Source::HORVATH)
            ->limit($count)
            ->groupBy('gene.id')
            ->asArray();
        if ($count) {
            $genesArrayQuery->limit($count);
        }
        return $genesArrayQuery->all();
    }

    /** @inheritDoc */
    public function getIncreaseLifespan(int $count = null): array
    {
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->limit($count)
            ->innerJoin('lifespan_experiment', 'gene.id=lifespan_experiment.gene_id')
            ->groupBy('gene.id')
            ->asArray();
        if ($count) {
            $genesArrayQuery->limit($count);
        }
        return $genesArrayQuery->all();
    }

    /** @inheritDoc */
    public function getByFunctionalClustersIds(array $functionalClustersIds): array
    {
        $genesIdsByFunctionalClusters = GeneToFunctionalCluster::find()
            ->select('gene_id')
            ->groupBy('gene_id')
            ->where(['functional_cluster_id' => $functionalClustersIds])
            ->having('count(functional_cluster_id) = ' . count($functionalClustersIds))
            ->asArray()->column();

        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withPhylum()
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withAgingMechanisms($this->lang)
            ->where(['gene.id' => ($genesIdsByFunctionalClusters)])
            ->andWhere('isHidden != 1')
            ->orderBy('family_phylum.order DESC')
            ->groupBy('gene.id')
            ->asArray();

        return $genesArrayQuery->all();
    }

    /** @inheritDoc */
    public function getBySelectionCriteriaIds(array $selectionCriteriaIds): array
    {
        $genesIdsBySelectionCriteria = GeneToCommentCause::find()
            ->select('gene_id')
            ->groupBy('gene_id')
            ->where(['comment_cause_id' => $selectionCriteriaIds])
            ->having('count(comment_cause_id) = ' . count($selectionCriteriaIds))
            ->asArray()->column();

        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withPhylum()
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withAgingMechanisms($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['gene.id' => $genesIdsBySelectionCriteria])
            ->orderBy('family_phylum.order DESC')
            ->groupBy('gene.id')
            ->asArray();

        return $genesArrayQuery->all();
    }

    /** @inheritDoc */
    public function getByExpressionChange(int $expressionChange): array
    {
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withPhylum()
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withAgingMechanisms($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['gene.expressionChange' => $expressionChange])
            ->orderBy('family_phylum.order DESC')
            ->groupBy('gene.id')
            ->asArray();
        return $genesArrayQuery->all();
    }

    public function getByGoTerm(string $term): GeneQuery
    {
        return Gene::find()
            ->select($this->fields)
            ->withPhylum()
            ->withFunctionalClusters($this->lang)
            ->withDiseases($this->lang)
            ->withCommentCause($this->lang)
            ->withGoTerms($this->lang)
            ->withAgingMechanisms($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['like', 'gene_ontology.name_en', '%' . $term . '%', false])
            ->orderBy('family_phylum.order DESC')
            ->groupBy('gene.id')
            ->asArray();
    }
}