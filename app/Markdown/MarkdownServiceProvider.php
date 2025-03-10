<?php

declare(strict_types=1);

namespace App\Markdown;

use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Mention\MentionExtension;
use League\CommonMark\MarkdownConverter;

final class MarkdownServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Converter::class, function (): LeagueConverter {
            $environment = new Environment([
                'html_input' => 'escape',
                'max_nesting_level' => 10,
                'allow_unsafe_links' => false,
                'external_link' => [
                    'open_in_new_window' => true,
                    'nofollow' => 'external',
                ],
            ]);

            $environment->addExtension(new CommonMarkCoreExtension);
            $environment->addExtension(new GithubFlavoredMarkdownExtension);
            $environment->addExtension(new MentionExtension);
            $environment->addExtension(new ExternalLinkExtension);

            return new LeagueConverter(new MarkdownConverter($environment));
        });
    }
}
