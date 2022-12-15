<?php

namespace App\Service;

use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApi
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

    public function getAllDatas(): array
    {
        return $this->getApi();
    }

    public function reOpeningDate(): array
    {
        $datas = $this->getAllDatas();
        $datas = $datas['records'];

        $endOfDay = "23:59";
        $endOfDay = date('g:ia', strtotime($endOfDay));
        $endOfDay = DateTime::createFromFormat('h:i a', $endOfDay);

        foreach ($datas as $data) {
            $closingTime = $data['fields']["fermeture_a_la_circulation"];
            $openingTime = $data['fields']["re_ouverture_a_la_circulation"];
            $passageDate = $data['fields']["date_passage"];
            $data['fields']['date_ouverture'] = "";

            if ($openingTime > $closingTime && $openingTime < $endOfDay) {
                $openingDate = $passageDate;
            } else {
                $openingDate = new DateTime($passageDate);
                $openingDate = $openingDate->modify('+1 day');
                $openingDate = $openingDate->format('Y-m-d');
            }

            $data['fields']['date_ouverture'] = $openingDate;
            $arrayNewDatas[] = $data;
        }
        return $arrayNewDatas;
    }
}
