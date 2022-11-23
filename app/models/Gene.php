<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gene".
 *
 * @property int $id
 * @property string $symbol
 * @property string $aliases
 * @property string $name
 * @property int $ncbi_id
 * @property string $uniprot
 * @property string $band
 * @property int $locationStart
 * @property int $locationEnd
 * @property int $orientation
 * @property string $accPromoter
 * @property string $accOrf
 * @property string $accCds
 * @property string $orthologs
 * @property string $commentEvolution
 * @property string $commentFunction
 * @property string $commentCause
 * @property string $commentEvolutionEN
 * @property string $commentFunctionEN
 * @property int $isHidden
 * @property int $expressionChange
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $family_phylum_id
 * @property int|null $phylum_id
 * @property string $protein_complex_ru
 * @property string $protein_complex_en
 * @property string $ncbi_summary_ru
 * @property string $ncbi_summary_en
 * @property string $ensembl
 * @property string $human_protein_atlas
 * @property int|null $methylation_horvath
 *
 * @property Phylum $phylum
 * @property Phylum $familyPhylum
 * @property AgeRelatedChange[] $ageRelatedChanges
 * @property GeneExpressionInSample[] $geneExpressionInSamples
 * @property GeneInterventionToVitalProcess[] $geneInterventionToVitalProcesses
 * @property GeneToCommentCause[] $geneToCommentCauses
 * @property GeneToLongevityEffect[] $geneToLongevityEffects
 * @property GeneToOntology[] $geneToOntologies
 * @property GeneToProgeria[] $geneToProgerias
 * @property GeneToProteinClass[] $geneToProteinClasses
 * @property LifespanExperiment[] $lifespanExperiments
 * @property ProteinToGene[] $proteinToGenes
 * @property ProteinToGene[] $proteinToGenes0
 * @property GeneToAdditionalEvidence[] $geneToAdditionalEvidences
 */
class Gene extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gene';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ncbi_id', 'locationStart', 'locationEnd', 'orientation', 'isHidden', 'expressionChange', 'created_at', 'updated_at', 'phylum_id', 'family_phylum_id', 'taxon_id', 'methylation_horvath'], 'integer'],
            [['commentEvolution', 'commentFunction', 'commentCause', 'commentAging', 'commentEvolutionEN', 'commentFunctionEN', 'commentAgingEN', 'protein_complex_ru', 'protein_complex_en', 'human_protein_atlas', 'ncbi_summary_ru', 'ncbi_summary_en', 'og_summary_en', 'og_summary_ru'], 'string'],
            [['symbol', 'aliases', 'name', 'uniprot', 'band', 'accPromoter', 'accOrf', 'accCds'], 'string', 'max' => 120],
            [['orthologs'], 'string', 'max' => 1000],
            [['commentEvolution', 'commentFunction', 'commentCause', 'commentAging', 'commentEvolutionEN', 'commentFunctionEN', 'commentAgingEN'], 'string', 'max' => 1500],
            [['phylum_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phylum::className(), 'targetAttribute' => ['phylum_id' => 'id']],
            [['family_phylum_id'], 'exist', 'skipOnError' => true, 'targetClass' => Phylum::className(), 'targetAttribute' => ['family_phylum_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'symbol' => 'Symbol',
            'aliases' => 'Aliases',
            'name' => 'Name',
            'ncbi_id' => 'Entrez Gene',
            'uniprot' => 'Uniprot',
            'band' => 'Band',
            'locationStart' => 'Location Start',
            'locationEnd' => 'Location End',
            'orientation' => 'Orientation',
            'accPromoter' => 'Acc Promoter',
            'accOrf' => 'Acc Orf',
            'accCds' => 'Acc Cds',
            'orthologs' => 'Orthologs',
            'commentEvolution' => 'Comment Evolution',
            'commentFunction' => 'Comment Function',
            'commentCause' => 'Comment Cause',
            'commentAging' => 'Comment Aging',
            'commentEvolutionEN' => 'Comment Evolution En',
            'commentFunctionEN' => 'Comment Function En',
            'commentAgingEN' => 'Comment Aging En',
            'isHidden' => 'Is Hidden',
            'expressionChange' => 'Expression Change',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'family_phylum_id' => 'Family Phylum ID',
            'phylum_id' => 'Phylum ID',
            'protein_complex_ru' => 'Protein Complex Ru',
            'protein_complex_en' => 'Protein Complex En',
            'ncbi_summary_ru' => 'Summary Ru',
            'ncbi_summary_en' => 'Summary En',
            'ensembl' => 'Ensembl',
            'human_protein_atlas' => 'Human Protein Atlas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgeRelatedChanges()
    {
        return $this->hasMany(AgeRelatedChange::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFamilyPhylum()
    {
        return $this->hasOne(Phylum::class, ['id' => 'family_phylum_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneExpressionInSamples()
    {
        return $this->hasMany(GeneExpressionInSample::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneInterventionToVitalProcesses()
    {
        return $this->hasMany(GeneInterventionToVitalProcess::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToCommentCauses()
    {
        return $this->hasMany(GeneToCommentCause::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToLongevityEffects()
    {
        return $this->hasMany(GeneToLongevityEffect::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToOntologies()
    {
        return $this->hasMany(GeneToOntology::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToProgerias()
    {
        return $this->hasMany(GeneToProgeria::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToProteinClasses()
    {
        return $this->hasMany(GeneToProteinClass::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLifespanExperiments()
    {
        return $this->hasMany(LifespanExperiment::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProteinToGenes()
    {
        return $this->hasMany(ProteinToGene::class, ['gene_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneToAdditionalEvidences()
    {
        return $this->hasMany(GeneToAdditionalEvidence::class, ['gene_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return GeneQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeneQuery(get_called_class());
    }
}
