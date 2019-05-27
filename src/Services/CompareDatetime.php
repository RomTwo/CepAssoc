<?php

namespace App\Services;

class CompareDatetime
{
    public function isSuperior($start, $end)
    {
        if (is_string($start) && is_string($end)) {
            $start = new \DateTime($start);
            $end = new \DateTime($end);
        }
        return ($start <= $end) ? true : false;
    }
}