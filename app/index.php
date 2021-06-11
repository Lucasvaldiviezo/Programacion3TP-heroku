<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;
date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once './vendor/autoload.php';
require_once './clases/AccesoDatos.php';
require_once './apis/empleadoApi.php';
require_once './apis/productoApi.php';
require_once './apis/mesaApi.php';
require_once './apis/pedidoApi.php';
require_once './apis/clienteApi.php';
require_once './apis/manejoArchivos.php';
require_once './models/changelog.php';
require './MWClases/MWparaAutentificar.php';
require './MWClases/MWparaCORS.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);


$container=$app->getContainer();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'tp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();




/*CON ESTO LOGEO PARA OBTENER EL JWT*/ 
$app->group('/login',function() {

  $this->post('/', \MWparaAutentificar::class . ':VerificarLogin');

});
/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/empleado', function () {
 
  $this->get('/', \empleadoApi::class . ':TraerTodos');
 
  $this->get('/{id}', \empleadoApi::class . ':TraerUno');

  $this->post('/', \empleadoApi::class . ':CargarUno');

  $this->delete('/{id}', \empleadoApi::class . ':BorrarUno');

  $this->put('/', \empleadoApi::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/producto', function () {
 
  $this->get('/', \productoApi::class . ':TraerTodos');
 
  $this->get('/{id}', \productoApi::class . ':TraerUno');

  $this->post('/', \productoApi::class . ':CargarUno');

  $this->delete('/{id}', \productoApi::class . ':BorrarUno');

  $this->put('/', \productoApi::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/mesa', function () {
 
  $this->get('/', \mesaApi::class . ':TraerTodos');
 
  $this->get('/{id}', \mesaApi::class . ':TraerUno');

  $this->post('/', \mesaApi::class . ':CargarUno');

  $this->delete('/{id}', \mesaApi::class . ':BorrarUno');

  $this->put('/', \mesaApi::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/pedido', function () {
 
  $this->get('/', \pedidoApi::class . ':TraerTodos');
 
  $this->get('/{id}', \pedidoApi::class . ':TraerUno');

  $this->post('/', \pedidoApi::class . ':CargarUno');

  $this->delete('/{id}', \pedidoApi::class . ':BorrarUno');

  $this->put('/', \pedidoApi::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/cliente', function () {
 
  $this->get('/', \clienteApi::class . ':TraerTodos');
 
  $this->get('/{id}', \clienteApi::class . ':TraerUno');

  $this->post('/', \clienteApi::class . ':CargarUno');

  $this->delete('/{id}', \clienteApi::class . ':BorrarUno');

  $this->put('/', \clienteApi::class . ':ModificarUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->group('/archivo', function () {
 
  $this->post('/', \manejoArchivos::class . ':GuardarDatos');

  //$this->get('/{id}', \clienteApi::class . ':TraerUno');
     
})->add(\MWparaAutentificar::class . ':VerificarUsuario');

$app->run();

?>