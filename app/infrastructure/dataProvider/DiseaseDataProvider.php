<?php
namespace app\infrastructure\dataProvider;


use app\models\Disease;

class DiseaseDataProvider implements DiseaseDataProviderInterface
{

    public function getAllDiseases($lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $icdNameField = $lang == 'en-US' ? 'icd_name_en' : 'icd_name_ru';
        return Disease::find()
            ->select(['id', 'icd_code', $nameField . ' name', $icdNameField . ' icd_name'])
            ->where('omim_id is not null')
            ->asArray()
            ->all();
    }

    public function getDiseasesCategories($lang): array
    {
        $icdNameField = $lang == 'en-US' ? 'icd_name_en' : 'icd_name_ru';
        return Disease::find()
            ->select(['disease_category.icd_code', 'disease_category.' . $icdNameField . ' icd_category_name'])
            ->distinct()
            ->innerJoin('gene_to_disease', 'gene_to_disease.disease_id=disease.id')
            ->join(
                'LEFT JOIN',
                'disease disease_category',
                'disease.icd_code_visible = disease_category.icd_code'
            )
            ->where('disease_category.icd_name_en is not null')
            ->asArray()
            ->all();
    }
}