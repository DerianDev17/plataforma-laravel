<?php

namespace Tests\Feature\Services\Registration;

use App\Models\AuditLog;
use App\Models\User;
use App\Mail\RegistrationMail;
use App\Services\Registration\Contracts\UserRegistrar;
use App\Services\Registration\Registrars\StudentUserRegistrar;
use App\Services\Registration\UserRegistrationService;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserRegistrationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Juan',
            'lastname' => 'Pérez',
            'cellphone' => '0999999999',
            'email' => 'juan@test.com',
            'highschool' => 'Colegio Nacional',
            'city' => 'Quito',
            'regimen' => 'Sierra',
            'fecha_examen' => 'Enero',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'name_representant' => 'Padre',
            'lastname_representant' => 'Pérez',
            'cellphone_representant' => '0988888888',
            'cedula' => '1234567890',
        ], $overrides);
    }

    /** @test */
    public function resolves_default_registrar_from_container()
    {
        $this->assertInstanceOf(StudentUserRegistrar::class, app(UserRegistrar::class));
    }

    /** @test */
    public function register_creates_user_with_student_role()
    {
        $user = app(UserRegistrationService::class)->register($this->validData());

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'juan@test.com',
            'name' => 'Juan',
        ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => 2,
        ]);

        $this->assertTrue($user->hasRole('student'));
    }

    /** @test */
    public function register_writes_audit_log()
    {
        $user = app(UserRegistrationService::class)->register($this->validData());

        $entry = AuditLog::where('action', 'user.registered')->firstOrFail();

        $this->assertNull($entry->actor_id);
        $this->assertSame($user->id, $entry->context['user_id']);
        $this->assertSame('juan@test.com', $entry->context['email']);
        $this->assertSame('self', $entry->context['channel']);
    }

    /** @test */
    public function register_sends_username_reminder_without_temp_password()
    {
        Mail::fake();

        $user = app(UserRegistrationService::class)->register($this->validData());

        Mail::assertSent(RegistrationMail::class, function (RegistrationMail $mail) use ($user) {
            return $mail->hasTo($user->email)
                && ($mail->details['user']->is($user))
                && ! isset($mail->details['temp_password']);
        });
    }

    /** @test */
    public function register_accepts_actor_for_audit_log()
    {
        $actor = User::factory()->create();

        $user = app(UserRegistrationService::class)->register($this->validData(), $actor);

        $this->assertSame($actor->id, AuditLog::first()->actor_id);
    }

    /** @test */
    public function register_hashes_password()
    {
        $user = app(UserRegistrationService::class)->register($this->validData());

        $this->assertNotEquals('password123', $user->getAuthPassword());
        $this->assertTrue(\Hash::check('password123', $user->getAuthPassword()));
    }

    /** @test */
    public function register_validates_required_fields()
    {
        $this->expectException(ValidationException::class);

        app(UserRegistrationService::class)->register([]);
    }

    /** @test */
    public function register_rejects_duplicate_active_email()
    {
        $this->expectException(ValidationException::class);

        $data = $this->validData();
        app(UserRegistrationService::class)->register($data);
        app(UserRegistrationService::class)->register($data);
    }

    /** @test */
    public function register_throws_when_email_belongs_to_soft_deleted_user()
    {
        $ghost = User::factory()->create(['email' => 'juan@test.com']);
        $ghost->delete();

        try {
            app(UserRegistrationService::class)->register($this->validData());
            $this->fail('Se esperaba ValidationException por email de cuenta desactivada.');
        } catch (ValidationException $e) {
            $this->assertSame(
                'Este correo ya pertenece a una cuenta desactivada. Contacta al administrador para reactivarla.',
                $e->validator->errors()->first('email')
            );
        }
    }
}
