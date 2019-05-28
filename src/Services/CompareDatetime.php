<?php

namespace App\Services;

class CompareDatetime
{
    public function isSuperior($start, $end)
    {
        if (is_string($start)) {
            $start = new \DateTime($start);
        }
        if (is_string($end)) {
            $end = new \DateTime($end);
        }
        return ($start <= $end) ? true : false;
    }
}