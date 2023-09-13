<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ies_nombre_institut',
        'ies_tipo_ies',
        'ies_tipo_financiamiento',
        'cam_direccion',
        'provincia_campus',
        'canton_campus',
        'car_nombre_carrera',
        'ofa_titulo',
        'modalidad',
        'jornada',
        'apc_descripcion_carrera',
        'cmc_duracion_carrera',
        'ofa_id',
        'puntaje_referencial',
        'apc_perfil_ocupacional',
        'porcentaje_beca',
        'provincia_campus_2',
    ];
}
