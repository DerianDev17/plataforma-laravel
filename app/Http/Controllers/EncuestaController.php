<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EncuestasExport;


class EncuestaController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());
        //validar

        //guardar en la base
        $encuesta = new Encuesta;
        $encuesta->score_mate = $request->score_mate ?? '';
        $encuesta->score_lengua = $request->score_lengua ?? '';
        $encuesta->score_socila = $request->score_socila ?? '';
        $encuesta->score_ciencias = $request->score_ciencias ?? '';
        $encuesta->frecuencia = $request->frecuencia;
        $encuesta->atencion = $request->atencion;
        $encuesta->satisfaccion = $request->satisfaccion;
        $encuesta->recomendacion = $request->recomendacion;
        $encuesta->user_id = $request->user_id;
        $encuesta->subject_id = $request->subject_id;
        $encuesta->save();
        
        $id = Auth::id();
        $user_db = User::find($id);
        $user_db->cuestionario_resuelto = 1;
        $user_db->save();

        //responder
        return response()->json([
            'mensaje' => 'Encuesta guardada',
        ]);
    }

    public function downloadEncuestas()
    {
        $current = Carbon::now()->format('YmdHs');

        return Excel::download(new EncuestasExport, 'encuestas' . $current . '.xlsx');
    }
}
