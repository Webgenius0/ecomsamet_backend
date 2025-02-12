<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class HairDresserNotificationController extends Controller
{
    public function notification(){

        $natification = FacadesAuth::user()->notifications;

        $formatNotification = $natification->map(function ($notification) {

            return [
                'title' => $notification->data['title'],
                'message' => $notification->data['message'],
            ];
        });

        return ApiResponse::format(true,200,'The following notifications',[

            $formatNotification]);

    }

    public function readNotification($id){


    }
}
