<?php

namespace App\Services;

use App\Models\Year;

class YearService
{

    public function getYears()
    {
        $years = Year::all();

        return $years;
    }

}
