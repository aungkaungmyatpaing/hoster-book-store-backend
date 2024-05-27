<?php

namespace App\Services;

use App\Models\UserNotification;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class UserNotificationService
{
    public function getNoti($filter)
    {
        $query = UserNotification::query()
        ->when(isset($filter['user_id']), function ($q) use ($filter) {
            $q->where('user_id', $filter['user_id']);
        })
        ->when(isset($filter['new']), function ($q) use ($filter) {
            $q->where('new', $filter['new']);
        });

        $count = $query->count();
        $perPage = $filter['limit'] ?? 20;
        $data = $query->paginate($perPage);

        return [
            'data' => $data,
            'count' => $count,
        ];

    }

    public function updateNoti($filter)
    {
        if (isset($filter['id'])) {
            $query = UserNotification::find($filter['id']);
            if ($query) {
                $query->new = $filter['new'];
                $query->save();
            }else{
                throw new ResourceNotFoundException('User not found');
            }
        }else{
            $query = UserNotification::where('user_id', $filter['user_id'])->get();

            foreach ($query as $value) {
                $value->new = $filter['new'];
                $value->save();
            }
        }


        return $query;
    }

    public function deleteNoti($filter)
    {
        if (isset($filter['id'])) {
            $query = UserNotification::find($filter['id']);
            if ($query) {
                $query->delete();
            }else{
                throw new ResourceNotFoundException('User not found');
            }
        }else{
            $query = UserNotification::where('user_id', $filter['user_id'])->get();

            foreach ($query as $value) {
                $value->delete();
            }
        }


        return $query;
    }
}
