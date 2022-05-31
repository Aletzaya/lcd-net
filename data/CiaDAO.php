<?php

/*
 * CiaDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('mysqlUtils.php');
include_once ('CiaVO.php');

class CiaDAO {
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function __destruct() {
        $this->conn->close();
    }

    /*
     * @return CiaVO
     */
    private function parseRS($rs) {
        $cia = new CiaVO();
        $cia->setCia($rs['nombre']);
        $cia->setDireccion($rs['direccion']);
        $cia->setNumeroext($rs['numeroext']);
        $cia->setNumeroint($rs['numeroint']);
        $cia->setColonia($rs['colonia']);
        $cia->setCiudad($rs['ciudad']);
        $cia->setEstado($rs['estado']);
        $cia->setCodigo($rs['codigo']);
        $cia->setTelefono($rs['telefono']);
        $cia->setIva($rs['iva']);
        $cia->setRfc(trim($rs['rfc']));
        $cia->setPassword(trim($rs['password']));
        $cia->setFacclavesat(trim($rs['facclavesat']));
        return $cia;
    }

    /**
     * 
     * @param type $fields
     * @return CiaVO
     */
    public function retrieveFields($id, $fields) {
        $cia = new CiaVO();
        $sql = "SELECT ". $fields . " FROM cia WHERE id = " . $id;
        error_log($sql);
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $cia = $this->parseRS($rs);
        }
        return $cia;
    }
}
