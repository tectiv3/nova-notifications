<?php

namespace Christophrumpel\NovaNotifications\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NotificationStatsController
{
    public function index()
    {
        return [
            'all' => $this->getNotificationsCount(),
            'failed' => $this->getFailedNotificationsCount()
        ];
    }

    private function getNotificationsCount()
    {
        return DB::table('notifications')->count();
    }

    private function getFailedNotificationsCount()
    {
        return DB::table('notifications')
            ->where('failed', true)
            ->count();
    }
}
