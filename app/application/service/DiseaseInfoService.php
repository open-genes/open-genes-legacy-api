<?php
namespace app\application\service;

use app\infrastructure\dataProvider\DiseaseDataProviderInterface;

class DiseaseInfoService implements DiseaseInfoServiceInterface
{
    /** @var DiseaseDataProviderInterface  */
    private $diseaseDataProvider;

    public function __construct(
        DiseaseDataProviderInterface $diseaseDataProvider
    )
    {
        $this->diseaseDataProvider = $diseaseDataProvider;
    }

    /** @inheritDoc */
    public function getAllDiseases($lang): array
    {
        $diseases = $this->diseaseDataProvider->getAllDiseases($lang);
        $result = [];
        foreach ($diseases as $disease) {
            $result[$disease['id']] = $disease;
            unset($result[$disease['id']]['id']);
        }
        return $result;
    }

    /** @inheritDoc */
    public function getDiseaseCategories($lang): array
    {
                 $diseaseCategories = $this->diseaseDataProvider->getDiseasesCategories($lang);
                $result = [];
                foreach ($diseaseCategories as $diseaseCategory) {
                    $result[$diseaseCategory['id']] = $diseaseCategory;
                    unset($result[$diseaseCategory['id']]['id']);
                }
                return $result;
//        return $this->diseaseDataProvider->getDiseasesCategories($lang);
    }
}