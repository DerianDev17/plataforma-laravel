<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExcelUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload()
    {
        return view('excel.file-upload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUploadPost(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        // respaldar archivo previo, si existe
        try
        {
            if (Storage::exists('Base_Alumnos.xlsx'))
            {
                Storage::move('Base_Alumnos.xlsx', 'Base_Alumnos.xlsx' . '.' . time() . '.resp');
            }
        } catch(Exception $e)
        {
            dd($e);
            return back()
            ->with('success', 'Ocurrió un error. Por favor, intente mas tarde.');
        }

        $path = Storage::putFileAs('', $request->file('file'), 'Base_Alumnos.xlsx');

        $full_path = Storage::path($path);

        // dd($full_path);
        File::chmod($full_path, 0740);

        return back()
            ->with('success', 'Archivo subido sin problemas.')
            ->with('file', $request->file('file')->getClientOriginalName());
    }
}
