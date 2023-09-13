<?php

namespace App\Http\Controllers\Oferta;

use App\Http\Controllers\Controller;
use App\Imports\OfertaImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class OfertaUploadController extends Controller
{
    public function import()
    {
        Excel::import(new OfertaImport, 'public/oferta.xlsx');
        return 'Importación exitosa';
    }
}
