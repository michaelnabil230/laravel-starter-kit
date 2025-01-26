<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\Notification\NotificationCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class NotificationController
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = auth('api')->user();

        $notifications = $user->notifications()->paginate();

        if ($request->get('page', '1') === '1') {
            $user->unreadNotifications()->update(['read_at' => now()]);
        }

        return response()->json([
            'notifications' => NotificationCollection::make($notifications),
        ]);
    }
}
