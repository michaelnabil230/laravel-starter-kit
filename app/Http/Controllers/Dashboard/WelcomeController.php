<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Inertia\Response;

final class WelcomeController
{
    public function __invoke(): Response
    {
        $usersCount = User::query()->count();

        return inertia('Welcome', [
            'usersCount' => $usersCount,
        ]);
    }
}
