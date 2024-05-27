<?php

namespace App\Services;

use App\Models\Library;

class LibraryService
{
    public function getLibraries()
    {
        $lib = Library::all();

        return $lib;
    }
}
