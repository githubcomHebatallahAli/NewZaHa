<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{

    public function showAll(){
        $this->authorize('manage_users');
        $notifications = Auth::user()->notifications;
        return response()->json([
          "notifications" => $notifications
        ]);
    }


    public function unread(){
        $this->authorize('manage_users');
        $notifications = Auth::user();
        return response()->json([
            "notifications"=> $notifications->unreadNotifications
        ]);
    }

    public function markReadAll(){
        $this->authorize('manage_users');
        $notifications = Auth::user();
        foreach ($notifications->unreadNotifications as $notification){
            $notification->markAsRead();
        }
        return response()->json([
            "message"=> "Read All Successfully"
        ]);
    }

    public function deleteAll(){
        $this->authorize('manage_users');
        $notifications = Auth::user();
        $notifications->notifications()->delete();
        return response()->json([
            "message"=> "All Notifications Deleted Successfully"
        ]);
    }


    public function delete(string $id){
        DB::table('notifications')->where('id',$id)->delete();
        return response()->json([
            "message"=> "Notification Deleted By Id Successfully"
        ]);


    }

}

