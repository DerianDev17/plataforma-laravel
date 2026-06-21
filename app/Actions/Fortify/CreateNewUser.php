<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\Registration\UserRegistrationService;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function __construct(private UserRegistrationService $registration) {}

    public function create(array $input): User
    {
        return $this->registration->register($input, Auth::user());
    }
}
