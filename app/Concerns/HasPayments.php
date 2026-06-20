<?php

namespace App\Concerns;

trait HasPayments
{
    public const PAYMENT_STATUSES = ['paid', 'pending', 'overdue', 'scholarship'];
    public const LIVE_CLASS_PAYMENT_STATUSES = ['paid', 'pending', 'scholarship'];
    public const PAYMENT_STATUS_LABELS = [
        'paid' => 'Pagado',
        'pending' => 'Pendiente',
        'overdue' => 'Vencido',
        'scholarship' => 'Becado',
    ];

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
        return $this->effective_payment_status === 'overdue';
    }

    public function canAccessLiveClasses()
    {
        return self::paymentStatusAllowsAccess($this->effective_payment_status);
    }

    public function getPaymentStatusLabelAttribute()
    {
        return self::PAYMENT_STATUS_LABELS[$this->effective_payment_status] ?? $this->effective_payment_status;
    }

    public function getEffectivePaymentStatusAttribute()
    {
        if (array_key_exists('payment_status', $this->attributes) && $this->payment_status !== null) {
            return $this->payment_status;
        }

        return (int) $this->status === 1 ? 'paid' : 'overdue';
    }

    public static function paymentStatusOptions()
    {
        return self::PAYMENT_STATUS_LABELS;
    }

    public static function normalizePaymentStatus($value, $default = 'pending')
    {
        $value = trim(mb_strtolower((string) $value));

        if ($value === '') {
            return $default;
        }

        $map = [
            '1' => 'paid',
            'true' => 'paid',
            'si' => 'paid',
            'yes' => 'paid',
            'pagado' => 'paid',
            'paid' => 'paid',
            'al dia' => 'paid',
            'aldia' => 'paid',
            '0' => 'overdue',
            'false' => 'overdue',
            'no' => 'overdue',
            'vencido' => 'overdue',
            'bloqueado' => 'overdue',
            'adeuda' => 'overdue',
            'debe' => 'overdue',
            'overdue' => 'overdue',
            'pendiente' => 'pending',
            'pending' => 'pending',
            'becado' => 'scholarship',
            'beca' => 'scholarship',
            'scholarship' => 'scholarship',
        ];

        if (isset($map[$value])) {
            return $map[$value];
        }

        return in_array($value, self::PAYMENT_STATUSES, true) ? $value : $default;
    }

    public static function paymentStatusAllowsAccess($paymentStatus)
    {
        return in_array(
            self::normalizePaymentStatus($paymentStatus, 'overdue'),
            self::LIVE_CLASS_PAYMENT_STATUSES,
            true
        );
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
