<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ServiceApi
{
    private $client;
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    // Cette fonction retourne un tableau qui contient toutes les données de l'Api
    private function getApi(): array
    {
        $response = $this->client->request(
            'GET',
            'https://opendata.bordeaux-metropole.fr/api/records/1.0/search/?dataset=previsions_pont_chaban&q=&rows=100&facet=bateau'
        );

        return $response->toArray();
    }


    // Cette fonction retourne un tableau qui contient toutes les données traitées pour l'affichage
    public function getAllDatas(): array
    {
        //Je récupère les données de l'API
        $datas = $this->getApi()['records'];

        //J'attribue à une variable $today une date fictive 
        $today = new DateService();
        $today = $today->createFictiveDate();
        $today =  date("Y-m-d", $today);

        // Je souhaite récupérer passage de bateaux à partir de la date fictive
        foreach ($datas as $data) {
            $passageDate = $data['fields']["date_passage"];
            if ($passageDate >= $today) {
                $arrayNewDatas[] = $data['fields'];
            }
        }
        // Je modifie la date de réouverture pour les passages
        //  où le pont réouvre le lendemain de la date de passage
        $newDatas = new DateService();
        $newDatas = $newDatas->reOpeningDate($arrayNewDatas);

        // Je trie par ordre chronologique selon la date de passage
        $sortedDatas = $this->sortArrayMulti($newDatas, 'date_timestamp');

        return $sortedDatas;
    }

    //Fonction pour trier un tableau multi-dimensionnel via une clé
    public function sortArrayMulti($array, $key): array
    {
        $keys = array_column($array, $key);
        array_multisort($keys, SORT_ASC, $array);
        return $array;
    }
}
