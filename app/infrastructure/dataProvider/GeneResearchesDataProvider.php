<?php
namespace app\infrastructure\dataProvider;

use app\models\AgeRelatedChange;
use app\models\GeneToAdditionalEvidence;
use app\models\GeneToLongevityEffect;
use app\models\GeneToProgeria;
use app\models\InterventionResultForVitalProcess;
use app\models\LifespanExperiment;
use app\models\ProteinToGene;
use yii\db\Query;

class GeneResearchesDataProvider implements GeneResearchesDataProviderInterface
{

    //// lifespan-experiments
    //// age-related-changes
    //// intervention-to-vital-processes
    //// protein-to-genes
    //// gene-to-progerias
    //// gene-to-longevity-effects

    public function getLifespanExperimentsByGeneId(int $geneId, string $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $commentField = $lang == 'en-US' ? 'comment_en' : 'comment_ru';
        return LifespanExperiment::find()
            ->select([
                "gene_intervention_method.{$nameField} as interventionType",
                "intervention_result_for_longevity.{$nameField} as interventionResult",
                "model_organism.{$nameField} as modelOrganism",
                "organism_line.{$nameField} as organismLine",
                "organism_sex.{$nameField} as sex",
                "general_lifespan_experiment_id",
                "general_lifespan_experiment.age",
                "lifespan_experiment.treatment_start",
                "start_time_unit.{$nameField} as startTimeUnit",
                "lifespan_experiment.genotype",
                "general_lifespan_experiment.age_unit as ageUnit",
                "general_lifespan_experiment.lifespan_change_percent_male as valueForMale",
                "general_lifespan_experiment.lifespan_change_percent_female as valueForFemale",
                "general_lifespan_experiment.lifespan_change_percent_common as valueForAll",
                "general_lifespan_experiment.reference as doi",
                "general_lifespan_experiment.pmid",
                "general_lifespan_experiment.{$commentField} as comment",
            ])
            ->distinct()
            ->innerJoin('gene_intervention_method', 'lifespan_experiment.gene_intervention_method_id=gene_intervention_method.id')
            ->innerJoin('general_lifespan_experiment', 'lifespan_experiment.general_lifespan_experiment_id=general_lifespan_experiment.id')
            ->innerJoin('intervention_result_for_longevity', 'general_lifespan_experiment.intervention_result_id=intervention_result_for_longevity.id')
            ->leftJoin('treatment_time_unit start_time_unit', 'lifespan_experiment.treatment_start_time_unit_id=start_time_unit.id')
            ->leftJoin('model_organism', 'general_lifespan_experiment.model_organism_id=model_organism.id')
            ->leftJoin('organism_line', 'general_lifespan_experiment.organism_line_id=organism_line.id')
            ->leftJoin('organism_sex', 'general_lifespan_experiment.organism_sex_id=organism_sex.id')
            ->where(['gene_id' => $geneId])
            ->asArray()
            ->all();
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

        return $this->prepareGeneInterventionToVitalProcessByGeneId($processList);
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

    private function prepareGeneInterventionToVitalProcessByGeneId (array $processList): array {
        $result = [];
        foreach ($processList as $process) {
            if (!isset($result[$process['id']])) {
                $result[$process['id']] = [
                    'geneIntervention' => $process['geneIntervention'],
                    'modelOrganism' => $process['modelOrganism'],
                    'organismLine' => $process['organismLine'],
                    'interventionImproves' => [],
                    'interventionDeteriorates' => [],
                    'age' => $process['age'],
                    'genotype' => $process['genotype'],
                    'sex' => $process['sex'],
                    'doi' => $process['doi'],
                    'pmid' => $process['pmid'],
                    'comment' => $process['comment'],
                ];
            }
            if ($process['resultCode'] == InterventionResultForVitalProcess::IMPROVE) {
                $result[$process['id']]['interventionImproves'][] = ['id' => $process['vitalProcessId'], 'name' => $process['vitalProcess']];
            }
            elseif ($process['resultCode'] == InterventionResultForVitalProcess::DETERIOR) {
                $result[$process['id']]['interventionDeteriorates'][] = ['id' => $process['vitalProcessId'], 'name' => $process['vitalProcess']];
            }
            else {
                throw new \Exception('Unknown process result code ' . $process['resultCode']);
            }
        }
        return array_values($result);
    }

}