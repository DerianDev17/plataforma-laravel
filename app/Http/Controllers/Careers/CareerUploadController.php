<?php

namespace App\Http\Controllers\Careers;

use App\Http\Controllers\Controller;
use App\Imports\CareersImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class CareerUploadController extends Controller
{
    public function import()
    {
        Excel::import(new CareersImport, 'oferta_academica_2do_semestre_2020.xlsm');
        return 'Importación exitosa';
    }
}
