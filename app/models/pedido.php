<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id';
    protected $table = 'pedidos';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_de_baja';

    protected $fillable = [
        'codigo','id_cliente', 'id_mesa', 'datos_productos', 'id_empleado', 'estado', 'total', 'puesto', 'fecha_hora_creacion', 'ultima_modificacion'
    ];
}
?>