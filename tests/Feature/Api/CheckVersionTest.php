<?php

declare(strict_types=1);

use function Pest\Laravel\postJson;

it('returns force_update true when request version is older than current version', function () {
    config()->set('apps.version', '1.0.26');
    $currentVersion = config('apps.version');

    postJson(route('api.check-version'), [
        'version' => '1.0.0',
        'platform' => 'android',
    ])->assertJson([
        'force_update' => true,
        'current_version' => $currentVersion,
    ]);
});

it('returns force_update false when request version is newer than current version', function () {
    config()->set('apps.version', '1.0.26');
    $currentVersion = config('apps.version');

    postJson(route('api.check-version'), [
        'version' => '1.0.27',
        'platform' => 'android',
    ])->assertJson([
        'force_update' => false,
        'current_version' => $currentVersion,
    ]);
});

it('returns force_update false when request version is equal to current version', function () {
    config()->set('apps.version', '1.0.26');
    $currentVersion = config('apps.version');

    postJson(route('api.check-version'), [
        'version' => '1.0.26',
        'platform' => 'android',
    ])->assertJson([
        'force_update' => false,
        'current_version' => $currentVersion,
    ]);
});
