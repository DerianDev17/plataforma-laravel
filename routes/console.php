<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    DB::table('users')->update(['cuestionario_resuelto' => false]);
})->monthlyOn(28, '00:00');
