<?php

require_once './models/pedido.php';
require_once "./MWClases/AutentificadorJWT.php";
require_once "changelogApi.php";
require_once './interfaces/IApiUsable.php';

use \App\Models\Pedido as Pedido;
use \App\Models\Empleado as Empleado;
use \App\Models\Cliente as Cliente;
use \App\Models\Mesa as Mesa;
use \App\Models\Producto as Producto;
use \App\Models\Changelog as Changelog;

class PedidoApi implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $ped=$args['id'];
        $pedido = Pedido::where('id', $ped)->first();
        $payload = json_encode($pedido);
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("pedidos",$pedido->id,$empleado->id,"Obtener datos","Datos de un pedido");
        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Pedido::all();
        $payload = json_encode(array("listaPedido" => $lista));
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("pedidos",0,$empleado->id,"Obtener datos","Datos de todos los pedidos");
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        //Obtengo los datos a cargar
        $parametros = $request->getParsedBody();
        $cadena = '0123456789abcdefghijklmnopqrstuvwxyz';
        $codigo = substr(str_shuffle($cadena),0,5);
        $idCliente = $parametros['idCliente'];
        $idMesa = $parametros['idMesa'];
        $estado = 'en preparacion';
        $productos = json_decode($parametros['json_productos']);
        $puestos = explode(",",$parametros['puesto']);
        $puestoAux = "";
        foreach($puestos as $pues)
        {
            if($pues == 'cocina' || $pues == 'bar' || $pues == 'candybar')
            {
                $puestoAux .= "-" . $pues . "- ";
            }else
            {
                $puestoAux .= "-" . "cocina" . "- ";
            }
        }
        //Obtengo el empleado para el changelog
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        //Checkeo que existan los datos a cargar(empleado,mesa y productos)
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        $cliente = Cliente::where('id', '=', $idCliente)->first();
        $mesa = Mesa::where('id', '=', $idMesa)->first();
        if($empleado != null && $cliente != null && $mesa != null && $mesa->estado == "cerrada")
        {
            $total = 0;
            $datosProductos = "";
            $checkeoProducto = true;
            foreach($productos->Productos as $prod)
            {
                $auxProd = Producto::where('id', $prod->id)->first();
                //reviso que cada producto tenga el stock de lo que se pide
                if($auxProd != null && $auxProd->stock >= $prod->cantidad)
                {
                    $total += $auxProd->precio * $prod->cantidad;
                    $datosProductos = $datosProductos . "Id: " . $prod->id . " - Cantidad: " . $prod->cantidad . " / ";
                }else
                {
                    $checkeoProducto = false;
                    break;
                }
                
            }
            if($checkeoProducto)
            {
                $mesa->estado = "con cliente esperando pedido";
                $mesa->save();
                // Creamos el pedido
                $ped = new Pedido();
                $ped->codigo = $codigo;
                $ped->id_cliente = $idCliente;
                $ped->id_mesa = $idMesa;
                $ped->id_empleado = $empleado->id;
                $ped->estado = $estado;
                $ped->puesto = $puestoAux;
                $ped->fecha_hora_creacion = date("y-m-d H:i:s");
                $ped->ultima_modificacion = date("H:i:s");
                $ped->total = "$" . $total;
                $ped->datos_productos = $datosProductos;
                $ped->save();
                $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
                //agregamos los cambios al changelog
                ChangelogApi::CrearLog("pedidos",$ped->id,$empleado->id,"Cargar",$estado);
                //Descontamos el stock
                foreach($productos->Productos as $prod)
                {
                    $auxProd = Producto::where('id', $prod->id)->first();
                    if($auxProd != null)
                    {
                        $auxProd->stock = $auxProd->stock - $prod->cantidad;
                        $auxProd->save();
                        //log para productos
                        $descripcion = "Se redujo stock en " . $prod->cantidad;
                        ChangelogApi::CrearLog("productos",$auxProd->id,$empleado->id,"Reduccion de stock",$descripcion);
                    }
                
                }
            }else
            {
                $payload = json_encode(array("mensaje" => "No hay stock o no existe el producto"));
            }
            
        }else
        {
            $payload = json_encode(array("mensaje" => "Datos erroneos"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $pedidoId = $args['id'];
        $pedido = Pedido::find($pedidoId);
        $pedido->delete();
        $payload = json_encode(array("mensaje" => "Pedido borrado con exito"));
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //creamos el log
        ChangelogApi::CrearLog("pedidos",$pedido->id,$empleado->id,"Eliminar","Se realizo el softdelete de la fila");
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args) {
        //Obtengo datos cargados y obtengo el pedido
        $parametros = $request->getParsedBody();
        $estado = $parametros['estado'];
        $pedidoId = $parametros['id'];   
        $pedido = Pedido::where('id', '=', $pedidoId)->first();
        //Obtengo el token para saber que empleado esta realizando la tarea
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        //busco el empleado con la data
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Checkeo
        if($pedido != null)
        {
            $mesa = Mesa::where('id',"=",$pedido->id_mesa)->first();
            switch($estado)
            {
                case "listo para servir":
                    $pedido->id_empleado = $empleado->id;
                    $pedido->puesto = "-mozo-";
                    $pedido->estado = $estado;
                    $pedido->ultima_modificacion = date("H:i:s");
                    $pedido->save();
                    
                break;
                case "servido":
                    $pedido->id_empleado = $empleado->id;
                    $pedido->puesto = "-mesa-";
                    $pedido->estado = $estado;
                    $mesa->estado = "con cliente comiendo";
                    $pedido->ultima_modificacion = date("H:i:s");
                    $pedido->save();
                    $mesa->save();
                break;
                case "pagando":
                    $pedido->id_empleado = $empleado->id;
                    $pedido->puesto = "-mesa-";
                    $pedido->estado = $estado;
                    $mesa->estado = "con cliente pagando";
                    $pedido->ultima_modificacion = date("H:i:s");
                    $pedido->save();
                    $mesa->save();
                break;
                case "pagado":
                    $pedido->id_empleado = $empleado->id;
                    $pedido->puesto = "-mesa-";
                    $pedido->estado = "pagado";
                    $mesa->estado = "cerrada";
                    $pedido->ultima_modificacion = date("H:i:s");
                    $pedido->save();
                    $mesa->save();
                break;
                default:
                $pedido->puesto = "-mozo-";
                $pedido->estado = "por definir";
                $mesa->estado = "cerrada";
                $pedido->ultima_modificacion = date("H:i:s");
                $pedido->save();
                $mesa->save();
            }
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
            ChangelogApi::CrearLog("pedidos",$pedido->id,$empleado->id,"Modificar",$estado);
        }else
        {
            $payload = json_encode(array("mensaje" => "No existe el pedido"));
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');		
    }
    
}

?>
