<?php

declare(strict_types=1);

namespace App;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ComponentAttributeBag;
use Livewire\WireDirective;

final readonly class BladeSupport
{
    public function __construct(protected ComponentAttributeBag $attributes)
    {
        //
    }

    public function entangle(): string
    {
        if (! ($wire = $this->wire()) instanceof WireDirective) {
            return Blade::render('null');
        }

        $property = $wire->value();

        return $wire->hasModifier('live') || $wire->hasModifier('blur')
            ? Blade::render("@entangle('{$property}').live")
            : Blade::render("@entangle('{$property}')");
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
