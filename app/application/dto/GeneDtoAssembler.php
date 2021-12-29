<?php

namespace app\application\dto;

class GeneDtoAssembler implements GeneDtoAssemblerInterface
{

    public function mapViewDto(array $geneArray, string $lang): GeneFullViewDto
    {
        $geneDto = new GeneFullViewDto();

        $geneDto->id = (int)$geneArray['id'];
        $geneDto->origin = $this->prepareOrigin($geneArray);
        $geneDto->familyOrigin = $this->prepareFamilyOrigin($geneArray);
        $geneDto->homologueTaxon = (string)$geneArray['taxon_name'];
        $geneDto->symbol = (string)$geneArray['symbol'];
        $geneDto->aliases = $geneArray['aliases'] ? explode(' ', str_replace(',', '', $geneArray['aliases'])) : [];
        $geneDto->name = (string)$geneArray['name'];
        $geneDto->diseases = $this->mapDiseases($geneArray['diseases']);
        $geneDto->diseaseCategories = $this->mapDiseaseCategories($geneArray['disease_categories']);
        $geneDto->ncbiId = (string)$geneArray['ncbi_id'];
        $geneDto->uniprot = (string)$geneArray['uniprot'];
        $geneDto->commentCause = $this->prepareCommentCauses($geneArray);
        $geneDto->agingMechanisms = $this->prepareAgingMechanisms($geneArray);
        $geneDto->source = $this->prepareSource($geneArray['source']);
        $geneDto->proteinClasses = $this->prepareProteinClasses($geneArray);
        $geneDto->commentEvolution = $geneArray['comment_evolution'];
        $geneDto->commentFunction = (string)$geneArray['comment_function'];
        $geneDto->proteinDescriptionUniProt = (string)$geneArray['comment_function'];
        $geneDto->descriptionNCBI = (string)$geneArray['description_ncbi'];
        $geneDto->descriptionOG = (string)$geneArray['description_og'];
        $geneDto->proteinDescriptionOpenGenes = (string)$geneArray['description_og'];
        $geneDto->functionalClusters = $this->mapFunctionalClusterDtos($geneArray['functional_clusters']);
        $geneDto->expressionChange = (int)$geneArray['expressionChange'];
        $geneDto->band = (string)$geneArray['band'];
        $geneDto->locationStart = (string)$geneArray['locationStart'];
        $geneDto->locationEnd = (string)$geneArray['locationEnd'];
        $geneDto->orientation = (string)$geneArray['orientation'];
        $geneDto->accPromoter = (string)$geneArray['accPromoter'];
        $geneDto->accOrf = (string)$geneArray['accOrf'];
        $geneDto->accCds = (string)$geneArray['accCds'];
        $geneDto->orthologs = $this->prepareOrthologs($geneArray['orthologs']);
        $geneDto->timestamp = $this->prepareTimestamp($geneArray);
        $geneDto->methylationCorrelation = $this->prepareMethylation($geneArray, $lang);

        $geneDto->ensembl = $geneArray['ensembl'] ?? '';
        $geneDto->human_protein_atlas = !empty($geneArray['human_protein_atlas']) ? json_decode($geneArray['human_protein_atlas']) : '';

        return $geneDto;
    }

    public function mapLatestViewDto(array $geneArray): LatestGeneViewDto
    {
        $geneDto = new LatestGeneViewDto();
        $geneDto->id = (int)$geneArray['id'];
        $geneDto->origin = $this->prepareOrigin($geneArray);
        $geneDto->familyOrigin = $this->prepareFamilyOrigin($geneArray);
        $geneDto->homologueTaxon = $geneArray['taxon_name'];
        $geneDto->symbol = $geneArray['symbol'];
        $geneDto->timestamp = $this->prepareTimestamp($geneArray);
        return $geneDto;
    }

