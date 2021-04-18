<?php

namespace app\application\service;

interface GeneOntologyServiceInterface
{
    public function mineFromGateway();

    public function mineFromGatewayForGene($id);

    public function getAllWithGenes();

    public function getForGene($id);
}