<?php

namespace app\infrastructure\dataProvider;

use app\models\InterventionResultForVitalProcess;
use app\models\LifespanExperiment;

class Converter
{
    public static function fixLifespan(&$general, &$lifespanList) {
        if (!isset($general['controlAndExperiment'])) {
            $general['controlAndExperiment'] = [];
        }
        if (!isset($general['experiment'])) {
            $general['experiment'] = [];
        }
        foreach ($lifespanList as &$lifespan) {
            if ($lifespan['type'] == LifespanExperiment::TYPE_CONTROL) {
                unset($lifespan['type']);
                unset($lifespan['id']);
                $general['controlAndExperiment'][] = $lifespan;
            }
            elseif ($lifespan['type'] == LifespanExperiment::TYPE_EXPERIMENT) {
                unset($lifespan['type']);
                unset($lifespan['id']);
                $general['experiment'][] = $lifespan;
            }
        }
    }

    public static function fixVitalProcess(&$general, $processes) {
        if (!isset($general['interventionImproves'])) {
            $general['interventionImproves'] = [];
        }

        if (!isset($general['interventionDeteriorates'])) {
            $general['interventionDeteriorates'] = [];
        }

        foreach ($processes as $process) {
            if ($process['intervention_result_for_vital_process_id'] == InterventionResultForVitalProcess::IMPROVE) {
                unset($process['intervention_result_for_vital_process_id']);
                $general['interventionImproves'][] = $process;
            }
            elseif ($process['intervention_result_for_vital_process_id'] == InterventionResultForVitalProcess::DETERIOR) {
                unset($process['intervention_result_for_vital_process_id']);
                $general['interventionDeteriorates'][] = $process;
            }
        }
    }

    public static function fixGeneInterventionToVitalProcessByGeneId (array $processList): array {
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