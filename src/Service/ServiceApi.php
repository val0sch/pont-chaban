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

    private function getApi()
    {
        $response = $this->client->request(
            'GET',
            'https://opendata.bordeaux-metropole.fr/api/records/1.0/search/?dataset=previsions_pont_chaban&q=&rows=100&facet=bateau'
        );

        return $response->toArray();
    }

    public function getAllDatas()
    {
        $datas = $this->getApi()['records'];

        $today = new FictiveDate();
        $today = $today->createFictiveDate();
        $today =  date("Y-m-d", $today);

        $arrayNewDatas = array();

        foreach ($datas as $data) {
            $passageDate = $data['fields']["date_passage"];
            if ($passageDate >= $today) {
                $arrayNewDatas[] = $data;
            }
        }

        $newDatas = new ReopeningGate();
        $newDatas = $newDatas->reOpeningDate($arrayNewDatas);

        return $newDatas;
    }
}
