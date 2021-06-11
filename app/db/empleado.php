<?php

class Empleado
{
    public $id;
    public $nombre;
    public $apellido;
    public $clave;
    public $mail;
    public $puesto;

    public function __construct()
    {

    }

    public function __construct1($nombre,$apellido,$clave,$mail,$puesto)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->clave = $clave;
        $this->mail = $mail;
        $this->puesto = $puesto;
    }

    public static function TraerTodoLosEmpleados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, apellido as apellido,clave as clave,mail as mail,puesto as puesto from empleados");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Empleado");		
	}

    public static function TraerUnEmpleadoId($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, apellido as apellido,clave as clave,mail as mail,puesto as puesto from empleados where id = $id");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Empleado');
			return $usuarioBuscado;	
	}

    public static function TraerEmpleadosPuesto($puesto) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $puestoAux = '"' . $puesto . '"'; 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,nombre as nombre, apellido as apellido,clave as clave,mail as mail,puesto as puesto from empleados where puesto = $puestoAux");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Empleado");	
	}

    public function InsertarEmpleadoParametros()
    {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into empleados (nombre,apellido,clave,mail,puesto)values(:nombre,:apellido,:clave,:mail,:puesto)");
            $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
            $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
            $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
            $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
            return $consulta->execute();
    }

    public function BorrarEmpleado()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
		delete 
		from empleados 				
		WHERE id=:id");	
		$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

    public function ModificarEmpleadoParametros()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update empleados 
            set nombre=:nombre,
            apellido=:apellido,
            clave=:clave,
            mail=:mail,
            puesto=:puesto
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);	
        return $consulta->execute();
    }
}


?>