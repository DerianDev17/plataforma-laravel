<?php

namespace Tests\Unit\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentDeadlineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_payment_deadline_returns_negative_one_when_payment_day_is_null()
    {
        $user = User::factory()->make(['payment_day' => null]);

        $this->assertEquals(-1, $user->getPaymentDeadline());
    }

    /** @test */
    public function get_payment_deadline_returns_negative_one_when_payment_day_is_empty()
    {
        $user = User::factory()->make(['payment_day' => '']);

        $this->assertEquals(-1, $user->getPaymentDeadline());
    }

    /** @test */
    public function get_payment_deadline_extracts_last_number_from_string()
    {
        $user = User::factory()->make(['payment_day' => 'Pago hasta el dia 25 del mes']);

        $this->assertEquals(25, $user->getPaymentDeadline());
    }

    /** @test */
    public function get_payment_deadline_returns_negative_one_when_no_numbers_present()
    {
        $user = User::factory()->make(['payment_day' => 'sin fecha definida']);

        $this->assertEquals(-1, $user->getPaymentDeadline());
    }

    /** @test */
    public function get_payment_deadline_extracts_last_number_when_multiple_numbers()
    {
        $user = User::factory()->make(['payment_day' => 'dia 5 al 25']);

        $this->assertEquals(25, $user->getPaymentDeadline());
    }

    /** @test */
    public function adeuda_returns_true_when_payment_is_overdue()
    {
        $user = User::factory()->make([
            'payment_status' => 'overdue',
            'status' => 1,
        ]);

        $this->assertTrue($user->adeuda());
    }

    /** @test */
    public function adeuda_returns_false_when_payment_is_paid()
    {
        $user = User::factory()->make([
            'payment_status' => 'paid',
            'status' => 1,
        ]);

        $this->assertFalse($user->adeuda());
    }

    /** @test */
    public function effective_payment_status_uses_explicit_payment_status()
    {
        $user = User::factory()->make([
            'payment_status' => 'scholarship',
            'status' => 0,
        ]);

        $this->assertEquals('scholarship', $user->effective_payment_status);
    }

    /** @test */
    public function effective_payment_status_falls_back_to_status_column_when_payment_status_null()
    {
        $user = User::factory()->make([
            'payment_status' => null,
            'status' => 1,
        ]);

        $this->assertEquals('paid', $user->effective_payment_status);
    }

    /** @test */
    public function effective_payment_status_falls_back_to_overdue_when_status_is_zero()
    {
        $user = User::factory()->make([
            'payment_status' => null,
            'status' => 0,
        ]);

        $this->assertEquals('overdue', $user->effective_payment_status);
    }

    /** @test */
    public function can_access_live_classes_returns_true_for_paid_students()
    {
        $user = User::factory()->make(['payment_status' => 'paid']);

        $this->assertTrue($user->canAccessLiveClasses());
    }

    /** @test */
    public function can_access_live_classes_returns_true_for_scholarship_students()
    {
        $user = User::factory()->make(['payment_status' => 'scholarship']);

        $this->assertTrue($user->canAccessLiveClasses());
    }

    /** @test */
    public function can_access_live_classes_returns_false_for_overdue_students()
    {
        $user = User::factory()->make(['payment_status' => 'overdue']);

        $this->assertFalse($user->canAccessLiveClasses());
    }

    /** @test */
    public function normalize_payment_status_defaults_to_pending_for_empty_value()
    {
        $this->assertEquals('pending', User::normalizePaymentStatus(''));
    }

    /** @test */
    public function normalize_payment_status_converts_spanish_pagado_to_paid()
    {
        $this->assertEquals('paid', User::normalizePaymentStatus('pagado'));
    }

    /** @test */
    public function normalize_payment_status_converts_vencido_to_overdue()
    {
        $this->assertEquals('overdue', User::normalizePaymentStatus('vencido'));
    }

    /** @test */
    public function normalize_payment_status_converts_becado_to_scholarship()
    {
        $this->assertEquals('scholarship', User::normalizePaymentStatus('becado'));
    }

    /** @test */
    public function normalize_payment_status_converts_numeric_one_to_paid()
    {
        $this->assertEquals('paid', User::normalizePaymentStatus('1'));
    }

    /** @test */
    public function normalize_payment_status_converts_numeric_zero_to_overdue()
    {
        $this->assertEquals('overdue', User::normalizePaymentStatus('0'));
    }

    /** @test */
    public function normalize_payment_status_passes_through_valid_status()
    {
        $this->assertEquals('pending', User::normalizePaymentStatus('pending'));
    }

    /** @test */
    public function get_payment_status_label_returns_spanish_label()
    {
        $user = User::factory()->make(['payment_status' => 'paid']);

        $this->assertEquals('Pagado', $user->payment_status_label);
    }

    /** @test */
    public function diapago_uses_payment_deadline_when_diapago_is_null()
    {
        $user = User::factory()->make([
            'diapago' => null,
            'payment_day' => '15',
        ]);

        $this->assertEquals(15, $user->diapago());
    }

    /** @test */
    public function diapago_uses_explicit_diapago_when_set()
    {
        $user = User::factory()->make([
            'diapago' => 28,
            'payment_day' => '15',
        ]);

        $this->assertEquals(28, $user->diapago());
    }
}