    public function mapListViewDto(array $geneArray, string $lang): GeneListViewDto
    {
//        var_dump($geneArray); die;
        $geneDto = new GeneListViewDto();
        $geneDto->id = (int)$geneArray['id'];
        $geneDto->name = (string)$geneArray['name'];
        $geneDto->origin = $this->prepareOrigin($geneArray);
        $geneDto->familyOrigin = $this->prepareFamilyOrigin($geneArray);
        $geneDto->homologueTaxon = (string)$geneArray['taxon_name'];
        $geneDto->symbol = (string)$geneArray['symbol'];
        $geneDto->diseases = $this->mapDiseases($geneArray['diseases']);
        $geneDto->diseaseCategories = $this->mapDiseaseCategories($geneArray['disease_categories']);
        $geneDto->agingMechanisms = $this->prepareAgingMechanisms($geneArray);
        $geneDto->ncbiId = (string)$geneArray['ncbi_id'];
        $geneDto->uniprot = (string)$geneArray['uniprot'];
        $geneDto->expressionChange = (int)$geneArray['expressionChange'];
        $geneDto->commentCause = $this->prepareCommentCauses($geneArray);
        $geneDto->aliases = $geneArray['aliases'] ? explode(' ', str_replace(',', '', $geneArray['aliases'])) : [];
        $geneDto->source = $this->prepareSource($geneArray['source']);
        $geneDto->functionalClusters = $this->mapFunctionalClusterDtos($geneArray['functional_clusters']);
        $geneDto->timestamp = $this->prepareTimestamp($geneArray);
        $geneDto->ensembl = (string)$geneArray['ensembl'];
        $geneDto->methylationCorrelation = $this->prepareMethylation($geneArray, $lang);
        unset($geneDto->terms);
        return $geneDto;
    }

    public function mapListViewWithTermsDto(array $geneArray, string $lang): GeneListViewDto
    {
        $geneDto = $this->mapListViewDto($geneArray, $lang);
        $termsArray = explode('||', $geneArray['go_terms']);
        $geneTerms = [
            'biological_process' => [],
            'cellular_component' => [],
            'molecular_activity' => [],
        ];
        if (is_array($termsArray)) {
            foreach ($termsArray as $term) {
                list($identifier, $termName, $category) = explode('|', $term);
                $geneTerms[$category][] = [
                    $identifier => $termName
                ];
            }
        }
        $geneDto->terms = $geneTerms;
        return $geneDto;
    }
    public function mapShortListViewDto(array $geneArray, string $lang): GeneShortListViewDto
    {
        $geneDto = new GeneShortListViewDto();
        $geneDto->id = (int)$geneArray['id'];
        $geneDto->symbol = (string)$geneArray['symbol'];
        $geneDto->name = (string)$geneArray['name'];
        $geneDto->ncbiId = (string)$geneArray['ncbi_id'];
        $geneDto->uniprot = (string)$geneArray['uniprot'];
        $geneDto->ensembl = (string)$geneArray['ensembl'];
        $geneDto->methylationCorrelation = $this->prepareMethylation($geneArray, $lang);
        return $geneDto;
    }
    /**
     * @param string $geneFunctionalClustersString
     * @return FunctionalClusterDto[]
     */
    private function mapFunctionalClusterDtos($geneFunctionalClustersString): array
    {
        $functionalClusterDtos = [];
        if ($geneFunctionalClustersString) {
            $functionalClustersArray = explode('||', $geneFunctionalClustersString);
            foreach ($functionalClustersArray as $functionalCluster) {
                list($id, $name) = explode('|', $functionalCluster);
                $functionalClusterDto = new FunctionalClusterDto();
                $functionalClusterDto->id = (int)$id;
                $functionalClusterDto->name = $name;
                $functionalClusterDtos[] = $functionalClusterDto;
            }
        }

        return $functionalClusterDtos;
    }

    private function mapDiseases($diseasesString): array
    {
        $diseases = [];
        if ($diseasesString) {
            $diseasesArray = explode('##', $diseasesString);
            foreach ($diseasesArray as $diseaseString) {
                list($id, $icdId, $name, $icdName) = explode('|', $diseaseString);
                $diseases[$id] = [
                    'icdCode' => $icdId,
                    'icdName' => $icdName,
                    'name' => $name,
                    'isRare' => null // todo for OG-343
                ];
            }
        }

        return $diseases;
    }

