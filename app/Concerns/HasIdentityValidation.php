<?php

namespace App\Concerns;

use App\Utils\ValidarIdentificacion;

trait HasIdentityValidation
{
    public function getExamMonth()
    {
        if (is_null($this->exam_month)) {
            return trim(strtolower($this->fecha_examen));
        }

        return trim(strtolower($this->exam_month));
    }

    public function hasCedulaPadre()
    {
        $cedulaPadre = $this->attributes['cedulaPadre'] ?? null;

        return isset($cedulaPadre) && trim($cedulaPadre) !== '';
    }

    public function check_cedula_validity()
    {
        $ci_validator = new ValidarIdentificacion();

        return $ci_validator->validarCedula($this->cedula);
    }
}
