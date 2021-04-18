<?php
namespace app\application\service;


use app\application\dto\PhylumDto;

interface PhylumInfoServiceInterface
{
    /**
     * @return PhylumDto[]
     */
    public function getAllPhyla(): array;

}