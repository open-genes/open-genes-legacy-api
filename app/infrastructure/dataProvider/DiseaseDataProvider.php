<?php
namespace app\infrastructure\dataProvider;


use app\models\Disease;

class DiseaseDataProvider implements DiseaseDataProviderInterface
{

    public function getAllDiseases($lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        return Disease::find()
            ->select(['id', 'omim_id', $nameField . ' name'])
            ->asArray()->all();
    }
}