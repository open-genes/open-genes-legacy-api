<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gene_intervention_to_vital_process".
 *
 * @property int $id
 * @property int $gene_id
 * @property int $gene_intervention_id
 * @property int $vital_process_id
 * @property int $model_organism_id
 * @property int $organism_line_id
 * @property double $age
 * @property int $sex_of_organism
 * @property string $reference
 * @property string $comment_en
 * @property string $comment_ru
 * @property int $age_unit
 * @property int $genotype
 *
 * @property Gene $gene
 * @property InterventionResultForVitalProcess $interventionResultForVitalProcess
 * @property GeneIntervention $geneIntervention
 * @property ModelOrganism $modelOrganism
 * @property OrganismLine $organismLine
 */
class GeneInterventionToVitalProcess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gene_intervention_to_vital_process';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gene_id', 'gene_intervention_id', 'model_organism_id', 'organism_line_id', 'sex_of_organism', 'age_unit', 'genotype'], 'integer'],
            [['age'], 'number'],
            [['comment_en', 'comment_ru'], 'string'],
            [['reference'], 'string', 'max' => 255],
            [['gene_id'], 'exist', 'skipOnError' => true, 'targetClass' => Gene::class, 'targetAttribute' => ['gene_id' => 'id']],
            [['gene_intervention_id'], 'exist', 'skipOnError' => true, 'targetClass' => GeneIntervention::class, 'targetAttribute' => ['gene_intervention_id' => 'id']],
            [['model_organism_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModelOrganism::class, 'targetAttribute' => ['model_organism_id' => 'id']],
            [['organism_line_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrganismLine::class, 'targetAttribute' => ['organism_line_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gene_id' => 'Gene ID',
            'gene_intervention_id' => 'Gene Intervention ID',
            'model_organism_id' => 'Model Organism ID',
            'organism_line_id' => 'Organism Line ID',
            'age' => 'Age',
            'sex_of_organism' => 'Sex Of Organism',
            'reference' => 'Reference',
            'comment_en' => 'Comment En',
            'comment_ru' => 'Comment Ru',
            'age_unit' => 'Age Unit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGene()
    {
        return $this->hasOne(Gene::class, ['id' => 'gene_id']);
    }

    /**
     * Gets query for [[InterventionResultForVitalProcess]].
     *
     * @return \yii\db\ActiveQuery|InterventionResultForVitalProcessQuery
     */
    public function getInterventionResultForVitalProcess()
    {
        return $this->hasMany(InterventionResultForVitalProcess::class, ['id' => 'intervention_result_for_vital_process_id'])
            ->viaTable('gene_intervention_result_to_vital_process', ['gene_intervention_to_vital_process_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeneIntervention()
    {
        return $this->hasOne(GeneIntervention::class, ['id' => 'gene_intervention_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelOrganism()
    {
        return $this->hasOne(ModelOrganism::class, ['id' => 'model_organism_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismLine()
    {
        return $this->hasOne(OrganismLine::class, ['id' => 'organism_line_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVitalProcess()
    {
        return $this->hasMany(VitalProcess::class, ['id' => 'vital_process_id'])
            ->viaTable('gene_intervention_result_to_vital_process', ['gene_intervention_to_vital_process_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return GeneInterventionToVitalProcessQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeneInterventionToVitalProcessQuery(get_called_class());
    }
}
