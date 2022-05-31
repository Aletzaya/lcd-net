<?php

/*
 * NcDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel VillafaÃ±a, Softcoatl
 * @version 1.0
 * @since nov 2017
 */

include_once ('mysqlUtils.php');
include_once ('NcVO.php');

class NcDAO {
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
        $nc = new NcVO();
        $sql = "SELECT * FROM nc WHERE id = " . $id;
        error_log($sql);
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $nc->setId($rs['id']);
            $nc->setSerie($rs['serie']);
            $nc->setFecha($rs['fecha']);
            $nc->setCliente($rs['cliente']);
            $nc->setCantidad($rs['cantidad']);
            $nc->setImporte($rs['importe']);
            $nc->setIva($rs['iva']);
            $nc->setIeps($rs['ieps']);
            $nc->setStatus($rs['status']);
            $nc->setTotal($rs['total']);
            $nc->setUuid($rs['uuid']);
            $nc->setObservaciones($rs['observaciones']);
            $nc->setFormadepago($rs['formadepago']);
            $nc->setMetododepago($rs['metododepago']);
            $nc->setStCancelacion($rs['stCancelacion']);
            $nc->setRelacioncfdi($rs['relacioncfdi']);
            $nc->setTiporelacion($rs['tiporelacion']);
            $nc->setUsocfdi($rs['usocfdi']);
        }
        return $nc;
    }
    
    /*
     * @return FcVO
     */
    public function create($cliente, $folio) {
        $sql = "INSERT INTO nc (serie, fecha, cliente, relacioncfdi) SELECT serie, now(), ?, ? FROM cia";
        if (($ps=$this->conn->prepare($sql))) {
            $ps->bind_param("ss", $cliente, $folio);
            $id = $ps->execute() ? $ps->insert_id : -1;
            $ps->close();
        }
        return $id;
    }

    /* @var $ncVO NcVO */
    public function update($ncVO) {
        $sql = "UPDATE nc SET "
                . "observaciones = '".$ncVO->getObservaciones() . "', "
                . "formadepago = '".$ncVO->getFormadepago() . "' "
                . "WHERE id=" . $ncVO->getId();
        echo $sql;
        if ($this->conn->query($sql)) {
            return true;
        }

        if (mysqli_errno($this->conn)) {
            throw new Exception(mysqli_error($this->conn));
        }
        return false;
    }
}
