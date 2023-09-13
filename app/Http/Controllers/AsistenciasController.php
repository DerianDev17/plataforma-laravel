<?php

namespace App\Http\Controllers;

use App\Exports\AsistenciasExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;


class AsistenciasController extends Controller
{
    public function exportar()
    {
        $current = Carbon::now()->format('YmdHs');

        return Excel::download(new AsistenciasExport, 'asistencias' . $current . '.xlsx');
    }
}
