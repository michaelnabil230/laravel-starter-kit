<?php

declare(strict_types=1);

namespace App\Traits;

trait SendToasts
{
    /**
     * Send an toast success.
     *
     * @param  array<string, bool|float|int|string>  $parameters
     */
    protected function success(string $message, array $parameters = [], ?string $locale = null): void
    {
        $this->sendToast('success', $message, $parameters, $locale);
    }

    /**
     * Send an toast error.
     *
     * @param  array<string, bool|float|int|string>  $parameters
     */
    protected function error(string $message, array $parameters = [], ?string $locale = null): void
    {
        $this->sendToast('error', $message, $parameters, $locale);
    }

    /**
     * Send an toast warning.
     *
     * @param  array<string, bool|float|int|string>  $parameters
     */
    protected function warning(string $message, array $parameters = [], ?string $locale = null): void
    {
        $this->sendToast('warning', $message, $parameters, $locale);
    }

    /**
     * Send an toast info.
     *
     * @param  array<string, bool|float|int|string>  $parameters
     */
    protected function info(string $message, array $parameters = [], ?string $locale = null): void
    {
        $this->sendToast('info', $message, $parameters, $locale);
    }

    /**
     * Send an toast general.
     *
     * @param  array<string, bool|float|int|string>  $parameters
     */
    protected function sendToast(string $type, string $message, array $parameters, ?string $locale): void
    {
        session()->flash('toast', [
            'message' => __($message, $parameters, $locale),
            'type' => $type,
        ]);
    }
}
