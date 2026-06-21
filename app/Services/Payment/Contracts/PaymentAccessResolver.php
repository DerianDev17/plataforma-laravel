<?php

namespace App\Services\Payment\Contracts;

use App\Models\User;
use App\Services\Payment\PaymentAccessResult;

interface PaymentAccessResolver
{
    public function resolve(User $user): PaymentAccessResult;
}
