<?php

namespace App\Service;

class FilterService
{
    // Cette fonction retourne un tableau qui retire les doublons
    public function selectReason($arrays)
    {
        foreach ($arrays as $array) {
            $reasons[] = $array['bateau'];
        }
        $reasons = array_unique($reasons);
        return $reasons;
    }
}
