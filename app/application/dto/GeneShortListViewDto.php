<?php

namespace app\application\dto;

class GeneShortListViewDto
{
    /** @var int */
    public $id;
    /** @var string */
    public $symbol;
    /** @var string */
    public $name;
    /** @var string */
    public $ncbiId;
    /** @var string */
    public $uniprot;
    /** @var string */
    public $ensembl;
    /** @var string */
    public $methylationCorrelation;
    /** @var array */
    public $researches;
}