<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[Gene]].
 *
 * @see Gene
 */
class GeneQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Gene[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Gene|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    public function withFunctionalClusters($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return $this
            ->addSelect([
                'group_concat(distinct concat(functional_cluster.id,"|",functional_cluster.'. $nameField . ') separator "||") as functional_clusters'
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_functional_cluster',
                'gene_to_functional_cluster.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'functional_cluster',
                'gene_to_functional_cluster.functional_cluster_id = functional_cluster.id'
            );
    }
    
    public function withDiseases($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $icdNameField = $lang == 'en-US' ? 'icd_name_en' : 'icd_name_ru';
        return $this
            ->addSelect([
                "group_concat(distinct concat(
                disease.id,'|',
                coalesce(disease.icd_code, ''),'|',
                coalesce(nullif(disease.{$nameField}, ''), nullif(disease.name_en, ''), ''),'|',
                coalesce(nullif(disease.{$icdNameField}, ''), nullif(disease.icd_name_en, ''), '')
                )  separator '##') as diseases"
            ])
            ->addSelect([
                "group_concat(distinct concat(
                disease_category.id,'|',
                coalesce(disease_category.icd_code, ''),'|',
                coalesce(nullif(disease_category.{$icdNameField}, ''), nullif(disease_category.icd_name_en, ''), '')
                )  separator '##') as disease_categories"
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_disease',
                'gene_to_disease.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'disease',
                'gene_to_disease.disease_id = disease.id'
            )
            ->join(
                'LEFT JOIN',
                'disease disease_category',
                'disease.icd_code_visible = disease_category.icd_code'
            );
    }

    public function withExpression()
    {
        return $this
            ->join(
                'LEFT JOIN',
                'gene_expression_in_sample',
                'gene_expression_in_sample.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'sample',
                'gene_expression_in_sample.sample_id = sample.id'
            );
    }

    public function withCommentCause($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return $this
            ->addSelect([
                'group_concat(distinct concat(comment_cause.id,"|",comment_cause.'. $nameField . ') separator "||") as comment_cause'
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_comment_cause',
                'gene_to_comment_cause.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'comment_cause',
                'gene_to_comment_cause.comment_cause_id = comment_cause.id'
            );
    }

    public function withProteinClasses($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return $this
            ->addSelect([
                'group_concat(distinct concat(protein_class.id,"|",protein_class.'. $nameField . ') separator "||") as protein_class'
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_protein_class',
                'gene_to_protein_class.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'protein_class',
                'gene_to_protein_class.protein_class_id = protein_class.id'
            );
    }
    public function withSources(int $sourceId = 0)
    {
        $geneQuery = $this
            ->addSelect([
                'group_concat(distinct source.name separator "||") as source'
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_source',
                'gene_to_source.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'source',
                'gene_to_source.source_id = source.id'
            );

        if ($sourceId !== 0) {
            $geneQuery->where('source.id = ' . $sourceId);
        }
        return $geneQuery;
    }

    public function withOrthologs($lang, int $orthologId = 0)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $geneQuery = $this
            ->addSelect([
                'group_concat(distinct concat_ws("|",
                ortholog.id,
                ortholog.symbol,
                IFNULL (ortholog.external_base_name, " "),
                IFNULL (ortholog.external_base_id, " "),
                IFNULL (model_organism.name_lat, " "),
                IFNULL (model_organism.' . $nameField . ', " ")
                ) separator "||") as ortholog'
            ])
            ->join(
                'LEFT JOIN',
                'gene_to_ortholog',
                'gene_to_ortholog.gene_id = gene.id'
            )
            ->join(
                'LEFT JOIN',
                'ortholog',
                'gene_to_ortholog.ortholog_id = ortholog.id'
            )
            ->join(
                'LEFT JOIN',
                'model_organism',
                'ortholog.model_organism_id = model_organism.id'
            );

        if ($orthologId !== 0) {
            $geneQuery->where('ortholog.id = ' . $orthologId);
        }
        return $geneQuery;
    }

    public function withPhylum()
    {
        return $this
            ->addSelect('phylum.name_mya as phylum_age, phylum.name_phylo as phylum_name, phylum.order as phylum_order, phylum.id as phylum_id')
            ->addSelect('family_phylum.name_mya as family_phylum_age, family_phylum.name_phylo as family_phylum_name, family_phylum.order as family_phylum_order, family_phylum.id as family_phylum_id')
            ->addSelect('taxon.name_en as taxon_name')
            ->join(
                'LEFT JOIN',
                'phylum as family_phylum',
                'gene.family_phylum_id = family_phylum.id'
            )
            ->join(
                'LEFT JOIN',
                'phylum',
                'gene.phylum_id = phylum.id'
            )
            ->join(
                'LEFT JOIN',
                'taxon',
                'gene.taxon_id = taxon.id'
            );
    }
    
    public function withGoTerms($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return $this
            ->addSelect(new Expression("
                group_concat(distinct concat(`gene_ontology`.`ontology_identifier`,'|',`gene_ontology`.`name_en`,'|',`gene_ontology`.`category`) separator  '||') as `go_terms`"))
            ->innerJoin('gene_to_ontology', 'gene_to_ontology.gene_id=gene.id')
            ->innerJoin('gene_ontology', 'gene_ontology.id = gene_to_ontology.gene_ontology_id');
    }
    
    public function withAgingMechanisms($lang)
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return $this->addSelect('aging_mechanisms')
            ->innerJoin("
                (SELECT gene.id,
                group_concat(distinct concat(`aging_mechanism`.`id`, '|', `aging_mechanism`.`{$nameField}`) separator
                    '||') as `aging_mechanisms`
                FROM gene
                LEFT JOIN `gene_to_ontology` ON gene_to_ontology.gene_id = gene.id
                LEFT JOIN `gene_ontology_to_aging_mechanism_visible`
                        ON gene_to_ontology.gene_ontology_id = gene_ontology_to_aging_mechanism_visible.gene_ontology_id
                LEFT JOIN `aging_mechanism` 
                ON gene_ontology_to_aging_mechanism_visible.aging_mechanism_id = aging_mechanism.id
                group by gene.id) mechanisms", 'gene.id=mechanisms.id');
    }
}
