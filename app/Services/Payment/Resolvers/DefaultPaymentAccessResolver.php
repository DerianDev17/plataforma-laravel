<?php

namespace App\Services\Payment\Resolvers;

use App\Models\User;
use App\Services\Payment\Contracts\PaymentAccessResolver;
use App\Services\Payment\PaymentAccessResult;
use App\Services\Payment\PaymentAccessService;

class DefaultPaymentAccessResolver implements PaymentAccessResolver
{
    public function resolve(User $user): PaymentAccessResult
    {
        $status = $this->effectiveStatus($user);
        $label = PaymentAccessService::statusLabel($status);

        if (in_array($status, PaymentAccessService::ACCESS_STATUSES, true)) {
            return PaymentAccessResult::allow(
                $status,
                $label,
                'El estado de pago permite el acceso.'
            );
        }

        return PaymentAccessResult::deny(
            $status,
            $label,
            'El estado de pago no permite el acceso.'
        );
    }

    private function effectiveStatus(User $user): string
    {
        if (! is_null($user->payment_status)) {
            return $user->payment_status;
        }

        return ((int) $user->status) === 1 ? 'paid' : 'overdue';
    }
}
