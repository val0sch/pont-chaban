<?php

namespace App\Service;

use DateTime;

class DateService
{
    public function createFictiveDate()
    {
        $today = mktime(13, 14, 54, 10, 10, 2022);
        return $today;
    }

    public function reOpeningDate($array): array
    {
        $endOfDay = "23:59";
        $endOfDay = date('g:ia', strtotime($endOfDay));
        $endOfDay = DateTime::createFromFormat('h:i a', $endOfDay);

        foreach ($array as $data) {
            $closingTime = $data["fermeture_a_la_circulation"];
            $openingTime = $data["re_ouverture_a_la_circulation"];
            $passageDate = $data["date_passage"];
            $data['date_ouverture'] = "";
            $timestamp = $data['date_passage'] . " " . $data['fermeture_a_la_circulation'];
            $timestamp = strtotime($timestamp);
            $data['date_timestamp'] = $timestamp;

            if ($openingTime > $closingTime && $openingTime < $endOfDay) {
                $openingDate = $passageDate;
            } else {
                $openingDate = new DateTime($passageDate);
                $openingDate = $openingDate->modify('+1 day');
                $openingDate = $openingDate->format('Y-m-d');
            }

            $data['date_ouverture'] = $openingDate;
            $newDatas[] = $data;
        }

        return $newDatas;
    }

}
