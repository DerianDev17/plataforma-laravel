<?php

namespace App\Http\Controllers;

use App\Imports\AccountsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CreateUsersBatch extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //importar
        $import = new AccountsImport;
        Excel::import($import, 'Base_Alumnos.xlsx');

        return ':)';
    }
}
