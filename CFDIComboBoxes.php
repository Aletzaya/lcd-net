<?php

/* 
 * CFDIComboBoxes
 * omicromÂ®
 * Â© 2017, Detisa 
 * http://www.detisa.com.mx
 * @author Rolando Esquivel Villafaña, Softcoatl
 * @version 1.0
 * @since jun 2017
 */

class ComboboxFormaDePago {
    static function generate($comboID) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_fpago WHERE status = 1", $link);

        $html = "<select style=\"font-size: 10px;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE FORMA DE PAGO</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['descripcion']. "</option>";
        }
        $html = $html . "<option value=\"98\">NA | No Aplica</option>";
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxMetodoDePago {
    static function generate($comboID, $version = '3.3') {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_mpago WHERE status = 1", $link);

        $html = "<select style=\"font-size: 10px;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\">";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['descripcion']. "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxUnidades {
    static function generate($comboID) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, nombre FROM cfdi33_c_unidades WHERE status = 1", $link);

        $html = "<select style=\"font-size: 10px;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE UNIDAD</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['nombre']. "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxDivison {
    static function generate($comboID, $tipo) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_categorias WHERE clave_padre = '0' " . ($tipo==='' ? "" : " AND tipo = '" . $tipo . "'"), $link);

        $html = "<select style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE DIVISIÃ“N</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['descripcion'] . "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxGrupo {
    static function generate($comboID, $division) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_categorias WHERE clave_padre = '" . $division . "'", $link);

        $html = "<select style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE GRUPO</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['descripcion'] . "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxClase {
    static function generate($comboID, $grupo) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_categorias WHERE clave_padre = '" . $grupo . "'", $link);

        $html = "<select style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE CLASE</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['descripcion'] . "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
class ComboboxProductoServicio {
    static function generate($comboID, $clase) {
        $link = conectarse();

        $html = "<select style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE CONCEPTO</option>";
        $html = $html . "<option value=\"01010101\">No existe en el catálogo</option>";
        if ($clase!='') {
            $qry = mysql_query("SELECT clave, nombre FROM cfdi33_c_conceptos WHERE clave LIKE '" . substr($clase, 0, 6) . "%'", $link);

            while(($rs = mysql_fetch_array($qry))) {
                $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['nombre'] . "</option>";
            }
        }
        $html = $html . "</select>";
        echo $html;
    }
}
class ComboboxCommonProductoServicio {
    static function generate($comboID) {
        $link = conectarse();

        $html = "<select style=\"font-family: Tahoma, Geneva, sans-serif; font-size: 12px; font-weight: bold; color: #3E5A8F;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE CONCEPTO</option>";
        $html = $html . "<option value=\"01010101\">01010101 | No existe en el catálogo</option>";
        $qry = mysql_query("SELECT clave, nombre FROM cfdi33_c_conceptos WHERE status = '1'", $link);

        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['nombre'] . "</option>";
        }

        $html = $html . "</select>";
        echo $html;
    }
}
class ComboboxTipoRelacion {
    static function generate($comboID, $version = '3.3') {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_trelacion WHERE status = 1", $link);

        $html = "<select style=\"font-size: 10px;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\">";
        $html = $html . "<option value=\"\">SELECCIONE EL TIPO DE RELACI&Oacute;N</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . $rs['clave'] . " | " . $rs['descripcion']. "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}//ComboboxTipoRelacion
class ComboboxUsoCFDI {
    static function generate($comboID) {
        $link = conectarse();

        $qry = mysql_query("SELECT clave, descripcion FROM cfdi33_c_uso WHERE status = 1", $link);

        $html = "<select style=\"font-size: 10px;\" name=\"" . $comboID . "\" id=\"" . $comboID . "\"><option value=\"\">SELECCIONE USO CFDI</option>";
        while(($rs = mysql_fetch_array($qry))) {
            $html = $html . "<option value=\"" . $rs['clave'] . "\">" . str_replace(' ' , '&nbsp;', $rs['clave']) . " | " . $rs['descripcion']. "</option>";
        }
        $html = $html . "</select>";

        echo $html;
    }
}
