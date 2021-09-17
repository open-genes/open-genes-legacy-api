<?php
namespace app\application\dto;

class GeneListViewDto
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
    /** @var array */
    public $aliases;
    /** @var array */
    public $diseases;
    /** @var array */
    public $diseaseCategories;
    /** @var string */
    public $name;
    /** @var string */
    public $ncbiId;
    /** @var string */
    public $commentCause;
    /** @var string */
    public $uniprot;
    /** @var FunctionalClusterDto[] */
    public $functionalClusters;
    /** @var int */
    public $expressionChange;
    /** @var int */
    public $timestamp;
    /** @var array */
    public $terms;
    /** @var string */
    public $ensembl;
    /** @var string */
    public $methylationCorrelation;

}

