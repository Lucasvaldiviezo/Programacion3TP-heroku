<?php
require_once './models/empleado.php';

use \App\Models\Pedido as Pedido;
use \App\Models\Empleado as Empleado;
use \App\Models\Cliente as Cliente;
use \App\Models\Mesa as Mesa;
use \App\Models\Producto as Producto;
use \App\Models\Changelog as Changelog;

class ManejoArchivos
{

    public function GuardarDatos($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        $tipo=$parametros['tipo'];
        switch($tipo)
        {
            case 'empleado':
                $lista = Empleado::all();
                $empleados = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/empleados.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($empleados,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de empleados"));
            break;
            case 'cliente':
                $lista = Cliente::all();
                $clientes = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/clientes.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($clientes,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de clientes"));
            break;
            case 'mesa':
                $lista = Mesa::all();
                $mesas = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/mesas.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($mesas,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de mesas"));
            break;
            case 'producto':
                $lista = Producto::all();
                $productos = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/productos.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($productos,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de productos"));
            break;
            case 'pedido':
                $lista = Pedido::all();
                $pedidos = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/pedidos.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($pedidos,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de pedidos"));
            break;
            default:
                $lista = Changelog::all();
                $changeLogs = json_encode(array("listaCompleta" => $lista));
                $archivo = fopen("./archivos/changelogs.csv","a");
                $bool = fwrite($archivo, $this->DatosToCSV($changeLogs,$tipo));
                $payload = json_encode(array("mensaje" => "Se guardo el archivo de logs"));
        }
        fclose($archivo);

        if($bool == false)
        {
            $payload = json_encode(array("mensaje" => "No se guardo el archivo"));
        }

        $response->getBody()->write($payload);
        return $bool;
    }

    public function DatosToCSV($datos,$tipo)
    {
        $lista = json_decode($datos);
        $cadena = "";
        switch($tipo)
        {
            case 'empleado':
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .= "{" . $dato->id . "," . $dato->nombre . "," . $dato->apellido . "," . $dato->mail . "," . $dato->clave . "," . $dato->puesto . "}" . ",\n";
                }
            break;
            case 'cliente':
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .= "{" . $dato->id . "," . $dato->nombre . "," . $dato->apellido . "," . $dato->mail . "," . $dato->dni . "}" .",\n";
                }
            break;
            case 'mesa':
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .= "{" . $dato->id . "," . $dato->numero . "," . $dato->estado . "}" . ",\n";
                }
            break;
            case 'producto':
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .=  "{" . $dato->id . "," . $dato->nombre . "," . $dato->precio . "," . $dato->stock . "," . $dato->tipo . "}" . ",\n";
                }
            break;
            case 'pedido':
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .= "{" . $dato->id . "," . $dato->codigo . "," . $dato->id_cliente . "," . $dato->id_mesa . "," . $dato->datos_productos . "," . $dato->id_empleado . "," .$dato->estado . "," . $dato->total . ",";
                    $cadena .= $dato->puesto . "," . $dato->fecha_hora_creacion . "," . $dato->ultima_modificacion . "}" . ",\n";
                }
            break;
            default:
                foreach($lista->listaCompleta as $dato)
                {
                    $cadena .= "{" . $dato->id . "," . $dato->tabla_afectada . "," . $dato->id_afectado . "," . $dato->id_empleado . "," . $dato->accion . "," . $dato->descripcion . "," . $dato->fecha_hora . "}" . ",\n";
                }
        }

        return $cadena;  
    }

}
?>