    private function mapDiseaseCategories($diseaseCategoriesString): array
    {
        $diseaseCategories = [];
        if ($diseaseCategoriesString) {
            $diseaseCategoriesArray = explode('##', $diseaseCategoriesString);
            foreach ($diseaseCategoriesArray as $diseaseCategoryString) {
                list($id, $icdCode, $categoryName) = explode('|', $diseaseCategoryString);
                if ($icdCode) {
                    $diseaseCategories[$id] = [
                        'icdCode' => $icdCode,
                        'icdCategoryName' => $categoryName,
                    ];
                }
            }
        }

        return $diseaseCategories;
    }

    private function prepareOrthologs($orthologsString): array
    {
        $result = [];
        $orthologs = explode(';', $orthologsString);
        foreach ($orthologs as $orthologString) {
            if (strpos($orthologString, ',')) {
                list($organism, $ortholog) = explode(',', $orthologString);
                $result[$organism] = $ortholog;
            } else {
                $result[$orthologString] = '';
            }
        }
        return $result;
    }

    private function prepareOrigin($geneArray)
    {
        if (!$geneArray['phylum_id']) {
            return null;
        }
        $phylum = new PhylumDto();
        $phylum->id = (int)$geneArray['phylum_id'];
        $phylum->age = (string)$geneArray['phylum_age'];
        $phylum->phylum = (string)$geneArray['phylum_name'];
        $phylum->order = (int)$geneArray['phylum_order'];
        return $phylum;
    }

    private function prepareFamilyOrigin($geneArray)
    {
        if (!$geneArray['family_phylum_id']) {
            return null;
        }
        $phylum = new PhylumDto();
        $phylum->id = (int)$geneArray['family_phylum_id'];
        $phylum->age = (string)$geneArray['family_phylum_age'];
        $phylum->phylum = (string)$geneArray['family_phylum_name'];
        $phylum->order = (int)$geneArray['family_phylum_order'];
        return $phylum;
    }

    private function prepareTimestamp($geneArray): array
    {
        return ['changed' => $geneArray['updated_at'], 'created' => $geneArray['created_at']];
    }

    private function prepareMethylation($geneArray, $lang): string
    {
        $methylationCorrelation = $lang == 'en-US' ? [
            0 => 'negative',
            1 => 'positive',
        ] : [
            0 => 'отрицательная',
            1 => 'положительная',
        ];
        return isset($geneArray['methylation_horvath']) && isset($methylationCorrelation[$geneArray['methylation_horvath']])
            ? $methylationCorrelation[$geneArray['methylation_horvath']] : '';
    }

    private function prepareCommentCauses($geneArray): array
    {
        $commentCauses = [];
        $geneCommentCausesStrings = $geneArray['comment_cause'] ? explode('||', $geneArray['comment_cause']) : [];
        foreach ($geneCommentCausesStrings as $geneCommentCausesString) {
            list($id, $name) = explode('|', $geneCommentCausesString);
            $commentCauses[$id] = $name;
        }

        return $commentCauses;
    }
    
    private function prepareAgingMechanisms($geneArray): array
    {
        $agingMechanisms = [];
        $geneAgingMechanismsStrings = $geneArray['aging_mechanisms'] ? explode('||', $geneArray['aging_mechanisms']) : [];
        foreach ($geneAgingMechanismsStrings as $geneAgingMechanismString) {
            list($id, $name) = explode('|', $geneAgingMechanismString);
            $agingMechanisms[] = ['id' => (int)$id, 'name' => trim($name)];
        }

        return $agingMechanisms;
    }
    
    private function prepareProteinClasses($geneArray): array
    {
        $proteinClasses = [];
        $proteinClassesStrings = $geneArray['aging_mechanisms'] ? explode('||', $geneArray['protein_class']) : [];
        foreach ($proteinClassesStrings as $proteinClassesString) {
            list($id, $name) = explode('|', $proteinClassesString);
            $proteinClasses[] = ['id' => (int)$id, 'name' => trim($name)];
        }

        return $proteinClasses;
    }

    private function prepareSource($geneArray): array
    {
        return $geneArray ? explode('||', $geneArray) : [];
    }
}