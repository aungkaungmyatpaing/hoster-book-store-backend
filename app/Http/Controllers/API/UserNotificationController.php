<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteNotiRequest;
use App\Http\Requests\GetNotiRequest;
use App\Http\Requests\UpdateNotiRequest;
use App\Http\Resources\UserNotificationResource;
use App\Services\UserNotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    use ApiResponse;

    private UserNotificationService $userNotificationService;

    public function __construct(UserNotificationService $userNotificationService)
    {
        $this->userNotificationService = $userNotificationService;
    }

    public function getNoti(GetNotiRequest $request)
    {

        $result = $this->userNotificationService->getNoti($request->validated());
        $noti = $result['data'];
        $count = $result['count'];
        $data = [
            'notifications' => UserNotificationResource::collection($noti),
            'meta' => [
                'current_page' => $noti->currentPage(),
                'last_page' => $noti->lastPage(),
                'total' => $noti->total(),
                'per_page' => $noti->perPage(),
                'count' => $count
            ],
        ];

        return $this->success("Get Noti successful", $data);
    }

    public function updateNoti(UpdateNotiRequest $request)
    {
        $this->userNotificationService->updateNoti($request->validated());

        return $this->success("Make as read successful");

    }

    public function deleteNoti(DeleteNotiRequest $request)
    {
        $this->userNotificationService->deleteNoti($request->validated());

        return $this->success("noti delete successful");

    }
}
