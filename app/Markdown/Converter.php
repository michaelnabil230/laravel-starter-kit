<?php

declare(strict_types=1);

namespace App\Markdown;

interface Converter
{
    public function toHtml(string $markdown): string;
}
