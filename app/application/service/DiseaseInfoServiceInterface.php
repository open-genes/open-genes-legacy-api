<?php
namespace app\application\service;


interface DiseaseInfoServiceInterface
{

    public function getAllDiseases($lang): array;

    public function getDiseaseCategories($lang): array;

}