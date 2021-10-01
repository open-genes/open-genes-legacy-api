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
            ->select(['id', 'icd_code icdCode', $nameField . ' name', $icdNameField . ' icdName'])
            ->where('omim_id is not null')
            ->asArray()
            ->all();
    }

    public function getDiseasesCategories($lang): array
    {
        $icdNameField = $lang == 'en-US' ? 'icd_name_en' : 'icd_name_ru';
        $rootCategories = Disease::find()
            ->select(['min(disease_category.id) id', 'disease_category.icd_code icdCode', 'min(disease_category.' . $icdNameField . ') icdCategoryName'])
            ->distinct()
            ->innerJoin('gene_to_disease', 'gene_to_disease.disease_id=disease.id')
            ->join(
                'LEFT JOIN',
                'disease disease_category',
                'disease.icd_code_visible = disease_category.icd_code'
            )
            ->where('disease_category.icd_name_en is not null')
            ->groupBy('disease_category.icd_code')
            ->asArray()
            ->all();

        foreach ($rootCategories as &$rootCategory) {
            $rootCategory['diseases'] = $this->getChildrenDiseasesForIcdRecursive($rootCategory['icdCode'], $lang);
        }
        return $rootCategories;
    }

    private function getChildrenDiseasesForIcdRecursive($rootIcdCategory, $lang): array
    {
        $nameField = $lang == 'en-US' ? 'name_en' : 'name_ru';
        $icdNameField = $lang == 'en-US' ? 'icd_name_en' : 'icd_name_ru';
        $diseases = [];
        $icdChildrenCategories = Disease::find()
            ->select(['disease.id', 'disease.icd_code icdCode', $nameField . ' name', 
                $icdNameField . ' icdName', 'group_concat(distinct gene.symbol separator ",") genesSymbols'])
            ->innerJoin('gene_to_disease', 'gene_to_disease.disease_id=disease.id')
            ->innerJoin('gene', 'gene_to_disease.gene_id=gene.id')
            ->where(['icd_code_visible' => $rootIcdCategory])
            ->groupBy('disease.id')
            ->asArray()->all();
        foreach ($icdChildrenCategories as $category) {
            $categoryId = $category['id'];
            unset($category['id']);
            $category['genesSymbols'] = explode(',', $category['genesSymbols']);
            $category['isRare'] = null; // todo for OG-343
            $diseases[$categoryId] = $category;
        }
        return $diseases;
    }
}
