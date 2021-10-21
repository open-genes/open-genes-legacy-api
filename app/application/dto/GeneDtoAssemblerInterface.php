<?php


namespace app\application\dto;


interface GeneDtoAssemblerInterface
{
    public function mapViewDto(array $geneArray, string $lang): GeneFullViewDto;

    public function mapLatestViewDto(array $geneArray): LatestGeneViewDto;

    public function mapListViewDto(array $geneArray, string $lang): GeneListViewDto;

    public function mapShortListViewDto(array $geneArray): GeneShortListViewDto;

    public function mapListViewWithTermsDto(array $geneArray, string $lang): GeneListViewDto;
}