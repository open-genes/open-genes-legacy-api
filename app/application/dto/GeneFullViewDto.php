<?php
namespace app\application\dto;

class GeneFullViewDto
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
    /** @var string */
    public $name;
    /** @var array */
    public $diseases;
    /** @var array */
    public $diseaseCategories;
    /** @var string */
    public $ncbiId;
    /** @var string */
    public $uniprot;
    /** @var string */
    public $commentEvolution;
    /** @var string */
    public $commentFunction;
    /** @var string */
    public $proteinDescriptionUniProt;
    /** @var string */
    public $descriptionNCBI;
    /** @var string */
    public $descriptionOG;
    /** @var string */
    public $proteinDescriptionOpenGenes;
    /** @var array */
    public $commentCause;
    /** @var FunctionalClusterDto[] */
    public $functionalClusters;
    /** @var ResearchDto[] */
    public $researches;
    /** @var array [$geneName => $geneExpression[]] */
    public $expression;
    /** @var array */
    public $proteinClasses;
    /** @var int */
    public $expressionChange;
    /** @var string */
    public $band;
    /** @var int */
    public $locationStart;
    /** @var int */
    public $locationEnd;
    /** @var int */
    public $orientation;
    /** @var string */
    public $accPromoter;
    /** @var string */
    public $accOrf;
    /** @var string */
    public $accCds;
//    /** @var array */
    /** @var @var array */
    public $terms;

//    public $isHidden;
    /** @var array */
    public $orthologs;
    /** @var array */
    public $ortholog;
    /** @var int */
    public $timestamp;
    /** @var array */
    public $humanProteinAtlas;
    /** @var string */
    public $ensembl;
    /** @var string */
    public $methylationCorrelation;
    /** @var array */
    public $agingMechanisms = [];
    /** @var array */
    public $source = [];

//    public $isHidden;
//    public $userEdited;
//    public $hylo;
//    public $why;
//    public $band;
//    public $locationStart;
//    public $locationEnd;
//    public $orientation;
//    public $accPromoter;
//    public $accOrf;
//    public $accCds;
}

