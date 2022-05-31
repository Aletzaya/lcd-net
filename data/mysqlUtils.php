<?php

/**
 * mysqlUtils
 * © 2017, Softcoatl
 * @author Rolando Esquivel
 * @since 05 Jan 2017
 * @version 1.0
 */

/**
 * getConnection Gets a new data base connection object
 * @param type $schemaName Schema Name
 * @param type $hostName Host URL
 * @param type $user Database user
 * @param type $password Database password
 * @return \mysqli Data base object
 * @throws Exception
 */
function getConnection() {

//    $dataBaseConf = (object) array(
//        'host' => '127.0.0.1',
//        'username' => 'u938386532_root',
//        'pass' => 'Lcd9623299',
//        'database' => 'u938386532_lcd'
//    );
    $dataBaseConf = (object) array(
                'host' => 'localhost',
                'username' => 'root',
                'pass' => 'det15a',
                'database' => 'lcd'
    );
    $dbConn = new mysqli($dataBaseConf->host, $dataBaseConf->username, $dataBaseConf->pass);

    if ($dbConn->connect_errno > 0) {
        if (mysqli_connect_errno()) {
            throw new Exception("Error conectando con base de datos <br/>" . utf8_encode(mysqli_connect_error()));
        }
    }
    if (!$dbConn->select_db($dataBaseConf->database)) {
        if (mysqli_errno($dbConn)) {
            throw new Exception("Error seleccionando base de datos <br/>" . utf8_encode(mysqli_error($dbConn)));
        }
    }
    if (!$psSetLocale = $dbConn->prepare("SET lc_time_names = 'es_MX'") || !$psSetLocale->execute()) {
        if (mysqli_errno($dbConn)) {
            throw new Exception("Error configurando base de datos <br/>" . utf8_encode(mysqli_error($dbConn)));
        }
    }
    if (!$dbConn->set_charset("UTF8")) {
        if (mysqli_errno($dbConn)) {
            throw new Exception("Error configurando base de datos <br/>" . utf8_encode(mysqli_error($dbConn)));
        }
    }
    return $dbConn;
}

//getConnection

