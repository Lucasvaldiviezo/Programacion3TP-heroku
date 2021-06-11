<?php

class Pedido{
    public $id;
    public $codigo;
    public $idCliente;
    public $idMesa;
    public $idProducto;
    public $cantidad;
    public $estado;
    public $horaCreacion;
    public $horaFinalizacion;
    
    public function __construct()
    {

    }

    public function __construct1($idCliente,$idMesa,$idProducto,$cantidad,$estado)
    {
        $this->idCliente = $idCliente;
        $this->idMesa = $idMesa;
        $this->idProducto = $idProducto;
        $this->cantidad = $cantidad;
        $this->codigo = rand(10000,99999);
        $this->horaCreacion = date("H:i:s");
        echo $this->horaCreacion;
        if($estado == "")
        {
            $this->estado = "solicitado";
        }else
        {
            $this->estado = $estado;
        } 
    }

    public static function TraerTodosLosPedidos()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,codigo as codigo, id_cliente as idCliente,id_mesa as idMesa, id_producto as idProducto,cantidad as cantidad,estado as estado from pedidos");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");		
	}

    public static function TraerUnPedidoNumeroMesa($idMesa) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,codigo as codigo, id_cliente as idCliente,id_mesa as idMesa, id_producto as idProducto,cantidad as cantidad,estado as estado from pedidos where id_mesa = $idMesa");
			$consulta->execute();
			$pedidoBuscado= $consulta->fetchObject('Pedido');
			return $pedidoBuscado;	
	}

    public static function TraerUnPedidoId($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,codigo as codigo, id_cliente as idCliente,id_mesa as idMesa, id_producto as idProducto,cantidad as cantidad,estado as estado from pedidos where id = $id");
			$consulta->execute();
			$pedidoBuscado= $consulta->fetchObject('Pedido');
			return $pedidoBuscado;	
	}

    public static function TraerPedidosEstado($estado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $estadoAux = '"' . $estado . '"'; 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,codigo as codigo, id_cliente as idCliente,id_mesa as idMesa, id_producto as idProducto,cantidad as cantidad,estado as estado from pedidos where estado = $estadoAux");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Producto");	
	}

    public function InsertarPedidoParametros()
    {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (codigo,id_cliente,id_mesa,id_producto,cantidad,estado,hora_de_creacion)values(:codigo,:idCliente,:idMesa,:idProducto,:cantidad,:estado,:horaCreacion)");
            $consulta->bindValue(':codigo',$this->codigo, PDO::PARAM_STR);
            $consulta->bindValue(':idCliente',$this->idCliente, PDO::PARAM_INT);
            $consulta->bindValue(':idMesa',$this->idMesa, PDO::PARAM_INT);
            $consulta->bindValue(':idProducto',$this->idProducto, PDO::PARAM_INT);
            $consulta->bindValue(':cantidad',$this->cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':horaCreacion', $this->horaCreacion, PDO::PARAM_STR);
            return $consulta->execute();
    }

    public function BorrarPedido()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
		delete 
		from pedidos 				
		WHERE id=:id");	
		$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

    public function ModificarPedidoParametros()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update pedidos 
            set codigo = :codigo,
            id_cliente = :idCliente, 
            id_mesa=:idMesa,
            id_producto=:idProducto,
            cantidad=:cantidad,
            estado=:estado
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':codigo',$this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':idCliente',$this->idCliente, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa',$this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function ModificarEstadoParametros()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $this->horaFinalizacion = date("H:i:s"); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update pedidos 
            set
            estado=:estado,
            hora_de_finalizacion=:horaFinalizacion
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':horaFinalizacion', $this->horaFinalizacion, PDO::PARAM_STR);
        return $consulta->execute();
    }
}


?>