<?php

namespace App\Services\Registration\Contracts;

use App\Models\User;

interface UserRegistrar
{
    public function register(array $data): User;
}
