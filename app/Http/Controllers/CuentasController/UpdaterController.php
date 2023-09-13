<?php

namespace App\Http\Controllers\CuentasController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Imports\StudentsImportar;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UpdaterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function excelUpload()
    {
        if (!Gate::allows('invitar-estudiantes')) {
            abort(403);
        }
        return view('updater2.excel-upload');
    }
    
    // actualizar los usuarios de la base de datos mediant excel
    public function excelUploadPost(Request $request)
    {
        if (!Gate::allows('invitar-estudiantes')) {
            abort(403);
        }
        
        $debe_borrar = $request->borrar;
        // dd($debe_borrar);

        //validacion
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        //obtener el archivo
        $archivo_excel = $request->file('file');

        //importar
        $import = new StudentsImportar($debe_borrar);
        Excel::import($import, $archivo_excel);
        $message = 'Base de datos actualizada<br>';
        $message .= 'Total registros creados: ' . strval($import->getRowCount()) . '<br><br>';
        $message .= '<strong>Las siguientes direcciones de email son inválidas:</strong><br>';

        foreach ($import->getFailedEmails() as $fu) {
            $message .= '<br>' . $fu;
        }

        return back()
            ->with('success', $message);
    }

}
