<?php

class Mesa
{
    public $id;
    public $estado;



    public function CrearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado) VALUES (:estado)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
    }


    public static function TraerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }


    public function CambiarEstadoMesa($estado)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objetoAccesoDato->prepararConsulta("update mesas set estado = ? where id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_INT);
        $consulta->bindValue(2, $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }


    public static function MapearParaMostrar($array)
    {
        if (count($array) > 0) {
            foreach ($array as $i) {
                switch ($i->estado) {
                    case 1:
                        $i->estado = "Con cliente esperando pedido";
                        break;
                    case 2:
                        $i->estado = "Con cliente comiendo";
                        break;
                    case 3:
                        $i->estado = "Con cliente pagando";
                        break;
                    case 4:
                        $i->estado = "Cerrada";
                        break;
                }
            }
        }
        return $array;
    }
}
?>