<?php

namespace App\Controllers;

use App\Core\AdminNotifications;
use App\Core\Auth;

class AdminNotificationController
{
    public static function feed(): void
    {
        Auth::require();
        header('Content-Type: application/json');
        echo json_encode([
            'count' => AdminNotifications::count(),
            'items' => AdminNotifications::items(),
        ]);
    }

    public static function markInquiryRead(int $id): void
    {
        Auth::require();
        verify_csrf();
        AdminNotifications::markContactRead($id);
        header('Content-Type: application/json');
        echo json_encode(['ok' => true, 'count' => AdminNotifications::count()]);
    }
}
