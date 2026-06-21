<?php

namespace App\Services;

use App\Mail\RegistrationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendRegistrationCredentials(User $user, ?string $tempPassword = null): bool
    {
        if (! filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        Mail::to($user->email)->send(new RegistrationMail([
            'title' => 'Creacion de cuenta Semilla Digital',
            'body' => 'Saludos desde ',
            'user' => $user,
            'temp_password' => $tempPassword,
        ]));

        return true;
    }
}
