<?php

namespace App\Services;

use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelStudentService
{
    public function findUserInExcel($user): array
    {
        $found_user = [];
        $students_excel = Excel::toArray(new UsersImport, 'Base_Alumnos.xlsx');
        foreach ($students_excel[0] as $i => $std_xlx) {
            if ($std_xlx[7] === $user->email) {
                $found_user = $std_xlx;
            }
        }

        return $found_user;
    }
}
