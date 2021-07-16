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
        return $this->diseaseDataProvider->getAllDiseases($lang);
    }
}