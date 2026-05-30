<?php

declare(strict_types=1);

namespace App\Support\Modal;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector as BaseRedirector;

final class Redirector extends BaseRedirector
{
    /**
     * {@inheritDoc}
     */
    public function back($status = 302, $headers = [], $fallback = false): RedirectResponse
    {
        if ($baseUrl = $this->generator->getRequest()->header(Modal::HEADER_BASE_URL)) {
            return $this->createRedirect($this->generator->to($baseUrl), $status, $headers);
        }

        return parent::back($status, $headers, $fallback);
    }
}
