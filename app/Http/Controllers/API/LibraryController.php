<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LibraryResource;
use App\Services\LibraryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    use ApiResponse;

    private LibraryService $libraryService;

    public function __construct(LibraryService $libraryService)
    {
        $this->libraryService = $libraryService;
    }

    public function getLibraries()
    {
        $data = $this->libraryService->getLibraries();
        return $this->success("Get Libraries successfully", LibraryResource::collection($data));

    }
}
