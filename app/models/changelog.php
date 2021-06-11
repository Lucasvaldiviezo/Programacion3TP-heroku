<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Changelog extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'changelogs';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_de_baja';

    protected $fillable = [
        'tabla_afectada', 'id_afectado', 'id_empleado', 'accion', 'descripcion', 'fecha_hora'
    ];
}
?>