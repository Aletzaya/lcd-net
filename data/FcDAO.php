<?php

/*
 * FcDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('mysqlUtils.php');
include_once ('FcVO.php');

class FcDAO {
    private $conn;
    
    public function __construct() {
        $this->conn=getConnection();
    }

    
    public function __destruct() {
        $this->conn->close();
    }

    /*
     * @return FcVO
     */
    public function retrieve($id) {
        $fc = new FcVO();
        $sql = "SELECT * FROM fc WHERE id = " . $id;
        error_log($sql);
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $fc->setId($rs['id']);
            $fc->setSerie($rs['serie']);
            $fc->setFecha($rs['fecha']);
            $fc->setCliente($rs['cliente']);
            $fc->setCantidad($rs['cantidad']);
            $fc->setImporte($rs['importe']);
            $fc->setIva($rs['iva']);
            $fc->setStatus($rs['status']);
            $fc->setTotal($rs['total']);
            $fc->setUuid($rs['uuid']);
            $fc->setObservaciones($rs['observaciones']);
            $fc->setUsr($rs['usr']);
            $fc->setOrigen($rs['origen']);
            $fc->setStCancelacion($rs['stCancelacion']);
            $fc->setRelacioncfdi($rs['relacioncfdi']);
            $fc->setTiporelacion($rs['tiporelacion']);
            $fc->setUsocfdi($rs['usocfdi']);
        }
        return $fc;
    }

    public function create($id, $cliente, $team, $usr) {
        $sql = "INSERT INTO fc ( suc, folio, serie, fecha, usocfdi, cliente, usr ) "
                . "SELECT ?, IFNULL( MAX( fc.folio ), 0 )+1, IFNULL( cia.serie, '' ), ?, 'G03', ?, ? FROM cia LEFT JOIN fc ON fc.serie = cia.serie WHERE cia.id = ?";
        $date = date("Y-m-d H:i:s");
        error_log($sql);
        if (($ps=$this->conn->prepare($sql))) {
            $ps->bind_param("isisi",
                    $team,
                    $date,
                    $cliente,
                    $usr,
                    $id);
            $id = $ps->execute() ? $ps->insert_id : -1;
            error_log($ps->error);
        }
        return $id;
    }

    public function setObservaciones($id, $observaciones) {
        $sql = "UPDATE fc SET observaciones = '".$observaciones."' WHERE id=".$id;
        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }
    public function setUsocfdi($id, $usocfdi) {
        $sql = "UPDATE fc SET usocfdi = '".$usocfdi."' WHERE id=".$id;
        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }

    public function setRelacion($id, $relacion, $tiporelacion) {
        $sql = "UPDATE fc SET relacioncfdi = '".$relacion."', tiporelacion = '".$tiporelacion."' WHERE id=".$id;

        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }

}
