<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Blade as BladeRender;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

final readonly class Blade
{
    public function __construct(protected ComponentAttributeBag $attributes)
    {
        //
    }

    public function entangle(): string
    {
        if (! ($wire = $this->wire()) instanceof WireDirective) {
            return BladeRender::render('null');
        }

        $property = $wire->value();

        return $wire->hasModifier('live') || $wire->hasModifier('blur')
            ? BladeRender::render("@entangle('{$property}').live")
            : BladeRender::render("@entangle('{$property}')");
    }

    protected function wire(): ?WireDirective
    {
        /** @var WireDirective $wire */
        $wire = $this->attributes->wire('model');

        if (! $wire->directive() && ! $wire->value()) {
            return null;
        }

        return $wire;
    }
}
