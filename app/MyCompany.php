<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCompany extends Model
{
    protected $fillable = [
        'ruc',
        'RazonSocial',
        'plan',
        'monto',
        'deuda',
        'fecha_suscription',
        'fecha_certificacion',
        'fin_suscription',
        'fin_certificacion',
        'nota',
        'estado',
        'nombre',
        'telefono',
        'archivo',
    ];
}
