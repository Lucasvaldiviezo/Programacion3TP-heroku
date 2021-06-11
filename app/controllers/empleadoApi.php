<?php
require_once './models/empleado.php';
require_once "./MWClases/AutentificadorJWT.php";
require_once 'changelogApi.php';
require_once './interfaces/IApiUsable.php';


use \App\Models\Empleado as Empleado;
use \App\Models\Changelog as Changelog;

class EmpleadoApi implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $emp=$args['id'];
        $empleado = Empleado::where('id', $emp)->first();
        $payload = json_encode($empleado);
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empExistente = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("empleados",$empleado->id,$empExistente->id,"Obtener datos","Datos de un empleado");
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Empleado::all();
        $payload = json_encode(array("listaEmpleado" => $lista));
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("empleados",0,$empleado->id,"Obtener datos","Datos de todos los empleados");
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $clave = $parametros['clave'];
        $mail = $parametros['mail'];

        if($parametros['puesto'] == 'mozo' || $parametros['puesto'] == 'cocinero' || $parametros['puesto'] == 'bartender' ||
        $parametros['puesto'] == 'candybar' || $parametros['puesto'] == 'socio')
        {
            $puesto = $parametros['puesto'];
        }else
        {
            $puesto = 'mozo';
        }
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empExistente = Empleado::where('mail', '=', $data->usuario)->first();
        // Creamos el empleado
        $emp = new Empleado();
        $emp->nombre = $nombre;
        $emp->apellido = $apellido;
        $emp->mail = $mail;
        $emp->clave = $clave;
        $emp->puesto = $puesto;
        $emp->save();
        //Log
        ChangelogApi::CrearLog("empleados",$emp->id,$empExistente->id,"Cargar",$emp->nombre . " " . $emp->puesto);

        $payload = json_encode(array("mensaje" => "Empleado creado con exito"));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $empleadoId = $args['id'];
        $empleado = Empleado::find($empleadoId);
        $empleado->delete();
        $payload = json_encode(array("mensaje" => "Empleado borrado con exito"));
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empExistente = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("empleados",$empleado->id,$empExistente->id,"Borrar","Se realizo el softdelete de la fila");
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   public function ModificarUno($request, $response, $args) {
    $parametros = $request->getParsedBody();
    $nombreModificado = $parametros['nombre'];
    $apellidoModificado = $parametros['apellido'];
    $mailModificado = $parametros['mail'];
    $claveModificada = $parametros['clave'];
    $puestoModificado = $parametros['puesto'];
    $empId = $parametros['id'];

    //Conseguimos el objeto
    $emp = Empleado::where('id', '=', $empId)->first();
    //Obtengo el empleado que esta realizando la tarea
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $empExistente = Empleado::where('mail', '=', $data->usuario)->first();
    // Si existe
    if ($emp !== null) {
        $emp->nombre = $nombreModificado;
        $emp->apellido = $apellidoModificado;
        $emp->mail = $mailModificado;
        $emp->clave = $claveModificada;
        $emp->puesto = $puestoModificado;
        $emp->save();
        $payload = json_encode(array("mensaje" => "Empleado modificado con exito"));
        //Log
        ChangelogApi::CrearLog("empleados",$emp->id,$empExistente->id,"Modificar",$empleado->nombre . " " . $empleado->puesto);
        
    } else {
      $payload = json_encode(array("mensaje" => "Empleado no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');	
    }
}

?>