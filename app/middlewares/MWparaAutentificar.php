<?php
require_once "AutentificadorJWT.php";
require_once "./controllers/empleadoApi.php";

use \App\Models\Empleado as Empleado;

class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
  public function VerificarLogin($request, $response, $next)
  {
	$respuesta = "Credenciales invalidas";
	$ArrayDeParametros = $request->getParsedBody();
	$mail=$ArrayDeParametros["mail"];
	$clave=$ArrayDeParametros["clave"];

	$lista = Empleado::all();
	foreach($lista as $emp)
	{
		if($emp->mail == $mail && $emp->clave == $clave)
		{
			$datos = array('usuario' => $mail,'perfil' => $emp->puesto, 'clave' => $clave);
			$token= AutentificadorJWT::CrearToken($datos);
			$respuesta = $token;
			break;
		}
	}
	
	echo $respuesta;
  }
  
  public function VerificarUsuario($request, $response, $next) {
	$header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);	 
	$objDelaRespuesta= new stdclass();
	$objDelaRespuesta->respuesta="";
	try 
	{
		AutentificadorJWT::verificarToken($token);
		$objDelaRespuesta->esValido=true;      
	}
	catch (Exception $e) {      
		$objDelaRespuesta->excepcion=$e->getMessage();
		$objDelaRespuesta->esValido=false;     
	} 
	if($objDelaRespuesta->esValido)
	{
		if($request->isGet())
		{
			$response = $next($request, $response);
		}else if($request->isPost() || $request->isPut())
		{
			$ruta = $_SERVER['PATH_INFO'];
			if(strpos($ruta, 'pedido') && $request->isPut())
			{
				$parametros = $request->getParsedBody();
				$payload=AutentificadorJWT::ObtenerData($token);
				if($parametros['estado'] == "pagado")
				{
					if($payload->perfil=="socio")
					{
						$response = $next($request, $response);
					}else
					{
						$objDelaRespuesta->respuesta="Solo socios";
					}
				}		           	
				else
				{	
					$response = $next($request, $response);
				}
			}else
			{
				$response = $next($request, $response);
			}
			
		}else
		{
			$payload=AutentificadorJWT::ObtenerData($token);
			// DELETE sirve para socio
			if($payload->perfil=="socio")
			{
				$response = $next($request, $response);
			}		           	
			else
			{

				$objDelaRespuesta->respuesta="Solo socios";
			}
		}
	   	
	}else
	{
		$objDelaRespuesta->respuesta="Solo usuarios registrados";
		$objDelaRespuesta->elToken=$token;
	}

	if($objDelaRespuesta->respuesta!="")
	{
		$nueva=$response->withJson($objDelaRespuesta, 401);  
		return $nueva;
	}
	
	return $response;   
}
}