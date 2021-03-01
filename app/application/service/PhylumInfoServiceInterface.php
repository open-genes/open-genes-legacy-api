<?php
namespace application\service;


use application\dto\PhylumDto;

interface PhylumInfoServiceInterface
{
    /**
     * @return PhylumDto[]
     */
    public function getAllPhyla(): array;

}