<?php

namespace App\Http\Controllers\UpdaterController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
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
        return view('updater.excel-upload');
    }

    // actualizar los usuarios de la base de datos mediant excel
    public function excelUploadPost(Request $request)
    {
        if (!Gate::allows('invitar-estudiantes')) {
            abort(403);
        }
        
        //validacion
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        //obtener el archivo
        $archivo_excel = $request->file('file');

        //importar
        $import = new StudentsImport;
        Excel::import($import, $archivo_excel);

        $message = 'Base de datos actualizada<br>';
        $message .= 'Total registros: ' . strval($import->getRowCount()) . '<br><br>';
        $message .= '<strong>Las siguientes direcciones de email no fueron encontradas en el sistema:</strong><br>';

        foreach ($import->getFailedUpdates() as $fu) {
            $message .= '<br>' . $fu;
        }

        // redirigir
        return back()
            ->with('success', $message);
    }
}
