<?php

namespace app\application\dto;

interface ResearchDtoAssemblerInterface
{
    /**
     * @param array $lifespanExperiments
     * @param array $geneToProgerias
     * @param array $geneToLongevityEffects
     * @param array $ageRelatedChanges
     * @param array $interventionResultForVitalProcesses
     * @param array $proteinToGenes
     * @param array $additionalEvidences
     * @param string $lang
     * @return ResearchDto
     */
    public function mapResearchDto(
        $lifespanExperiments,
        $geneToProgerias,
        $geneToLongevityEffects,
        $ageRelatedChanges,
        $interventionResultForVitalProcesses,
        $proteinToGenes,
        $additionalEvidences,
        $lang
    ): ResearchDto;
}