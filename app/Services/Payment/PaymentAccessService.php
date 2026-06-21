<?php

namespace App\Services\Payment;

use App\Models\User;
use App\Services\Payment\Contracts\PaymentAccessResolver;

class PaymentAccessService
{
    public const PAYMENT_STATUSES = ['paid', 'pending', 'overdue', 'scholarship'];

    public const ACCESS_STATUSES = ['paid', 'pending', 'scholarship'];

    public const STATUS_LABELS = [
        'paid' => 'Pagado',
        'pending' => 'Pendiente',
        'overdue' => 'Vencido',
        'scholarship' => 'Becado',
    ];

    private const NORMALIZATION_MAP = [
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

    public function __construct(private PaymentAccessResolver $resolver) {}

    public function resolve(User $user): PaymentAccessResult
    {
        return $this->resolver->resolve($user);
    }

    public function canAccess(User $user): bool
    {
        return $this->resolve($user)->allowed();
    }

    public function effectiveStatus(User $user): string
    {
        return $this->resolve($user)->status();
    }

    public static function normalizeStatus($value, ?string $default = 'pending'): string
    {
        $value = trim(mb_strtolower((string) $value));

        if ($value === '') {
            return $default ?? 'pending';
        }

        if (isset(self::NORMALIZATION_MAP[$value])) {
            return self::NORMALIZATION_MAP[$value];
        }

        if (in_array($value, self::PAYMENT_STATUSES, true)) {
            return $value;
        }

        return $default ?? 'pending';
    }

    public static function statusAllowsAccess($paymentStatus): bool
    {
        return in_array(
            self::normalizeStatus($paymentStatus, 'overdue'),
            self::ACCESS_STATUSES,
            true
        );
    }

    public static function statusOptions(): array
    {
        return self::STATUS_LABELS;
    }

    public static function statusLabel(string $status): string
    {
        return self::STATUS_LABELS[$status] ?? $status;
    }
}
