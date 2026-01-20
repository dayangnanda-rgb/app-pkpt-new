<?php

namespace App\Cells;

use App\Models\ProgramKerjaModel;

class NotificationCell
{
    public function show()
    {
        $model = new ProgramKerjaModel();
        $notifications = $model->getNotificationActivities();
        
        return view('cells/notification', [
            'notifications' => $notifications,
            'count'         => count($notifications)
        ]);
    }
}
