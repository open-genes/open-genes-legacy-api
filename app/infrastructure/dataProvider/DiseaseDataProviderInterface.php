<?php
namespace app\infrastructure\dataProvider;


interface DiseaseDataProviderInterface
{

    /**
     * @return array
     */
    public function getAllDiseases($lang): array;

    /**
     * @return array
     */
    public function getDiseasesCategories($lang): array;

}