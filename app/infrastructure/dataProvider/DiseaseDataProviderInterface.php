<?php
namespace app\infrastructure\dataProvider;


interface DiseaseDataProviderInterface
{

    /**
     * @return array
     */
    public function getAllDiseases($lang): array;

}