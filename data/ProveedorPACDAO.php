<?php

/*
 * ProveedorPACDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */
require_once ('cfdi/com/softcoatl/cfdi/utils/pac/PACFactory.php');
include_once ('mysqlUtils.php');
include_once ('ProveedorPACVO.php');

use com\softcoatl\cfdi\utils\pac\PACFactory;
use com\softcoatl\cfdi\utils\pac\SifeiPACWrapper;
use com\softcoatl\cfdi\utils\pac\ProdigiaPACWrapper;

class ProveedorPACDAO {

    private $conn;

    public function __construct() {
        $this->conn = getConnection();
    }

    public function __destruct() {
        $this->conn->close();
    }

    private function parsePAC($rs) {
        $pac = PACFactory::getPAC($rs["url_webservice"], $rs["usuario"], $rs["password"], $rs["clave_pac"]);
        if ($pac instanceof SifeiPACWrapper) {
            $pac->setIdEquipo($rs["clave_aux"]);
            $pac->setSerie($rs["clave_aux2"]);
        } else if ($pac instanceof ProdigiaPACWrapper) {
            $pac->setContrato($rs["clave_aux"]);
        }
        $pac->setUrlCancelacion($rs["url_cancelacion"]);
        return $pac;
    }

    public function getActive() {
        $sql = "SELECT * FROM proveedor_pac WHERE activo = 1";
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            return $this->parsePAC($rs);
        }
        return false;
    }

}
