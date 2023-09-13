<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ZoomJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoomMeetingController extends Controller
{
    use ZoomJWT;

    public function main()
    {
        // $user = Auth::user();
        // $user = User::find($user->id);

        // // obtener nombre y apellido
        // $n = explode(" ", $user->name);
        // $ln = explode(" ", $user->last_name);

        // $datos_sesion = $this->obtenerDatosSesion($user); // [0]=>id, [1]=>pass
        // $es_estudiante = $user->hasRole('student');
        // $email = $this->removeSpaces($user->email);
        // $signature = $this->getZoomSignature($user, $datos_sesion[0]);
        // // dd($es_estudiante);
        // return view('meetings.zoom-meet', [
        //     // 'usuario' => $user,
        //     // 'nombre' => $n,
        //     // 'apellido' => $ln,
        //     // 'meeting_number' => $datos_sesion[0],
        //     // 'meeting_password' => $datos_sesion[1],
        //     // 'es_estudiante' => $es_estudiante,
        //     // 'email' => $email,
        //     // 'signature' => $signature,
        // ]);
    }

    //remove spaces
    public function removeSpaces($str){
        $str = str_replace(' ', '', $str);
        return $str;
    }

    //Devuelve el id y la clave de la sesion como un array de 2 elementos
    public function obtenerDatosSesion($user)
    {
      
    }

    function getZoomSignature($user, $zoom_id)
    {
        $is_admin = $user->hasRole('superadmin');
        $signature = $this->generate_signature($zoom_id, $is_admin ? 1 : 0);
        return $signature;
    }
}
