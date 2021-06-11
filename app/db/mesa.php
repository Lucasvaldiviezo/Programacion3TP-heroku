<?php

class Mesa{

    public $id;
    public $numero;
    public $estado;
    
    public function __construct()
    {

    }

    public function __construct1($numero,$estado)
    {
        $this->numero = $numero;
        $this->estado = $estado;
    }

    public static function TraerTodasLasMesas()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,numero as numero, estado as estado from mesas");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");		
	}

    public static function TraerUnaMesaNumero($numero) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,numero as numero, estado as estado from mesas where numero = $numero");
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Mesa');
			return $usuarioBuscado;	
	}

    public static function TraerMesasEstado($estado) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $estadoAux = '"' . $estado . '"'; 
			$consulta =$objetoAccesoDato->RetornarConsulta("select id,numero as numero, estado as estado from mesas where estado = $estadoAux");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");	
	}

    public function InsertarMesaParametros()
    {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mesas (numero,estado)values(:numero,:estado)");
            $consulta->bindValue(':numero',$this->numero, PDO::PARAM_INT);
            $consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);
            return $consulta->execute();
    }

    public function BorrarMesa()
	{
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("
		delete 
		from mesas			
		WHERE id=:id");	
		$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
		$consulta->execute();
		return $consulta->rowCount();
	}

    public function ModificarMesaParametros()
	{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("
            update mesas 
            set numero=:numero,
            estado=:estado
            WHERE id=:id");
        $consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
        $consulta->bindValue(':numero',$this->numero, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        return $consulta->execute();
    }
}

?>