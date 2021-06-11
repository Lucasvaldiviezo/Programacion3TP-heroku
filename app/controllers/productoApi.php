<?php
require_once './models/producto.php';
require_once "./MWClases/AutentificadorJWT.php";
require_once 'changelogApi.php';
require_once './interfaces/IApiUsable.php';

use \App\Models\Producto as Producto;
use \App\Models\Empleado as Empleado;
use \App\Models\Changelog as Changelog;

class ProductoApi implements IApiUsable
{
    public function TraerUno($request, $response, $args) {
        $prod=$args['id'];
        $producto = Producto::where('id', $prod)->first();
        $payload = json_encode($producto);
        $response->getBody()->write($payload);
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("productos",$producto->id,$empleado->id,"Obtener datos","Datos de un producto");

        return $response
         ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args) {
        $lista = Producto::all();
        $payload = json_encode(array("listaProducto" => $lista));
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("productos",0,$empleado->id,"Obtener datos","Datos de todos los producto");
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $stock = $parametros['stock'];
        $tipo = $parametros['tipo'];
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        // Creamos el producto
        $prod = new Producto();
        $prod->nombre = $nombre;
        $prod->precio = $precio;
        $prod->tipo = $tipo;
        $prod->stock = $stock;
        $prod->save();
        //Log
        ChangelogApi::CrearLog("productos",$prod->id,$empleado->id,"Cargar","Stock: $stock");
        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args) {
        $productoId = $args['id'];
        $producto = Producto::find($productoId);
        $producto->delete();
        //Obtengo el empleado
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::where('mail', '=', $data->usuario)->first();
        //Log
        ChangelogApi::CrearLog("productos",$producto->id,$empleado->id,"Borrar","Se realizo el softdelete de la fila");
        $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   public function ModificarUno($request, $response, $args) {
    $parametros = $request->getParsedBody();
    $nombreModificado = $parametros['nombre'];
    $tipoModificado = $parametros['tipo'];
    $precioModificado = $parametros['precio'];
    $stockModificado = $parametros['stock'];
    $prodId = $parametros['id'];

    // Conseguimos el objeto
    $producto = Producto::where('id', '=', $prodId)->first();
    //Obtengo el empleado
    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $data = AutentificadorJWT::ObtenerData($token);
    $empleado = Empleado::where('mail', '=', $data->usuario)->first();
    
    // Si existe
    if ($producto !== null) {
        $producto->nombre = $nombreModificado;
        $producto->stock = $stockModificado;
        $producto->precio = $precioModificado;
        $producto->tipo = $tipoModificado;
        $producto->save();
        //Log
        ChangelogApi::CrearLog("productos",$producto->id,$empleado->id,"Modificar","Stock: $stock");
        $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        
    } else {
    $payload = json_encode(array("mensaje" => "Producto no encontrado"));
    }

    $response->getBody()->write($payload);
    return $response
    ->withHeader('Content-Type', 'application/json');	
    }

    
}

?>