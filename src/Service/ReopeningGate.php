<?php

namespace App\Service;

use DateTime;

class ReopeningGate
{

    public function reOpeningDate($array): array
    {
        $endOfDay = "23:59";
        $endOfDay = date('g:ia', strtotime($endOfDay));
        $endOfDay = DateTime::createFromFormat('h:i a', $endOfDay);

        foreach ($array as $data) {
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
            $newDatas[] = $data;
        }

        return $newDatas;
    }
}
