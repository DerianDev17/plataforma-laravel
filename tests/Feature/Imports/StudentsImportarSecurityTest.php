<?php

namespace Tests\Feature\Imports;

use App\Actions\Fortify\UpdateUserPassword;
use App\Imports\StudentsImportar;
use App\Mail\RegistrationMail;
use App\Models\StudentGroup;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentsImportarSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionsSeeder::class);
        StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);

        if (! Schema::hasColumn('users', 'diapago')) {
            Schema::table('users', function ($table) {
                $table->string('diapago')->nullable();
                $table->boolean('enviarCorreo')->default(false);
            });
        }
    }

    private function validRow(string $email = 'student@test.com'): array
    {
        return [
            null,
            'Juan',
            'Pérez',
            '0999999999',
            'Representante',
            '0988888888',
            '022222222',
            $email,
            'Sierra',
            'Quito',
            'Colegio',
            'Carrera',
            'A',
            'A',
            '5',
            'paid',
            '5',
            true,
        ];
    }

    /** @test */
    public function import_creates_user_with_random_password_not_equal_to_username()
    {
        Mail::fake();

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow()]));

        $user = User::where('email', 'student@test.com')->firstOrFail();

        $this->assertNotEmpty($user->username);
        $this->assertNotEquals($user->username, $user->getAuthPassword());
    }

    /** @test */
    public function import_marks_user_as_must_change_password()
    {
        Mail::fake();

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow()]));

        $user = User::where('email', 'student@test.com')->firstOrFail();

        $this->assertTrue((bool) $user->must_change_password);
    }

    /** @test */
    public function import_stores_a_bcrypt_hash_for_the_random_password()
    {
        Mail::fake();

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow()]));

        $user = User::where('email', 'student@test.com')->firstOrFail();

        $this->assertStringStartsWith('$2y$', $user->getAuthPassword());
        $this->assertNotEquals($user->username, $user->getAuthPassword());
    }

    /** @test */
    public function registration_email_contains_temp_password_and_username_separately()
    {
        Mail::fake();

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow()]));

        $user = User::where('email', 'student@test.com')->firstOrFail();

        Mail::assertSent(RegistrationMail::class, function (RegistrationMail $mail) use ($user) {
            $details = $mail->details;
            $body = view('emails.register-mail', ['details' => $details])->render();

            return str_contains($body, $user->username)
                && ! empty($details['temp_password'])
                && $details['temp_password'] !== $user->username
                && str_contains($body, $details['temp_password']);
        });
    }

    /** @test */
    public function update_user_password_clears_must_change_password_flag()
    {
        $user = User::factory()->create([
            'password' => Hash::make('temp-pass-1234'),
            'must_change_password' => true,
        ]);

        (new UpdateUserPassword)->update($user, [
            'password' => 'new-strong-pass-9876',
            'password_confirmation' => 'new-strong-pass-9876',
        ]);

        $user->refresh();

        $this->assertFalse((bool) $user->must_change_password);
        $this->assertTrue(Hash::check('new-strong-pass-9876', $user->getAuthPassword()));
    }

    /** @test */
    public function update_user_password_does_not_require_current_password_when_flag_is_set()
    {
        $user = User::factory()->create([
            'password' => Hash::make('temp-pass-1234'),
            'must_change_password' => true,
        ]);

        (new UpdateUserPassword)->update($user, [
            'password' => 'new-strong-pass-9876',
            'password_confirmation' => 'new-strong-pass-9876',
        ]);

        $this->assertTrue(Hash::check('new-strong-pass-9876', $user->getAuthPassword()));
    }

    /** @test */
    public function update_user_password_still_requires_current_password_when_flag_is_false()
    {
        $user = User::factory()->create([
            'password' => Hash::make('current-pass-1234'),
            'must_change_password' => false,
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        (new UpdateUserPassword)->update($user, [
            'password' => 'new-strong-pass-9876',
            'password_confirmation' => 'new-strong-pass-9876',
        ]);
    }

    /** @test */
    public function import_does_not_auto_verify_email()
    {
        Mail::fake();

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow()]));

        $user = User::where('email', 'student@test.com')->firstOrFail();

        $this->assertNull($user->email_verified_at);
    }

    /** @test */
    public function borrarEstudiantes_soft_deletes_users_not_in_import()
    {
        $existing = User::factory()->create([
            'email' => 'old@test.com',
            'name' => 'Old',
            'last_name' => 'Student',
        ]);
        $existing->assignRole('student');

        $import = new StudentsImportar(true);
        $import->borrarEstudiantes(collect([$this->validRow('new@test.com')]));

        $this->assertNotNull($existing->fresh()->deleted_at);
        $this->assertNotNull(User::withTrashed()->find($existing->id));
    }

    /** @test */
    public function borrarEstudiantes_skips_superadmins()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'name' => 'Admin',
            'last_name' => 'User',
        ]);
        $admin->assignRole('superadmin');

        $import = new StudentsImportar(true);
        $import->borrarEstudiantes(collect([$this->validRow('new@test.com')]));

        $this->assertNull($admin->fresh()->deleted_at);
    }

    /** @test */
    public function crearUsuario_force_deletes_soft_deleted_user_with_same_email()
    {
        Mail::fake();

        $ghost = User::factory()->create([
            'email' => 'student@test.com',
            'name' => 'Ghost',
            'last_name' => 'User',
        ]);
        $ghost->delete();

        $this->assertNotNull($ghost->fresh()->deleted_at);

        $import = new StudentsImportar(false);
        $import->collection(collect([$this->validRow('student@test.com')]));

        $active = User::where('email', 'student@test.com')->first();

        $this->assertNotNull($active);
        $this->assertNull($active->deleted_at);
        $this->assertNull(User::withTrashed()->find($ghost->id));
    }
}
