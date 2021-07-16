<?php
namespace app\application\service;


use app\application\dto\DiseaseDto;

interface DiseaseInfoServiceInterface
{
    /**
     * @return DiseaseDto[]
     */
    public function getAllDiseases($lang): array;

}