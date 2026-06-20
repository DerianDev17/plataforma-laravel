<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $mustChange = (bool) $user->must_change_password;

        $rules = [
            'password' => $this->passwordRules(),
        ];

        if (! $mustChange) {
            $rules['current_password'] = ['required', 'string'];
        }

        $validator = Validator::make($input, $rules);

        if (! $mustChange) {
            $validator->after(function ($validator) use ($user, $input) {
                if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                    $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
                }
            });
        }

        $validator->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
            'must_change_password' => false,
        ])->save();
    }
}
