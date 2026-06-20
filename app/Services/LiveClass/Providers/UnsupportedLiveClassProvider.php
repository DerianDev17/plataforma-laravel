<?php

namespace App\Services\LiveClass\Providers;

use App\Models\User;
use App\Services\LiveClass\Contracts\LiveClassProvider;
use App\Services\LiveClass\LiveClassOperationResult;
use App\Services\LiveClass\LiveClassSyncResult;

class UnsupportedLiveClassProvider implements LiveClassProvider
{
    private $provider;

    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    public function label(): string
    {
        return $this->provider;
    }

    public function registerStudent(User $student): LiveClassOperationResult
    {
        return LiveClassOperationResult::failure(
            'El proveedor de clases "' . $this->provider . '" no esta implementado.'
        );
    }

    public function syncAccessLinks(): LiveClassSyncResult
    {
        return LiveClassSyncResult::completed(0, 'Proveedor no implementado.', [
            'El proveedor de clases "' . $this->provider . '" no esta implementado.',
        ]);
    }
}
