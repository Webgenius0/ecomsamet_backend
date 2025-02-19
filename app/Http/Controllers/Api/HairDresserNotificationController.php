<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class HairDresserNotificationController extends Controller
{    public function notification(){

    $natification = FacadesAuth::user()->notifications;

    $formatNotification = $natification->map(function ($notification) {

        return [
            'id' => $notification->id,
            'title' => $notification->data['title'],
            'message' => $notification->data['message'],
            'read_at' => $notification->read_at
        ];
    });

    return ApiResponse::format(true,200,'The following notifications',[

        $formatNotification]);

}

public function markAllRead(){
 $user = FacadesAuth::user();

 //markall read
 try{
     $user->unreadNotifications->markAsRead();
     return response()->json(['message' => 'All notifications marked as read']);
 }catch(\Exception $e){
     return response()->json(['message' => $e->getMessage()]);
 }

 }

 //single notification read

 public function markSingleRead($id){
    $user = FacadesAuth::user();
    try{
        $notification = $user->notifications()->where('id',$id)->first();
        if($notification){
            $notification->markAsRead();

            $notificationData = [
                'id' => $notification->id,
                'title' => $notification->data['title'] ,
                'message' => $notification->data['message'],
                'read_at' => $notification->read_at
            ];

            return ApiResponse::format(true,200,'Notification is marked as read',[$notificationData]);
        }else{
            return response()->json(['message' => 'Notification not found']);
        }
    }catch(\Exception $e){
        return response()->json(['message' => $e->getMessage()]);
    }
}

}
