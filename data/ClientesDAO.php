<?php

/*
 * ClientesDAO
 * omicrom®
 * © 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jul 2017
 */

include_once ('mysqlUtils.php');
include_once ('ClientesVO.php');

class ClientesDAO {

    private $conn;
    
    function __construct() {
        $this->conn = getConnection();
    }

    function __destruct() {
        $this->conn->close();
    }

    public function retrieve($id) {
        $cliente = new ClientesVO();
        $sql = "SELECT * FROM clif WHERE id = " . $id;
        if (($query = $this->conn->query($sql)) && ($rs = $query->fetch_assoc())) {
            $cliente->setId($rs['id']);
            $cliente->setNombre($rs['nombre']);
            //$cliente->setAlias($rs['alias']);
            //$cliente->setCia(trim($rs['cia']));
            $cliente->setRfc(trim($rs['rfc']));
            $cliente->setDireccion($rs['direccion']);
            $cliente->setNumeroext($rs['numeroext']);
            $cliente->setNumeroint($rs['numeroint']);
            $cliente->setColonia($rs['colonia']);
            $cliente->setMunicipio($rs['municipio']);
            $cliente->setEstado($rs['estado']);
            //$cliente->setContacto($rs['contacto']);
            $cliente->setTelefono($rs['telefono']);
            $cliente->setCorreo($rs['correo']);
            //$cliente->setCorreo2($rs['correo2']);               // Not in use
            $cliente->setEnviarcorreo($rs['enviarcorreo']);
            $cliente->setCuentaban($rs['cuentaban']);
            $cliente->setFormadepago($rs['formadepago']);
            $cliente->setTipodepago($rs['tipodepago']);
            //$cliente->setLimite($rs['limite']);
            $cliente->setCodigo($rs['codigo']);                 // Not in use
            //$cliente->setPuntos($rs['puntos']);
            //$cliente->setDesgloseIEPS($rs['desgloseIEPS']);
            //$cliente->setNcc($rs['ncc']);
            //$cliente->setObservaciones($rs['observaciones']);   // Not in use
            //$cliente->setActivo($rs['activo']);
        }
        error_log($cliente);
        return $cliente;
    }
    /**
     * @param ClientesVO $cliente
     */
    public function update($cliente) {
        $sql = "UPDATE clif SET "
            . "nombre = UPPER(?), "
            . "rfc = UPPER(?), "
            . "correo = ?, "
            . "enviarcorreo = ?, "
            . "cuentaban = ?, "
            . "formadepago = ? "
        . "WHERE id = ? ";
        if (($ps=$this->conn->prepare($sql))) {
            $ps->bind_param("sssssss", 
                    $cliente->getNombre(),
                    $cliente->getRfc(),
                    $cliente->getCorreo(),
                    $cliente->getEnviarcorreo(),
                    $cliente->getCuentaban(),
                    $cliente->getFormadepago(),
                    $cliente->getId());
            return $ps->execute();
        }
    }
    /**
     * @param ClientesVO $cliente
     */
    public function create($cliente) {
        $sql = "INSERT INTO clif ("
            . "nombre, "
            . "rfc, "
            . "direccion, "
            . "numeroext, "
            . "numeroint, "
            . "colonia, "
            . "municipio, "
            . "estado, "
            . "codigo, "
            . "telefono, "
            . "correo, "
            . "enviarcorreo, "
            . "cuentaban, "
            . "formadepago, "
            . "tipodepago "
            . ") "
            . "VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if (($ps=$this->conn->prepare($sql))) {
            $ps->bind_param("sssssssssssssssssss", 
                    $cliente->getNombre(),
                    $cliente->getRfc(),
                    $cliente->getDireccion(),
                    $cliente->getNumeroext(),
                    $cliente->getNumeroint(),
                    $cliente->getColonia(),
                    $cliente->getMunicipio(),
                    $cliente->getEstado(),
                    $cliente->getCodigo(),
                    $cliente->getTelefono(),
                    $cliente->getCorreo(),
                    $cliente->getEnviarcorreo(),
                    $cliente->getCuentaban(),
                    $cliente->getFormadepago(),
                    $cliente->getTipodepago());
            $id = $ps->execute() ? $ps->insert_id : -1;
            error_log(mysqli_error($this->conn));
            $ps->close();
        }
        return $id;
    }//create
}//ClientesDAO