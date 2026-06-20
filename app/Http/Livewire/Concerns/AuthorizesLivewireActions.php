<?php

namespace App\Http\Livewire\Concerns;

trait AuthorizesLivewireActions
{
    protected function authorizeAbility(string $ability): void
    {
        abort_unless(auth()->check() && auth()->user()->can($ability), 403);
    }
}
