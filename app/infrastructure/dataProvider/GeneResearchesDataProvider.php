<?php
namespace app\infrastructure\dataProvider;

use app\models\AgeRelatedChange;
use app\models\GeneralLifespanExperiment;
use app\models\GeneToAdditionalEvidence;
use app\models\GeneToLongevityEffect;
use app\models\GeneToProgeria;
use app\models\InterventionResultForVitalProcess;
use app\models\LifespanExperiment;
use app\models\ProteinToGene;
use app\models\Sample;
use app\models\VitalProcess;
use yii\db\Query;

class GeneResearchesDataProvider implements GeneResearchesDataProviderInterface
{

    //// lifespan-experiments
    //// age-related-changes
    //// intervention-to-vital-processes
    //// protein-to-genes
    //// gene-to-progerias
    //// gene-to-longevity-effects

    public function getGeneralLifespanExperimentsByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        $therapyField = $lang == 'en-US' ? 'description_of_therapy_en' : 'description_of_therapy_ru';

        $generalLifespanExperiments = $this->getGeneralListByGeneId($geneId, $nameField, $commentField);

        foreach ($generalLifespanExperiments as &$general) {
            $lifespanExperiments = $this->getLifespanListByGeneral($general['id'], $nameField, $therapyField, $geneId);
            if (!isset($general['interventions'])) {
                $general['interventions'] = [];
            }
            $general['interventions'] = $lifespanExperiments;
        }
        return $generalLifespanExperiments;
    }

    public function getAgeRelatedChangesByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return AgeRelatedChange::find()
            ->select([
                "age_related_change_type.{$nameField} as changeType",
                "sample.{$nameField} as sample",
                "model_organism.{$nameField} as modelOrganism",
                "organism_line.{$nameField} as organismLine",
                "age_related_change.age_from as ageFrom",
                "age_related_change.age_to as ageTo",
                "age_related_change.age_unit as ageUnit",
                "age_related_change.change_value_male as valueForMale",
                "age_related_change.change_value_female as valueForFemale",
                "age_related_change.change_value_common as valueForAll",
                "age_related_change.measurement_type as measurementType",
                "age_related_change.reference as doi",
                "age_related_change.pmid",
                "age_related_change.{$commentField} as comment",
            ])
            ->distinct()
            ->innerJoin('age_related_change_type', 'age_related_change.age_related_change_type_id=age_related_change_type.id')
            ->leftJoin('sample', 'age_related_change.sample_id=sample.id')
            ->leftJoin('model_organism', 'age_related_change.model_organism_id=model_organism.id')
            ->leftJoin('organism_line', 'age_related_change.organism_line_id=organism_line.id')
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
    }

    /**
     * @throws \Exception
     */
    public function getGeneInterventionToVitalProcessByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        $processList = (new Query())->from('gene_intervention_to_vital_process')
            ->select([
                "gene_intervention_to_vital_process.id as id",
                "gene_intervention_method.{$nameField} as geneIntervention",
                "intervention_result_for_vital_process.{$nameField} as result",
                "intervention_result_for_vital_process.id as resultCode",
                "vital_process.{$nameField} as vitalProcess",
                "vital_process.id as vitalProcessId",
                "model_organism.{$nameField} as modelOrganism",
                "organism_line.{$nameField} as organismLine",
                "gene_intervention_to_vital_process.age",
                "gene_intervention_to_vital_process.genotype",
                "gene_intervention_to_vital_process.age_unit as ageUnit",
                "organism_sex.{$nameField} as sex",
                "gene_intervention_to_vital_process.reference as doi",
                "gene_intervention_to_vital_process.pmid",
                "gene_intervention_to_vital_process.{$commentField} as comment",
            ])
            ->innerJoin(
                'gene_intervention_result_to_vital_process',
                'gene_intervention_result_to_vital_process.gene_intervention_to_vital_process_id=gene_intervention_to_vital_process.id'
            )
            ->innerJoin('vital_process', 'vital_process.id=gene_intervention_result_to_vital_process.vital_process_id')
            ->innerJoin(
                'intervention_result_for_vital_process',
                'intervention_result_for_vital_process.id=gene_intervention_result_to_vital_process.intervention_result_for_vital_process_id'
            )
            ->innerJoin(
                'gene_intervention_method',
                'gene_intervention_to_vital_process.gene_intervention_method_id=gene_intervention_method.id'
            )
            ->leftJoin(
                'organism_sex',
                'organism_sex.id=gene_intervention_to_vital_process.sex_of_organism'
            )
            ->leftJoin('model_organism', 'gene_intervention_to_vital_process.model_organism_id=model_organism.id')
            ->leftJoin('organism_line', 'gene_intervention_to_vital_process.organism_line_id=organism_line.id')
            ->where(['gene_id' => $geneId])
            ->all();

        return $processList;
    }

    public function getProteinToGenesByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return ProteinToGene::find()
            ->select([
                "regulated_gene.id as regulatedGeneId",
                "regulated_gene.symbol as regulatedGeneSymbol",
                "regulated_gene.name as regulatedGeneName",
                "regulated_gene.ncbi_id as regulatedGeneNcbiId",
                "protein_activity.{$nameField} as proteinActivity",
                "gene_regulation_type.{$nameField} as regulationType",
                "protein_to_gene.reference as doi",
                "protein_to_gene.pmid",
                "protein_to_gene.{$commentField} as comment",
            ])
            ->distinct()
            ->innerJoin('gene as regulated_gene', 'protein_to_gene.regulated_gene_id=regulated_gene.id')
            ->innerJoin('protein_activity', 'protein_to_gene.protein_activity_id=protein_activity.id')
            ->innerJoin('gene_regulation_type', 'protein_to_gene.regulation_type_id=gene_regulation_type.id')
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
    }

    public function getGeneToProgeriasByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return GeneToProgeria::find()
            ->select([
                "progeria_syndrome.{$nameField} as progeriaSyndrome",
                "gene_to_progeria.reference as doi",
                "gene_to_progeria.pmid",
                "gene_to_progeria.{$commentField} as comment",
            ])
            ->distinct()
            ->innerJoin('progeria_syndrome', 'gene_to_progeria.progeria_syndrome_id=progeria_syndrome.id')
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
    }

    public function getGeneToLongevityEffectsByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return GeneToLongevityEffect::find()
            ->select([
                "longevity_effect.{$nameField} as longevityEffect",
                "polymorphism.{$nameField} as allelicPolymorphism",
                "gene_to_longevity_effect.sex_of_organism as sex",
                "gene_to_longevity_effect.allele_variant as allelicVariant",
                "model_organism.{$nameField} as modelOrganism",
                "age_related_change_type.{$nameField} as changeType",
                "gene_to_longevity_effect.data_type as dataType",
                "gene_to_longevity_effect.reference as doi",
                "gene_to_longevity_effect.pmid",
                "gene_to_longevity_effect.{$commentField} as comment",
            ])
            ->distinct()
            ->innerJoin('longevity_effect', 'gene_to_longevity_effect.longevity_effect_id=longevity_effect.id')
            ->leftJoin('polymorphism', 'gene_to_longevity_effect.polymorphism_id=polymorphism.id')
            ->leftJoin('age_related_change_type', 'gene_to_longevity_effect.age_related_change_type_id=age_related_change_type.id')
            ->leftJoin('model_organism', 'gene_to_longevity_effect.model_organism_id=model_organism.id')
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
    }

    public function getGeneToAdditionalEvidencesByGeneId(int $geneId, string $lang): array
    {
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return GeneToAdditionalEvidence::find()
            ->select([
                "gene_to_additional_evidence.reference as doi",
                "gene_to_additional_evidence.pmid",
                "gene_to_additional_evidence.{$commentField} as comment",
            ])
            ->distinct()
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
    }

    private function getTissueByLifespan (int $lifespanId, string $nameField): array {
        return Sample::find()
            ->select([
                "sample.id as id",
                "sample.{$nameField} as name",
            ])
            ->innerJoin('lifespan_experiment_to_tissue', 'lifespan_experiment_to_tissue.tissue_id=sample.id')
            ->innerJoin('lifespan_experiment', 'lifespan_experiment_to_tissue.lifespan_experiment_id=lifespan_experiment.id')
            ->where(['lifespan_experiment.id' => $lifespanId])
            ->asArray()
            ->all();
    }

    private function getVitalProcessByGeneral(int $generalId, string $nameField): array {
        return VitalProcess::find()
            ->select([
                "general_lifespan_experiment_to_vital_process.intervention_result_for_vital_process_id as intervention_result_for_vital_process_id",
                "vital_process.id as id",
                "vital_process.{$nameField} as name"
            ])
            ->innerJoin('general_lifespan_experiment_to_vital_process', 'general_lifespan_experiment_to_vital_process.vital_process_id=vital_process.id')
            ->where(['general_lifespan_experiment_to_vital_process.general_lifespan_experiment_id' => $generalId])
            ->asArray()
            ->all();
    }

    private function getGeneralListByGeneId(int $geneId, string $nameField, string $commentField): array {
        $generalList = GeneralLifespanExperiment::find()
            ->select([
                "general_lifespan_experiment.id as id",
                "model_organism.{$nameField} as modelOrganism",
                "organism_line.{$nameField} as organismLine",
                "organism_sex.{$nameField} as sex",
                "general_lifespan_experiment.temperature_from as temperatureFrom",
                "general_lifespan_experiment.temperature_to as temperatureTo",
                "diet.{$nameField} as diet",
                "sample.{$nameField} as expressionChangeTissue",
                "time_unit.{$nameField} as lifespanTimeUnit",
                "intervention_result_for_longevity.{$nameField} as interventionResultForLifespan",
                "measurement_type.{$nameField} as expressionMeasurementType",
                "general_lifespan_experiment.control_number as controlCohortSize",
                "general_lifespan_experiment.experiment_number as experimentCohortSize",
                "general_lifespan_experiment.expression_change as expressionChangePercent",
                "general_lifespan_experiment.control_lifespan_min as lifespanMinControl",
                "general_lifespan_experiment.control_lifespan_mean as lifespanMeanControl",
                "general_lifespan_experiment.control_lifespan_median as lifespanMedianControl",
                "general_lifespan_experiment.control_lifespan_max as lifespanMaxControl",
                "general_lifespan_experiment.experiment_lifespan_min as lifespanMinExperiment",
                "general_lifespan_experiment.experiment_lifespan_mean as lifespanMeanExperiment",
                "general_lifespan_experiment.experiment_lifespan_median as lifespanMedianExperiment",
                "general_lifespan_experiment.experiment_lifespan_max as lifespanMaxExperiment",
                "general_lifespan_experiment.lifespan_min_change as lifespanMinChangePercent",
                "general_lifespan_experiment.lifespan_mean_change as lifespanMeanChangePercent",
                "general_lifespan_experiment.lifespan_median_change as lifespanMedianChangePercent",
                "general_lifespan_experiment.lifespan_max_change as lifespanMaxChangePercent",
                "ssmin.{$nameField} as lMinChangeStatSignificance",
                "ssmean.{$nameField} as lMeanChangeStatSignificance",
                "ssmedian.{$nameField} as lMedianChangeStatSignificance",
                "ssmax.{$nameField} as lMaxChangeStatSignificance",
                "general_lifespan_experiment.reference as doi",
                "general_lifespan_experiment.pmid",
                "general_lifespan_experiment.{$commentField} as comment",
                "general_lifespan_experiment.organism_number_in_cage as populationDensity",

            ])
            ->distinct()
            ->innerJoin('lifespan_experiment', 'lifespan_experiment.general_lifespan_experiment_id=general_lifespan_experiment.id')
            ->leftJoin('intervention_result_for_longevity', 'general_lifespan_experiment.intervention_result_id=intervention_result_for_longevity.id')
            ->leftJoin('model_organism', 'general_lifespan_experiment.model_organism_id=model_organism.id')
            ->leftJoin('organism_line', 'general_lifespan_experiment.organism_line_id=organism_line.id')
            ->leftJoin('organism_sex', 'general_lifespan_experiment.organism_sex_id=organism_sex.id')
            ->leftJoin('diet', 'general_lifespan_experiment.diet_id=diet.id')
            ->leftJoin('sample', 'general_lifespan_experiment.changed_expression_tissue_id=sample.id')
            ->leftJoin('time_unit', 'general_lifespan_experiment.lifespan_change_time_unit_id=time_unit.id')
            ->leftJoin('measurement_type', 'general_lifespan_experiment.measurement_type=measurement_type.id')
            ->leftJoin('statistical_significance ssmin', 'general_lifespan_experiment.lifespan_min_change_stat_sign_id=ssmin.id')
            ->leftJoin('statistical_significance ssmean', 'general_lifespan_experiment.lifespan_mean_change_stat_sign_id=ssmean.id')
            ->leftJoin('statistical_significance ssmedian', 'general_lifespan_experiment.lifespan_median_change_stat_sign_id=ssmedian.id')
            ->leftJoin('statistical_significance ssmax', 'general_lifespan_experiment.lifespan_max_change_stat_sign_id=ssmax.id')
            ->where(['lifespan_experiment.gene_id' => $geneId])
            ->andWhere(['not', ['general_lifespan_experiment.model_organism_id' => null]])
            ->asArray()
            ->all();

        foreach ($generalList as &$general) {
            $processes = $this->getVitalProcessByGeneral($general['id'], $nameField);
            $general['vital_process'] = $processes;
        }
        return $generalList;
    }

    private function addTissueToLifespan(array &$lifespanList, string $nameField) {
        foreach ($lifespanList as &$lifespan) {
            if(!isset($lifespan['tissues'])) {
                $lifespan['tissues'] = [];
            }
            $tissues = $this->getTissueByLifespan($lifespan['id'], $nameField);
            foreach ($tissues as $tissue) {
                $lifespan['tissues'][] = $tissue;
            }
        }
    }

    private function getLifespanListByGeneral(int $generalId, string $nameField, string $therapyField, $currentGeneId): array {
        $lifespanList = LifespanExperiment::find()
            ->select([
                "lifespan_experiment.id as id",
                "gene.id as geneId",
                "gene.symbol as geneSymbol",
                "gene.name as geneName",
                "gene.ncbi_id as geneNcbiId",
                "gene_intervention_method.{$nameField} as interventionMethod",
                "gene_intervention_way.{$nameField} as interventionWay",
                "lifespan_experiment.tissue_specificity as tissueSpecific",
                "lifespan_experiment.tissue_specific_promoter as tissueSpecificPromoter",
                "lifespan_experiment.treatment_start as treatmentStart",
                "lifespan_experiment.treatment_end as treatmentEnd",
                "lifespan_experiment.mutation_induction as inductionByDrugWithdrawal",
                "lifespan_experiment.type as type",
                "lifespan_experiment.{$therapyField} as treatmentDescription",
                "start_time_unit.{$nameField} as startTimeUnit",
                "end_time_unit.{$nameField} as endTimeUnit",
                "genotype.{$nameField} as genotype",
                "active_substance_delivery_way.{$nameField} as drugDeliveryWay",
                "active_substance.{$nameField} as drug",
                "ts.{$nameField} as startStageOfDevelopment",
                "te.{$nameField} as endStageOfDevelopment",
                "experiment_treatment_period.{$nameField} as treatmentPeriod",
                "experiment_main_effect.{$nameField} as experimentMainEffect",

            ])
            ->distinct()
            ->innerJoin('gene', 'lifespan_experiment.gene_id=gene.id')
            ->leftJoin('gene_intervention_method', 'lifespan_experiment.gene_intervention_method_id=gene_intervention_method.id')
            ->leftJoin('gene_intervention_way', 'lifespan_experiment.gene_intervention_way_id=gene_intervention_way.id')
            ->leftJoin('time_unit start_time_unit', 'lifespan_experiment.treatment_start_time_unit_id=start_time_unit.id')
            ->leftJoin('time_unit end_time_unit', 'lifespan_experiment.treatment_end_time_unit_id=end_time_unit.id')
            ->leftJoin('genotype', 'lifespan_experiment.genotype=genotype.id')
            ->leftJoin('active_substance_delivery_way', 'lifespan_experiment.active_substance_delivery_way_id=active_substance_delivery_way.id')
            ->leftJoin('treatment_stage_of_development ts', 'lifespan_experiment.treatment_start_stage_of_development_id=ts.id')
            ->leftJoin('treatment_stage_of_development te', 'lifespan_experiment.treatment_end_stage_of_development_id=te.id')
            ->leftJoin('experiment_treatment_period', 'lifespan_experiment.treatment_period_id=experiment_treatment_period.id')
            ->leftJoin('active_substance', 'lifespan_experiment.active_substance_id=active_substance.id')
            ->leftJoin('experiment_main_effect', 'lifespan_experiment.experiment_main_effect_id=experiment_main_effect.id')
            ->where(['lifespan_experiment.general_lifespan_experiment_id' => $generalId])
            ->andWhere(['or', ['<>', 'gene.id', $currentGeneId], ['lifespan_experiment.type' => LifespanExperiment::TYPE_EXPERIMENT]])
            ->asArray()
            ->all();

        $this->addTissueToLifespan($lifespanList, $nameField);
        return $lifespanList;
    }
}