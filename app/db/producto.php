<?php

class Producto{
    public $id;
    public $nombre;
    public $precio;
    public $stock;
    public $tipo;
    public $fechaCreacion;

    public function __construct()
    {

    }

    public function __construct1($nombre,$precio,$stock,$tipo)
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->tipo = $tipo;
        $this->fechaCreacion = date("y-m-d");
    }

    public static function TraerTodosLosProductos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre,precio as precio,stock as stock,tipo as tipo,fecha_de_creacion as fechaCreacion from productos");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");		
	}

    public static function TraerUnProductoId($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre,precio as precio,stock as stock,tipo as tipo,fecha_de_creacion as fechaCreacion from productos where id = $id");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Producto');
			return $usuarioBuscado;	
	}

    public static function TraerProductosTipo($tipo) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $tipoAux = '"' . $tipo . '"'; 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id, nombre as nombre,precio as precio,stock as stock,tipo as tipo,fecha_de_creacion as fechaCreacion from productos where tipo = $tipoAux");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");	
	}

    public function InsertarProductoParametros()
    {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into productos (nombre,precio,stock,tipo,fecha_de_creacion)values(:nombre,:precio,:stock,:tipo,:fechaCreacion)");
            $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
            $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
            $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
            $consulta->bindValue(':fechaCreacion', $this->fechaCreacion, PDO::PARAM_STR);
            return $consulta->execute();
    }

    public function BorrarProducto()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
		delete 
		from productos 				
		WHERE id=:id");	
		$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

    public function ModificarProductoParametros()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update productos 
            set nombre=:nombre,
            precio=:precio,
            stock=:stock,
            tipo=:tipo,
            fecha_de_creacion=:fechaCreacion
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':stock', $this->stock, PDO::PARAM_INT);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':fechaCreacion', $this->fechaCreacion, PDO::PARAM_STR);	
        return $consulta->execute();
    }
}


?>