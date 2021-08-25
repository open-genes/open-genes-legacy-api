<?php
namespace app\application\dto;

class LatestGeneViewDto
{
    /** @var int */
    public $id;
    /** @var PhylumDto */
    public $origin;
    /** @var PhylumDto */
    public $familyOrigin;
    /** @var string */
    public $homologueTaxon;
    /** @var string */
    public $symbol;
    /** @var int */
    public $timestamp;
}

