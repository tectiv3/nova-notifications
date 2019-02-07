<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class NotificationController extends ApiController
{
    public function index()
    {
        return [
            'data' => DB::table('notifications')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $notificationClass = 'App\Notifications\Announcement';
        if (!class_exists($notificationClass)) {
            return response('', 400);
        }

        $body = $request->get('notification_body');
        try {
            $notification = new $notificationClass($body);
        } catch (\Throwable $e) {
            return response(
                __('The notification could not be created with the provided information'),
                422
            );
        }

        $notifiable = str_replace('.', '\\', $request->get('notifiable')['name']);
        $id = $request->get('notifiable')['value'];
        if ($id) {
            $notifiable = $notifiable::findOrFail($id);
        }
        Notification::send($notifiable, $notification);

        return $this->respondSuccess();
    }
}
