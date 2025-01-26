<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

final class LegalController
{
    public function __invoke(string $page): View
    {
        $file = $this->localizedMarkdownPath("$page.md");

        abort_if($file === null, 404);

        $content = Str::markdown(File::get($file));

        return view('legal', ['content' => $content]);
    }

    protected function localizedMarkdownPath(string $name): ?string
    {
        $localName = preg_replace('#(\.md)$#i', '/'.app()->getLocale().'$1', $name);

        return Arr::first([
            resource_path("markdown/$localName"),
            resource_path("markdown/$name"),
        ], fn (string $path): bool => file_exists($path));
    }
}
