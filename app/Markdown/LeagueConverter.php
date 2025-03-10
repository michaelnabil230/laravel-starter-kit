<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\MarkdownConverter;

final readonly class LeagueConverter implements Converter
{
    public function __construct(private MarkdownConverter $converter) {}

    public function toHtml(string $markdown): string
    {
        return $this->converter->convert($markdown)->getContent();
    }
}
