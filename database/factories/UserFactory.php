<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->bothify('user####????'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'must_change_password' => false,
            'remember_token' => Str::random(10),
            'last_name' => $this->faker->lastName,
            'cellphone' => $this->faker->phoneNumber,
            'fixedphone' => $this->faker->phoneNumber,
            'highschool' => $this->faker->streetName,
            'especialty' => $this->faker->jobTitle,
            'paralelo' => $this->faker->text(5),
            'city' => $this->faker->city,
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'cedula' => '',
            'regimen' => 'Sierra',
            'fecha_examen' => 'Febrero',
            'exam_month' => 'Febrero',
            'student_group_id' => 999,
        ];
    }
}
