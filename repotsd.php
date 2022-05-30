<?php

  session_start();

  require ("config.php");

  require("lib/lib.php");

  $busca=$_REQUEST[busca];

  $id=$_REQUEST[id];

  $pagina=$_REQUEST[pagina];

  $orden=$_REQUEST[orden];

  $resp=$_REQUEST[resp];

  $Msj="";

  $Titulo="Detalle de la orden de estudio [$busca]";

  $link=conectarse();

  $OrdenDef="otd.estudio";            //Orden de la tabla por default

  $Tabla="otd";

  $cSqlA=mysql_query("select sum(importe) from cja where orden='$busca'",$link);

  $SqlS=mysql_fetch_array($cSqlA);

  $cSqlH="select ot.orden,ot.fecha,ot.fechae,ot.cliente,cli.nombrec,ot.importe,ot.ubicacion,ot.institucion,ot.medico,med.nombrec,ot.status,ot.recibio,ot.institucion,ot.pagada from ot,cli,med where ot.cliente=cli.cliente and ot.medico=med.medico and ot.orden='$busca'";

  $cSqlD="select otd.estudio,otd.status,est.descripcion,otd.precio,otd.descuento,est.muestras,otd.etiquetas,otd.capturo,est.depto,otd.status,otd.usrvalida,otd.alterno from otd,est where otd.estudio=est.estudio and otd.orden='$busca'";

  $HeA=mysql_query($cSqlH,$link);

  $He=mysql_fetch_array($HeA);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>.:: Sistema LCD-NET ::.</title>
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
<title><?php echo $Titulo;?></title>

<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=450,height=350,left=100,top=150")
}
</script>
<?php

   echo "$Gfont <p align='center'><font FACE='arial'size='2' color='#FFFFFF'><strong>..::DETALLE DE LA ORDEN DE ESTUDIOS::.. $busca</strong></font></p>";
echo "<table border='0' width='85%' align='center' cellpadding='0' cellspacing='0'>";
   echo "<tr class='letrap' height='30'><td bgcolor='$Gbgsubtitulo'><b><font color='#FFFFFF'>&nbsp;&nbsp; No.Orden : $busca &nbsp;&nbsp;&nbsp; $He[nombre] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#FFFFFF'> Cliente:</font> $He[cliente] $He[4]</font></b></td></tr>";
   echo "<tr class='letrap' height='30'><td bgcolor='$Gbgsubtitulo'><b><font color='#FFFFFF'>&nbsp;&nbsp; Fecha : $He[fecha] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp<font color='#FFFFFF'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha/entrega : </font> $He[fechae]</font></b></td></tr>";
   echo "<tr class='letrap' height='30'><td bgcolor='$Gbgsubtitulo'><b><font color='#FFFFFF'>&nbsp;&nbsp; Medico: $He[medico] $He[9]</font></b></td></tr>";
   echo "<tr class='letrap' height='30'><td bgcolor='$Gbgsubtitulo'><b><font color='#FFFFFF'>&nbsp;&nbsp; Importe : $ ".number_format($He[importe],'2')."<font color='#FFFFFF'>&nbsp; &nbsp; Abonado : $ ".number_format($SqlS[0],'2')."&nbsp;&nbsp;&nbsp;<font color='#FFFFFF'>Saldo : ".number_format($He[importe]-$SqlS[0],'2')." </font></b></td></tr></table>";
   
   //." &nbsp; [";
   
   //echo "<a class='pg' href='ingreso.php?busca=$busca&pagina=$pagina'>NVO/INGRESO</a>] &nbsp; &nbsp; Pagada: $He[pagada]</p>";

   echo "</font>";

   if(!$res=mysql_query($cSqlD." ORDER BY ".$OrdenDef,$link)){
 		echo "<div align='center'>";
    	echo "<font face='verdana' size='2'>No se encontraron resultados </font>";
        echo "<p align='center'><font face='verdana' size='-2'><a href='ordenes.php?op=br'>";
        echo "Recarga y/� limpia.</a></font>";
 		echo "</div>";
 	}else{
      echo "<br>";
      $numeroRegistros=mysql_num_rows($res);
		echo "<div align='center'>";
		echo "<img src='images/corner-bottom-left.gif' width='15' height='12'>";
		echo "<font face='verdana' size='-1'><strong>".number_format($numeroRegistros,"0")." Estudios</b></strong></font>";
		echo "<img src='images/corner-bottom-right.gif' width='15' height='12'>";
		echo "</div>";
		echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";
		echo "<tr class='letrap'><td colspan='10'><hr noshade></td></tr>";
            echo "<tr class='letrap' align='center'><td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>PDF</font></b></td>";
            echo "<td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>Estudio</font></b></td>";
            echo "<td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>Descripcion</font></b></td>";
            echo "<td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>Precio</font></b></td>";
            echo "<td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>Descto</font></b></td>";
            echo "<td class='letrap' bgcolor='#ABEBC6' aling='center'><b><font color='#17202A'>Importe</font></b></td>";

            
		while($registro=mysql_fetch_array($res)){
            if (($nRng % 2) > 0) {
                $Fdo = '#FFFFFF';
            } else {
                $Fdo = $Gfdogrid;
            }    //El resto de la division;

            echo "<tr class='letrap' bgcolor=$Fdo onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";


            if ($registro[capturo] <> '') {

                if ($registro[depto] <> 2) {

                    if ($registro[status] == 'TERMINADA' and $registro[usrvalida] <> '') {
                                                        
                        echo "<td class='Seleccionar' align='center'><a href=javascript:wingral('resultapdf.php?cInk=$clnk&Orden=$busca&Estudio=$registro[estudio]&Depto=TERMINADA&op=im&alterno=$registro[alterno]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#FF0000'></i></a></td>";

                    }else{

                        echo "<td align='center'>-</td>";

                    }

                }else{

                    echo "<td align='center'><a href=javascript:wingral('pdfradiologia.php?busca=$busca&Estudio=$registro[estudio]')><i class='fa fa-file-pdf-o fa-lg' aria-hidden='true' style='color:#0000FF' title='Vista preliminar'></i></a></td> ";

                }
                    
            } else {

                    echo "<td align='center'>-</td>";

            }

            echo "<td>$Gfont $registro[estudio]</font></td>";
            echo "<td>$Gfont $registro[descripcion]</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio],'2')."</font></td>";
            echo "<td align='center'>$Gfont ".number_format($registro[descuento],'0')."</font></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio]-(($registro[precio]*$registro[descuento])/100),'2')."</font></td>";
            echo "</tr>";
            $Totalprecio=$Totalprecio+$registro[precio];
            $Total=$Total+$registro[precio]-(($registro[precio]*$registro[descuento])/100);
            $nRng++;
		}//fin while
        
        echo "<tr class='letrap'>";
        echo "<td colspan='3' align='right'>$Gfont <b>Totales: <b></font></td>";
        echo "<td align='right'>$Gfont <b>$ ".number_format($Totalprecio,'2')."</b></font></td>";
        echo "<td align='center'>$Gfont </font></td>";
        echo "<td align='right'>$Gfont <b>$ ".number_format($Total,'2')."</b></font></td></tr>";
		echo "</table> <br>";

    


		
      //echo " &nbsp; &nbsp; <a class='pg' href=javascript:Ventana('impeti2.php?op=3&busca=$busca')>Etiquetas</a>";

	}//fin if

    echo "<br><a class='letra' href=javascript:winuni('repots.php?busca=$id')><img src='lib/regresa.png'>  Regresar </a><br>";


echo "</body>";

echo "</html>";

mysql_close();
?>