<?php
require_once './models/cliente.php';
require_once "./MWClases/AutentificadorJWT.php";
require_once 'changelogApi.php';
require_once 'IApiUsable.php';

use \App\Models\Cliente as Cliente;
use \App\Models\Empleado as Empleado;
use \App\Models\Changelog as Changelog;


class ClienteApi implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $cli=$args['id'];
        $cliente = Cliente::where('id', $cli)->first();
        $payload = json_encode($cliente);
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("clientes",$cliente->id,$empleado->id,"Obtener datos","Datos de un cliente");
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Cliente::all();
        $payload = json_encode(array("listaCliente" => $lista));
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("clientes",0,$empleado->id,"Obtener datos","Datos de todos los clientes");
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $dni = $parametros['dni'];
        $mail = $parametros['mail'];
        //obtenemos el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        // Creamos el cliente
        $cli = new Cliente();
        $cli->nombre = $nombre;
        $cli->apellido = $apellido;
        $cli->mail = $mail;
        $cli->dni = $dni;
        $cli->save();
        //creamos el Log
        ChangelogApi::CrearLog("clientes",$cli->id,$empleado->id,"Cargar",$nombre . " " . $apellido);
        $payload = json_encode(array("mensaje" => "Cliente creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $clienteId = $args['id'];
        $cliente = Cliente::find($clienteId);
        $cliente->delete();
        $payload = json_encode(array("mensaje" => "Cliente borrado con exito"));
        $response->getBody()->write($payload);
        //obtenemos el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //creamos el log
        ChangelogApi::CrearLog("clientes",$cliente->id,$empleado->id,"Borrar","Se realizo el softdelete de la fila");
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombreModificado = $parametros['nombre'];
        $apellidoModificado = $parametros['apellido'];
        $mailModificado = $parametros['mail'];
        $dniModificado = $parametros['dni'];
        $cliId = $parametros['id'];

        // Conseguimos el objeto
        $cli = Cliente::where('id', '=', $cliId)->first();
        //obtenemos el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        // Si existe
        if ($cli !== null) {
            $cli->nombre = $nombreModificado;
            $cli->apellido = $apellidoModificado;
            $cli->mail = $mailModificado;
            $cli->dni = $dniModificado;
            $cli->save();
            $payload = json_encode(array("mensaje" => "Cliente modificado con exito"));
            //creamos el log
            ChangelogApi::CrearLog("clientes",$cli->id,$empleado->id,"Modificar","Se modifico el cliente: " . $dniModificado);
        } else {
        $payload = json_encode(array("mensaje" => "Cliente no encontrado"));
        }

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');	
    }
    


}

?>