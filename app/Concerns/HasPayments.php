<?php

namespace App\Concerns;

use App\Services\Payment\PaymentAccessService;

trait HasPayments
{
    public function getPaymentDeadline()
    {
        if (is_null($this->payment_day) || trim($this->payment_day) === '') {
            return -1;
        }
        preg_match_all('!\d+!', $this->payment_day, $matches);

        if (empty($matches[0])) {
            return -1;
        }

        return (int) end($matches[0]);
    }

    public function adeuda()
    {
        return app(PaymentAccessService::class)->effectiveStatus($this) === 'overdue';
    }

    public function canAccessLiveClasses()
    {
        return app(PaymentAccessService::class)->canAccess($this);
    }

    public function getPaymentStatusLabelAttribute()
    {
        return app(PaymentAccessService::class)->resolve($this)->label();
    }

    public function getEffectivePaymentStatusAttribute()
    {
        return app(PaymentAccessService::class)->effectiveStatus($this);
    }

    public static function paymentStatusOptions()
    {
        return PaymentAccessService::statusOptions();
    }

    public static function normalizePaymentStatus($value, $default = 'pending')
    {
        return PaymentAccessService::normalizeStatus($value, $default);
    }

    public static function paymentStatusAllowsAccess($paymentStatus)
    {
        return PaymentAccessService::statusAllowsAccess($paymentStatus);
    }

    public function diapago()
    {
        if (
            array_key_exists('diapago', $this->attributes)
            && ! is_null($this->attributes['diapago'])
            && trim($this->attributes['diapago']) !== ''
        ) {
            return (int) $this->attributes['diapago'];
        }

        return $this->getPaymentDeadline();
    }
}
