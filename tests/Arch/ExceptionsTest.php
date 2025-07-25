<?php

declare(strict_types=1);

arch('exceptions')
    ->expect('App\Exceptions')
    ->toImplement('Throwable')
    ->toOnlyBeUsedIn([
        'App\Console\Commands',
        'App\Http\Controllers',
        'App\Livewire',
        'App\Services',
        'App\Traits',
        'App\Models\Scopes',
        'App\Models\TemporaryUpload',
    ]);
