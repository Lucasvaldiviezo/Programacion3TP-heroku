<?php
require_once './models/mesa.php';
require_once "./MWClases/AutentificadorJWT.php";
require_once 'changelogApi.php';
require_once './interfaces/IApiUsable.php';


use \App\Models\Mesa as Mesa;
use \App\Models\Empleado as Empleado;
use \App\Models\Changelog as Changelog;

class MesaApi implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $ms=$args['id'];
        $mesa = Mesa::where('id', $ms)->first();
        $payload = json_encode($mesa);
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("mesas",$mesa->id,$empleado->id,"Obtener datos","Datos de una mesa");
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Mesa::all();
        $payload = json_encode(array("listaMesa" => $lista));
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("mesas",0,$empleado->id,"Obtener datos","Datos de todas las mesa");
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $cadena = '0123456789abcdefghijklmnopqrstuvwxyz';
        $numero = substr(str_shuffle($cadena),0,5);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        // Creamos la Mesa
        $mesa = new Mesa();
        $mesa->numero = $numero;
        $mesa->estado = 'cerrada';
        $mesa->save();
        //Log
        ChangelogApi::CrearLog("mesas",$mesa->id,$empleado->id,"Cargar",$mesa->estado);
        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $mesaId = $args['id'];
        $mesa = Mesa::find($mesaId);
        $mesa->delete();
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("mesas",$mesa->id,$empleado->id,"Borrar","Se realizo el softdelete de la fila");

        $payload = json_encode(array("mensaje" => "Mesa borrada con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   public function ModificarUno($request, $response, $args) {
    $parametros = $request->getParsedBody();
    $mesaId = $parametros['id'];
    if($parametros['estado'] == 'cerrada' || $parametros['estado'] == 'con cliente esperando pedido' || $parametros['estado'] == 'con cliente comiendo'
    || $parametros['estado'] == 'con cliente pagando')
    {
        $estado = $parametros['estado'];
    }else
    {
        $estado = 'cerrada';
    }
    // Conseguimos el objeto
    $mesa = Mesa::where('id', '=', $mesaId)->first();
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $empleado = Empleado::where('mail', '=', $data->usuario)->first();
    // Si existe
    if ($mesa != null) {
        $mesa->estado = $estado;
        $mesa->save();
        $payload = json_encode(array("mensaje" => "Estado de la mesa modificado con exito"));
        //log
        ChangelogApi::CrearLog("mesas",$mesa->id,$empleado->id,"Modificar",$estado);
        
    } else {
      $payload = json_encode(array("mensaje" => "Mesa no encontrada"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');		
    }

}

?>