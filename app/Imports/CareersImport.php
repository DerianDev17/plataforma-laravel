<?php

namespace App\Imports;

use App\Models\Career;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CareersImport implements ToCollection, WithStartRow
{
    const IES_NOMBRE_INSTIT = 0;
    const IES_TIPO_IES = 1;
    const IES_TIPO_FINAN = 2;
    const CAM_DIRECCION = 3;
    const PROV_CAMPUS = 4;
    const CANTON_CAMPUS = 5;
    const CAR_NOMBRE_CARRERA = 6;
    const OFA_TITULO = 7;
    const MODALIDAD = 8;
    const JORNADA = 9;
    const APC_DESC_CARR = 10;
    const CMC_DURAC_CARR = 11;
    const OFA_ID = 12;
    const PUNTAJE_REFERENCIAL = 13;
    const APC_PERFIL_OCUPACIONAL = 14;
    const PORCENT_BECA = 15;
    const EMPTYHEADER1 = 16;
    const PROV_CAMPUS_2 = 17;
    

    public function collection(Collection $rows)
    {

        if (Career::count() != 0) return false;

        foreach ($rows as $row) {
            Career::create([
                'ies_nombre_institut' =>        $row[self::IES_NOMBRE_INSTIT],
                'ies_tipo_ies' =>               $row[self::IES_TIPO_IES],
                'ies_tipo_financiamiento' =>    $row[self::IES_TIPO_FINAN],
                'cam_direccion' =>              $row[self::CAM_DIRECCION],
                'provincia_campus' =>           $row[self::PROV_CAMPUS],
                'canton_campus' =>              $row[self::CANTON_CAMPUS],
                'car_nombre_carrera' =>         $row[self::CAR_NOMBRE_CARRERA],
                'ofa_titulo' =>                 $row[self::OFA_TITULO],
                'modalidad' =>                  $row[self::MODALIDAD],
                'jornada' =>                    $row[self::JORNADA],
                'apc_descripcion_carrera' =>    $row[self::APC_DESC_CARR],
                'cmc_duracion_carrera' =>       $row[self::CMC_DURAC_CARR],
                'ofa_id' =>                     $row[self::OFA_ID],
                'puntaje_referencial' =>        $row[self::PUNTAJE_REFERENCIAL],
                'apc_perfil_ocupacional' =>     $row[self::APC_PERFIL_OCUPACIONAL],
                'porcentaje_beca' =>            $row[self::PORCENT_BECA],
                'provincia_campus_2' =>         $row[self::PROV_CAMPUS_2],
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
