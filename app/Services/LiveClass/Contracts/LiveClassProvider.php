<?php

namespace App\Services\LiveClass\Contracts;

use App\Models\User;
use App\Services\LiveClass\LiveClassOperationResult;
use App\Services\LiveClass\LiveClassSyncResult;

interface LiveClassProvider
{
    public function label(): string;

    public function registerStudent(User $student): LiveClassOperationResult;

    public function syncAccessLinks(): LiveClassSyncResult;
}
