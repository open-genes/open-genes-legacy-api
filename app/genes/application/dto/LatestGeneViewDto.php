<?php
namespace genes\application\dto;

class LatestGeneViewDto
{
    /** @var int */
    public $id;
    /** @var PhylumDto */
    public $origin;
    /** @var string */
    public $homologueTaxon;
    /** @var string */
    public $symbol;
}

