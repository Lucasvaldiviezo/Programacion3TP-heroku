<?php
require_once './models/changelog.php';

use \App\Models\Changelog as Changelog;

class ChangelogApi
{
    public static function CrearLog($tablaAfectada,$idAfectado,$idEmpleado,$accion,$descripcion)
    {
            $changeLog = new Changelog();
            $changeLog->tabla_afectada = $tablaAfectada;
            $changeLog->id_afectado = $idAfectado;
            $changeLog->id_empleado = $idEmpleado;
            $changeLog->accion = $accion;
            $changeLog->descripcion = $descripcion;
            $changeLog->fecha_hora = date("y-m-d H:i:s");
            $changeLog->save();
    }
}

?>