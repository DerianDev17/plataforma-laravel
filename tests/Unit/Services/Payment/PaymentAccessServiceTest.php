<?php

namespace Tests\Unit\Services\Payment;

use App\Models\User;
use App\Services\Payment\Contracts\PaymentAccessResolver;
use App\Services\Payment\PaymentAccessResult;
use App\Services\Payment\PaymentAccessService;
use App\Services\Payment\Resolvers\DefaultPaymentAccessResolver;
use Tests\TestCase;

class PaymentAccessServiceTest extends TestCase
{
    private function service(): PaymentAccessService
    {
        return new PaymentAccessService(new DefaultPaymentAccessResolver);
    }

    private function user(array $attrs = []): User
    {
        return new User(array_merge([
            'status' => true,
            'payment_status' => null,
        ], $attrs));
    }

    /** @test */
    public function resolves_default_resolver_from_container()
    {
        $this->assertInstanceOf(DefaultPaymentAccessResolver::class, app(PaymentAccessResolver::class));
    }

    /** @test */
    public function grants_access_for_paid_status()
    {
        $result = $this->service()->resolve($this->user(['payment_status' => 'paid']));

        $this->assertTrue($result->allowed());
        $this->assertSame('paid', $result->status());
        $this->assertSame('Pagado', $result->label());
    }

    /** @test */
    public function grants_access_for_scholarship()
    {
        $result = $this->service()->resolve($this->user(['payment_status' => 'scholarship']));

        $this->assertTrue($result->allowed());
    }

    /** @test */
    public function grants_access_for_pending()
    {
        $result = $this->service()->resolve($this->user(['payment_status' => 'pending']));

        $this->assertTrue($result->allowed());
    }

    /** @test */
    public function denies_access_for_overdue()
    {
        $result = $this->service()->resolve($this->user(['payment_status' => 'overdue']));

        $this->assertTrue($result->denied());
        $this->assertSame('overdue', $result->status());
        $this->assertSame('Vencido', $result->label());
    }

    /** @test */
    public function falls_back_to_status_column_when_payment_status_is_null()
    {
        $this->assertSame('paid', $this->service()->effectiveStatus($this->user(['status' => 1, 'payment_status' => null])));
        $this->assertSame('overdue', $this->service()->effectiveStatus($this->user(['status' => 0, 'payment_status' => null])));
    }

    /** @test */
    public function payment_status_explicit_takes_precedence_over_status_fallback()
    {
        $result = $this->service()->resolve($this->user(['status' => 0, 'payment_status' => 'paid']));

        $this->assertTrue($result->allowed());
    }

    /** @test */
    public function can_access_returns_bool_for_user()
    {
        $service = $this->service();

        $this->assertTrue($service->canAccess($this->user(['payment_status' => 'paid'])));
        $this->assertFalse($service->canAccess($this->user(['payment_status' => 'overdue'])));
    }

    /** @test */
    public function normalize_status_maps_spanish_aliases()
    {
        $service = $this->service();

        $this->assertSame('paid', $service::normalizeStatus('pagado'));
        $this->assertSame('paid', $service::normalizeStatus('al dia'));
        $this->assertSame('overdue', $service::normalizeStatus('vencido'));
        $this->assertSame('overdue', $service::normalizeStatus('adeuda'));
        $this->assertSame('pending', $service::normalizeStatus('pendiente'));
        $this->assertSame('scholarship', $service::normalizeStatus('becado'));
    }

    /** @test */
    public function normalize_status_maps_numeric_values()
    {
        $service = $this->service();

        $this->assertSame('paid', $service::normalizeStatus(1));
        $this->assertSame('overdue', $service::normalizeStatus(0));
        $this->assertSame('paid', $service::normalizeStatus('1'));
        $this->assertSame('overdue', $service::normalizeStatus('0'));
    }

    /** @test */
    public function normalize_status_uses_default_for_empty_value()
    {
        $service = $this->service();

        $this->assertSame('pending', $service::normalizeStatus(''));
        $this->assertSame('pending', $service::normalizeStatus(null));
        $this->assertSame('overdue', $service::normalizeStatus('', 'overdue'));
    }

    /** @test */
    public function normalize_status_passes_through_known_statuses()
    {
        $service = $this->service();

        $this->assertSame('paid', $service::normalizeStatus('paid'));
        $this->assertSame('overdue', $service::normalizeStatus('overdue'));
        $this->assertSame('pending', $service::normalizeStatus('pending'));
        $this->assertSame('scholarship', $service::normalizeStatus('scholarship'));
    }

    /** @test */
    public function status_allows_access_follows_access_statuses()
    {
        $service = $this->service();

        $this->assertTrue($service::statusAllowsAccess('paid'));
        $this->assertTrue($service::statusAllowsAccess('pending'));
        $this->assertTrue($service::statusAllowsAccess('scholarship'));
        $this->assertFalse($service::statusAllowsAccess('overdue'));
    }

    /** @test */
    public function status_label_returns_spanish_translation()
    {
        $service = $this->service();

        $this->assertSame('Pagado', $service::statusLabel('paid'));
        $this->assertSame('Pendiente', $service::statusLabel('pending'));
        $this->assertSame('Vencido', $service::statusLabel('overdue'));
        $this->assertSame('Becado', $service::statusLabel('scholarship'));
    }

    /** @test */
    public function status_label_returns_input_for_unknown_status()
    {
        $service = $this->service();

        $this->assertSame('mystery', $service::statusLabel('mystery'));
    }

    /** @test */
    public function result_value_object_exposes_accessors()
    {
        $allow = PaymentAccessResult::allow('paid', 'Pagado', 'ok');
        $deny = PaymentAccessResult::deny('overdue', 'Vencido', 'no');

        $this->assertTrue($allow->allowed());
        $this->assertFalse($allow->denied());
        $this->assertSame('paid', $allow->status());
        $this->assertSame('Pagado', $allow->label());
        $this->assertSame('ok', $allow->reason());

        $this->assertTrue($deny->denied());
        $this->assertFalse($deny->allowed());
    }
}
