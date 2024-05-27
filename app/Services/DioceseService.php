<?php

namespace App\Services;

use App\Models\Diocese;

class DioceseService
{
    public function getDioceses()
    {
        $dioceses = Diocese::all();

        return $dioceses;
    }
}
