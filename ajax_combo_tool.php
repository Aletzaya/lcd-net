<?php

if (filter_input(INPUT_GET, 'sQuery', FILTER_SANITIZE_STRING) !== null
        && filter_input(INPUT_GET, 'sField', FILTER_SANITIZE_STRING) !== null
        && filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING) !== null) {

    $jsonResult = array();

    foreach($_GET as $key=>$value){
        error_log($key . ' => ' . $value);
    }

    $sQuery     = filter_input(INPUT_GET, 'sQuery', FILTER_SANITIZE_STRING);
    $sField     = filter_input(INPUT_GET, 'sField', FILTER_SANITIZE_STRING);
    $sText      = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
    error_log(str_replace("&#39;", "'", $sQuery));
    $sQuery = str_replace("&#39;", "'", $sQuery);
    $query = $sQuery . " AND " . $sField . " LIKE '%" . str_replace(' ', '%', $sText) . "%' ORDER BY " . $sField;
    
    error_log($query);

    $connection = mysql_connect ("127.0.0.1","Admon", "det15a");

    if (!$connection) {
      die('Not connected : ' . mysql_error());
    }

    $db_selected = mysql_select_db("lcd-net", $connection);
    if (!$db_selected) {
      die ('Can\'t use db : ' . mysql_error());
    }

    mysql_query("SET NAMES 'utf8'", $connection);
    
    $result  = mysql_query($query);

    if (!$result) {
        die('Invalid query: ' . $query . ' ' . mysql_error());
    }

    while($rg=mysql_fetch_array($result)) {
        $jsonResult[] = $rg;
    }

    if ($connection) {
        mysql_close($connection);
    }

    $jsonString = json_encode(array('suggestions'=>$jsonResult));
    error_log($jsonString);

    if ($jsonString==null) {
        error_log(json_last_error());
    }

    echo $jsonString;
}// if valid parameters
?>
