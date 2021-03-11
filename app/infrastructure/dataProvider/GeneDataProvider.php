<?php
namespace app\infrastructure\dataProvider;

use app\models\Gene;
use app\models\GeneQuery;
use app\models\GeneToCommentCause;
use app\models\GeneToFunctionalCluster;
use yii\web\NotFoundHttpException;

class GeneDataProvider implements GeneDataProviderInterface
{
    /** @var string  */
    private $lang;

    private $fields = [
        'gene.id',
        'gene.symbol',
        'gene.aliases',
        'gene.name',
        'gene.ncbi_id',
        'gene.uniprot',
        'gene.why',
        'gene.band',
        'gene.locationStart',
        'gene.locationEnd',
        'gene.orientation',
        'gene.accPromoter',
        'gene.accOrf',
        'gene.accCds',
        'gene.references',
        'gene.orthologs',
        'gene.commentsReferenceLinks',
        'gene.expressionChange',
        'gene.created_at',
        'gene.updated_at',
        'gene.rating',
        'gene.ensembl',
        'gene.human_protein_atlas',
    ];

    private $fieldsEn = [
        'gene.commentEvolutionEN comment_evolution',
        'gene.commentFunctionEN comment_function',
        'gene.commentAgingEN comment_aging',
        'gene.summary_en description_ncbi',
    ];

    private $fieldsRu = [
        'gene.commentEvolution comment_evolution',
        'gene.commentFunction comment_function',
        'gene.commentAging comment_aging',
        'gene.summary_ru description_ncbi',
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
            ->withCommentCause($this->lang)
            ->withProteinClasses($this->lang)
            ->where(['gene.id' => $geneId])
            ->withAge()
            ->asArray()
            ->groupBy('gene.id')
            ->one();
        if(!$geneArray) {
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
            ->withCommentCause($this->lang)
            ->withProteinClasses($this->lang)
            ->where(['gene.symbol' => $geneSymbol])
            ->withAge()
            ->asArray()
            ->groupBy('gene.id')
            ->one();
        if(!$geneArray) {
            throw new NotFoundHttpException("Gene {$geneSymbol} not found");
        }
        return $geneArray;
    }

    /** @inheritDoc */
    public function getLatestGenes(int $count): array
    {
        return Gene::find()
            ->select($this->fields)
            ->withAge()
            ->andWhere('isHidden != 1')
            ->orderBy('gene.updated_at desc')
            ->limit($count)
            ->asArray()
            ->all();
    }

    /** @inheritDoc */
    public function getAllGenes(int $count = null): array
    {
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withAge()
            ->withFunctionalClusters($this->lang)
            ->withCommentCause($this->lang)
            ->andWhere('isHidden != 1')
            ->orderBy('age.order DESC')
            ->limit($count)
            ->groupBy('gene.id')
            ->asArray();
         if($count) {
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
            ->withAge()
            ->withFunctionalClusters($this->lang)
            ->withCommentCause($this->lang)
            ->where(['gene.id' => ($genesIdsByFunctionalClusters)])
            ->andWhere('isHidden != 1')
            ->orderBy('age.order DESC')
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
            ->withAge()
            ->withFunctionalClusters($this->lang)
            ->withCommentCause($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['gene.id' => $genesIdsBySelectionCriteria])
            ->orderBy('age.order DESC')
            ->groupBy('gene.id')
            ->asArray();

        return $genesArrayQuery->all();
    }

    /** @inheritDoc */
    public function getByExpressionChange(int $expressionChange): array
    {
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withAge()
            ->withFunctionalClusters($this->lang)
            ->withCommentCause($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['gene.expressionChange' => $expressionChange])
            ->orderBy('age.order DESC')
            ->groupBy('gene.id')
            ->asArray();
        return $genesArrayQuery->all();
    }

    public function getByGoTerm(string $term): array
    {
        $genesArrayQuery = Gene::find()
            ->select($this->fields)
            ->withAge()
            ->withFunctionalClusters($this->lang)
            ->withCommentCause($this->lang)
            ->withGoTerms($this->lang)
            ->andWhere('isHidden != 1')
            ->andWhere(['like', 'gene_ontology.name_en', '%' . $term . '%', false])
            ->orderBy('age.order DESC')
            ->groupBy('gene.id')
            ->asArray();
        return $genesArrayQuery->all();
    }
}