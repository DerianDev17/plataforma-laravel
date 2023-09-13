<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        // registrar el archivo personalizado LogoutResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LogoutResponse::class,
            \App\Http\Responses\LogoutResponse::class
        );

        Fortify::authenticateUsing(function (Request $request) {
            // obtener el usuario desde la bdd por el email.
            $user = User::where('username', $request->username)->first();

            // si no se encontro el usuario o si la contraseña incorrecta no retornar nada
            if (!$user || !Hash::check($request->password, $user->password)) {
                return;
            }
            
            $logged_user = DB::table('sessions')->where('user_id', $user->id)->delete();

            // if ($logged_user) {
            //     abort(403, 'Operación deshabilitada.');
            //     return;
            // }

            if (Hash::check($request->password, $user->password)) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
                $res = Auth::logoutOtherDevices($request->password);
                // dd($res);
                return $user;
            }
        });
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
