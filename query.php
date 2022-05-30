<?php
#Librerias
session_start();

include_once ("auth.php");
include_once ("authconfig.php");
include_once ("check.php");

require("lib/lib.php");
$link  = conectarse();

$buscar = "";
if(isset($_POST['Med'])){
    $buscar = $_POST['Med'];
}
$Sql = "SELECT id,nombrec FROM med WHERE nombre LIKE '%$buscar%'";
$res = mysql_query($Sql);
$file = mysql_fetch_array($res);
$total= $file;
?>
<?php
if($total > 0 && $buscar != ""){
?>
<div class='resultado'>
<?php do{ ?>
    <div class='resultados-q'></div>    
<?php
echo $fila['nombrec'];

?>
</div>
<?php
}while($fila=mysql_fetch_array($res));
?>
<?php
mysql_close();
?>

