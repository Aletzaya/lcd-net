<?php

  session_start();

  require("lib/lib.php");

  $link     = conectarse();

  $OrdenDef = "ot.fecha";            //Orden de la tabla por default

  $busca    = $_REQUEST[busca];

  $Tabla    = "ot";

  // Es una copia de clientes y se cambian todas las urls a clientesord y el exit al ordenesnvas com busca
  $Titulo  = "Ordenes abiertas";

  $cSql    = "SELECT ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.status,ot.ubicacion,
             ot.institucion,ot.recepcionista,ot.suc,ot.pagada,cli.direccion,cli.localidad,cli.municipio,cli.telefono,cli.celular,cli.programa
             FROM ot,cli 
             WHERE ot.cliente=cli.cliente AND ot.cliente = '$busca'";

  $Sucursal = array("Admin","Matriz","Futura","Tepexpan","Los Reyes","Camarones","San Vicente");

  $aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

  require ("config.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.:: Numero de Visitas ::.</title>
<link href="estilos.css?var=1.2" rel="stylesheet" type="text/css"/>
<link href="menu.css?var=1.2" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />
<link rel='icon' href='favicon.ico' type='image/x-icon' />
<script src="js/jquery-1.8.2.min.js"></script>
<script src="jquery-ui/jquery-ui.min.js"></script>
<link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>

<?php 

echo '<body topmargin="1">';
        
//Tabla Principal    

        $sql  = $cSql.$cWhe." ORDER BY orden DESC ";
        
        $res  = mysql_query($sql);

        $nRng=0;

        while($registro=mysql_fetch_array($res)){

            if (($nRng % 2) > 0) {
                $Fdo = 'FFFFFF';
            } else {
                $Fdo = $Gfdogrid;
            }    //El resto de la division;
            
            if($nRng==0){

              echo "<table border='0' width='100%' align='center' cellpadding='0' cellspacing='0'>";    
              echo "<tr><td bgcolor='$Gbgsubtitulo' class='letratitulo' align='center' colspan='2'>";
              echo $registro[cliente]. " - " . $registro[nombrec];
              echo "</td>";
              echo "</tr>";
              echo "<tr><td bgcolor='$Gbgsubtitulo' class='letrap' align='center' colspan='2' style='color:#ffffff'>";
              echo $registro[direccion]. ", " . $registro[localidad]. ", " . $registro[municipio];
              echo "</td>";
              echo "</tr>";
              echo "<tr><td bgcolor='$Gbgsubtitulo' class='letrap' align='center' colspan='2' style='color:#ffffff'>";
              echo "Tel.: ".$registro[telefono]. ", Cel.: " . $registro[celular]. ", Programa: " . $aPrg[$registro[programa]];
              echo "</td>";
              echo "</tr>";
              echo "</table>";
              echo "<br>";

              echo "<table width='100%' align='center' border='0' cellpadding='1' cellspacing='2' bgcolor='#EBF5FB' style='border-collapse: collapse; border: 1px solid #999;'>";
              echo "<tr class='letrap' bgcolor='#EBF5FB' align='center'>";
              echo "<td></td>";
              echo "<td><b>Sucursal</b></td>";
              echo "<td><b>Inst</b></td>";
              echo "<td><b>Orden</b></td>";
              echo "<td><b>Fecha</b></td>";
              echo "<td><b>Importe</b></td>";
              echo "<td><b>Status</b></td>";
              echo "<td><b>Recepcionista</b></td>";
              echo "<td><b>Pagada</b></td>";
              echo "</tr>";

            }

            echo "<tr class='letrap' height='20px' bgcolor=$Fdo onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            echo "<td align='center'><a class='ord' href=javascript:winuni('repotsd.php?busca=$registro[orden]&id=$busca')><i class='fa fa-file-text-o'></i></td>";
            //echo "<td align='center'><a href=ordenesd.php?busca=$registro[orden]&pagina=$pagina><img src='lib/browse.png' alt='Detalle' border='0'></a></td>";
            echo "<td align='center'>$Gfont ".$Sucursal[$registro[suc]]."</font></td>";
            echo "<td align='center'>$Gfont $registro[institucion]</font></td>";
            echo "<td align='center'>$Gfont $registro[orden]</font></a></td>";
            echo "<td align='center'>$Gfont $registro[fecha]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[5],"2")."</font></td>";
            echo "<td align='center'>$Gfont $registro[status]</font></td>";
            echo "<td align='center'>$Gfont $registro[recepcionista]</font></td>";
            echo "<td align='center'>$Gfont $registro[pagada]</font></td>";
            echo "</tr>";
            $nRng++;
        }//fin while

		  echo "</table>";	
        
      echo "<br><a class='letra' href='javascript:window.close()'><img src='lib/regresa.png'>  Regresar </a><br>";

  mysql_close();

  ?>

</body>

</html>