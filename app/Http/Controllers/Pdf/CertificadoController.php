<?php

namespace App\Http\Controllers\Pdf;

// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// use DB;
// use PDF;

class CertificadoController extends Controller
{
  public function pdfForm(Request $request)
  {
    $user = Auth::user();
    $date = Carbon::now();

    $nombre = explode(' ', trim($user->name))[0];
    $nombre = mb_strtolower($nombre);
    $nombre = ucfirst($nombre);

    $apellido = explode(' ', trim($user->last_name))[0];
    $apellido = mb_strtolower($apellido);
    $apellido = ucfirst($apellido);
    
    //datos q se pasan al pdf
    $data = [
      'nombre'     => $nombre,
      'apellido'     => $apellido,
      'newDate'     => $date->format('d/m/Y'),
    ];

    if ($request->has('download')) {
      $queried_user = User::find($user->id);
      if ($queried_user->certif_intentos > 0) {
        $pdf = PDF::loadView('pdf.pdf_download', $data)->setPaper('a4', 'landscape');
        $queried_user->certif_intentos = $queried_user->certif_intentos - 1;
        $queried_user->save();
        return $pdf->download('pdf_download.pdf');
      }
      return redirect('/user/profile');
    }

    return view('pdf.pdf_view', ['user' => $user]);
  }
}
