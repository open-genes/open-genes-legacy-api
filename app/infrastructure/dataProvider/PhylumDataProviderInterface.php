<?php
namespace app\infrastructure\dataProvider;


interface PhylumDataProviderInterface
{

    /**
     * @return array
     */
    public function getAllPhyla(): array;

}