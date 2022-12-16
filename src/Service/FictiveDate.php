<?php

namespace App\Service;



class FictiveDate
{
    public function createFictiveDate()
    {
        $today = mktime(11, 14, 54, 10, 10, 2022);
        return $today;
    }
}
