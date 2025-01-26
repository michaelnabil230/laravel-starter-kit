<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $this->setApplicationLocale('en');
        parent::setUp();
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }

    protected function setApplicationLocale(string $locale): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'='.$locale);
    }
}
