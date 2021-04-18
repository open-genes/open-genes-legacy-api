<?php
namespace app\infrastructure\dataProvider;


use app\models\Phylum;

class PhylumDataProvider implements PhylumDataProviderInterface
{
    /** @var string  */
    private $lang;

    private $fields = [
        'age.id',
        'age.name_phylo',
        'age.name_mya',
        'age.order',
    ];


    public function getAllPhyla(): array
    {
        return Phylum::find()
            ->asArray()
            ->all();
    }
}