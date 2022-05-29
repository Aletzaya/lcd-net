<?php
function conectarse(){
    //if (!($link=mysql_connect("localhost","$_SESSION[usr]","texcoco"))){

    if (!($link=mysql_connect("localhost","root","det15a"))){
        echo "Error conectando a la base de datos.";
        exit();
    }
    if (!mysql_select_db("lcd",$link)){
        echo "Error seleccionando la base de datos.";
        exit();
    }

    return $link;

}

function cMensaje($Mensaje){
         echo "<div align='center'>";

        echo "<font face='verdana' size='2'>$Mensaje</font>";

        echo "<p align='center'><font class='p' face='verdana' size='-2'><a class='ord' href='$_SERVER[PHP_SELF]?op=br'>";

        echo "Recarga y/o limpia.</a></font></p>";

         echo "</div>";
return true;

}

function cZeros($Vlr,$nLen){   // Function p/ rellenar de zeros
  for($i = strlen($Vlr); $i < $nLen; $i=$i+1){
      $Vlr = "0".$Vlr;
   }
  return $Vlr;
}

function headymenu($Titulo,$Disp){    //Disp si es true desplega el menu

require ('config.php');

$Fec     = date('Y-m-d');

$Nombre  = $_SESSION['Nombre'];

$Usr     = $_COOKIE['USERNAME'];

$Mnu="menu/menu".$_SESSION['nivel'].".js";

if($Disp){
  echo "<script language='JavaScript1.2'>dqm__codebase = 'menu/script/'</script>";
  echo "<script language='JavaScript1.2' src='$Mnu'></script>";
  echo "<script language='JavaScript1.2' src='menu/script/tdqm_loader.js'></script>";
}

echo "<table width='100%' border='0'>";

echo "<tr>";

echo "<td width='15%'><a href='menu.php'><img  src='lib/logo2.jpg' border='0'></a></td>";

echo "<td width='70%' align='center'><br><img src='lib/labclidur.jpg'>";

//echo "<div align='center'><font size='3' color=$Gfdogrid > $Gdir </div>";

echo "</td>";

echo "<td width='15%'>&nbsp;</td>";

echo "</tr></table>";

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr bgcolor=$GfdoTitulo><td width='20' align='left'><script language='JavaScript1.2'>generate_mainitems()</script></td>";

echo "<td align='right'>$Gfont";

echo " &nbsp; &nbsp; &nbsp; ";

echo "</td>";

echo "</tr>";

echo "</table><br>";

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr><td>";

echo "$Gfont <font size='+1'><b> $Titulo </b> </font>";

echo "</td>";

echo "<td align='right'>$Gfont ";

echo " <img src='lib/msjn.png' border='0'> <b>$Usr</b> &nbsp; | &nbsp;";

echo " <a class='pg' href='menu.php'> <b> Inicio </b> </a> &nbsp; | &nbsp; $Fec &nbsp; | &nbsp; <a class='pg' href='logout.php'><b> Salir </b></a>  </font>";

echo " &nbsp; &nbsp; ";

echo "</td></tr></table>";


  return true;

}


function PonEncabezado(){

require ('config.php');

global $pagina,$cFuncion,$numeroRegistros,$orden,$busca,$Edit;   #P k reconoscan el valor k traen

      echo "<br>";

      echo "<table align='center' width='98%' border='0' cellspacing='1' cellpadding='0'>";

        echo "<tr height='25' background='lib/bartit.gif'>";

        $nEle=round(sizeof($Edit)/2);                        #No.de elementos en el arreglo

        for ($i = 0; $i < $nEle; $i++) {

            $Pos=strrpos($Edit[$nEle + $i],".") + 1; #por k los valores biene Drm.Fech

            $Ordn=substr($Edit[$nEle + $i],$Pos);     # y solo tomo Fecha
            if($Edit[$nEle + $i]<>"-"){              # Si trae guion(-) nopongas el link
               echo "<th>$Gfont <a class='pg' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=$Ordn'>$Edit[$i]</a></th>";
            }else{
               echo "<th>$Gfont $Edit[$i]</th>";
            }
        }

        echo "</tr>";

        return true;
}

function PonPaginacion($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$busca,$nRng,$Sort,$Id,$numeroRegistros;
#P k reconoscan el valor k traen

require ('config.php');


		if(sizeof($Bd)>1){
			$Comodin = $Bd[1];
			$Bd      = $Bd[0];
		}

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


		//Mensajes y No de registros;

      echo "<table align='center' width='98%' border='0' cellpadding='0' cellspacing='0'><tr height='25'>";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";

      echo "<td width='50%'>$Gfont <font size='2' color='#FF0000'><b> &nbsp;  $Msj </b></font></td>";

      echo "<td width='50%' align='right'>$Gfont <b>Registros: ".number_format($numeroRegistros,"0")." &nbsp; </b> </td>";

		echo "</tr></table>";


      $Pos   = strrpos($_SERVER[PHP_SELF],".");
      $cLink = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $Sql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string

      echo "<table align='center' width='98%' border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr><td height='40'> <b>";

      if($Bd){

         echo "<a class='pg' href='$cLink?busca=NUEVO'> Agrega </a> &nbsp; ";

         echo "<a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> &nbsp; ";

         echo "$Comodin  &nbsp; ";

         //echo "<a class='pg' href=javascript:winuni('filcampos.php?Id=$Id')>Filtro</a> &nbsp;&nbsp; ";

         //echo "<a class='pg' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a> &nbsp;&nbsp; ";

         echo "<img src='lib/print.png' alt='Imprimir pantalla' border='0' onClick='window.print()'>";

     }

     echo "</b></td><td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<b>[ $i ]</b> ";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=$i'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

		  $ini = 1;
		  if($pagina >= 7){

		     $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=1'><img src='lib/imini.gif' border='0'></a>&nbsp;";

			  if($pagina-11>=1){

			      if($pagina >= $numPags - 3){
			         $pg = $ini - 9;
			      }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=$pg'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

			  }

		     if($ini+10>$numPags){$ini = $numPags - 10;}

		  }

		  $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

   			$pag = cZeros($i,2);

            if($i==$pagina){

               echo "<b>[ $pag ]</b>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=$i'> $pag &nbsp; </a>";

            }

        }

		  if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=$pg'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

		  }

		  if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&orden=$orden&pagina=$numPags'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

		  }

     }

     echo " &nbsp; </td></tr></table>";

return true;

}


function CuadroInferior($busca){

require ('config.php');

global $orden,$pagina,$cSql,$cWhe,$Edit;

$Fec=date("Y-m-d");

echo "<table width='98%' border='0' cellpadding=0 cellspacing=0  align='center'>";

echo "<tr><td>$Gfont ";

        echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]'>";

        echo " &nbsp; Busca: &nbsp;<input type='text' name='busca' size='10' maxlength='25'> &nbsp; &nbsp; ";

        echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?op=br'>Limpia</a>";

        echo "<input type='hidden' name='pagina' value='1' >";

        echo "<input type='hidden' name='inicio' value ='1' >";

        echo "</font></form><br>";

        echo "</td><td>";

        echo "<form name='form11' method='post' action='$_SERVER[PHP_SELF]'>";

           echo "$Gfont Genera filtros en linea </font><br>";

           echo "<input type='hidden' name='op' value='ft'>";

           echo "<input type='hidden' name='pagina' value=$pagina>";

           echo "<input type='hidden' name='orden' value=$orden>";

           echo "<input type='hidden' name='busca' value=$busca>";

           echo "<select name='Campo'>";

           echo "<option value='CAMPOS'>Campos</option>";

           $nEle=round(sizeof($Edit)/2);                        #No.de elementos en el arreglo

           $n=0;

           for ($i = $nEle; $i < sizeof($Edit); $i++) {
               if($Edit[$n+$nEle] <> ''){
                 echo "<option value=".$Edit[$n+$nEle].">".$Edit[$n]."</option>";
               }
               $n=$n+1;
            }

            echo "</select>";

        echo "<select name='Signo'>";
        echo "<option value='SIGNO'>Signo</option>";
        echo "<option value='='>Igual a</option>";
        echo "<option value='>'>Mayor a</option>";
        echo "<option value='>='>Mayor igual a</option>";
        echo "<option value='<'>Menor a</option>";
        echo "<option value='<='>Menor igual a</option>";
        echo "<option value='<>'>Diferente de</option>";
        echo "<option value='like'>Contenga</option>";
        echo "</select>";
        echo "<input type='text' name='Valor'  size='8' value='$Fec' maxlength='15'>";
        echo "<select name='Yo'>";
        echo "<option value=''></option>";
        echo "<option value=' and '>y</option>";
        echo "<option value=' or '>&ograve;</option>";
        echo "</select>";

       echo "<INPUT TYPE='SUBMIT'  name='ok' value='ok'>";

       echo "</form>";

       echo "</td>";

		 /*
       echo "<td>";

       echo "<form name='form12' method='post' action='$_SERVER[PHP_SELF]' onSubmit='return ValSuma();'>";

       echo "<select name='SumaCampo'>";

       echo "<option value='CAMPOS'>Suma</option>";

       $nEle=round(sizeof($Edit)/2);                        #No.de elementos en el arreglo

       $n=0;

       for ($i = $nEle; $i < sizeof($Edit); $i++) {
          if(substr($Edit[$n+$nEle],0,1)=='N'){
               echo "<option value=".substr($Edit[$n+$nEle],1).">".$Edit[$n]."</option>";
          }
          $n=$n+1;
       }

      echo "</select>";

      echo "<input type='submit' name='Submit' value='Ok'>";

      echo "<input type='hidden' name='op' value='sm'>";

      echo "<input type='hidden' name='pagina' value=$pagina>";

      echo "<input type='hidden' name='orden' value=$orden>";

      echo "<input type='hidden' name='busca' value=$busca>";

      echo "</form>";

      echo "</td>";

      */

echo "</tr>";

echo "</table>";

return true;

}


function CalculaPaginas(){
global $res,$OrdenDef,$limitInf,$pagina,$tamPag,$orden,$numPags,$numeroRegistros;

       if(!isset($_REQUEST[orden])){$orden=$OrdenDef;}else{$orden=$_REQUEST[orden];}

       $numeroRegistros = mysql_num_rows($res);

       $numPags         = ceil($numeroRegistros/$tamPag);	//Redondea hacia arriba 3.14 -> 4;

       if(!isset($pagina) or $pagina <= 0 or $pagina >$numPags){   // Si no trae nada vete hasta e final
          $pagina=$numPags;
       }

       //calculo del limite inferior de registros para tomarlos de la tabla;
       $limitInf=0;
       if($numPags>1){
          if($pagina==$numPags){
          	 $limitInf = $numeroRegistros-$tamPag;
          }else{
             $limitInf = ($pagina-1)*$tamPag;
          }
		 }

return $limitInf;

}


function Botones2(){

require ('config.php');

global $pagina,$busca,$Msj;

echo "<p align='center'>";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Aceptar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Cancelar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Aplicar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<input type='IMAGE' name='Imprimir' src='images/print.png' onClick='print()'>";

echo "<input type='hidden' name='pagina' value=$pagina >";

echo "<input type='hidden' name='busca' value=$busca >";

echo "</p>";

}

function Botones(){

require ('config.php');

global $pagina,$busca,$Msj;

echo "<p align='center'>";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Aceptar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Cancelar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<INPUT TYPE='SUBMIT'  name='Boton' value='Aplicar'> &nbsp; &nbsp; &nbsp; &nbsp;";

echo "<input type='IMAGE' name='Imprimir' src='images/print.png' onClick='print()'>";

echo "<input type='hidden' name='pagina' value=$pagina >";

echo "<input type='hidden' name='busca' value=$busca >";

echo " &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </p>";

//echo "<hr noshade style='color:$GrallaInf;height:2px'>";

echo "$Gfont Mensaje <font color='#FF0000'><b>  $Msj </b></font>";

}

function cTable($Tam,$Borde){    //Abre tabla
   echo "<table width='$Tam' border='$Borde'>";
}

function cTableCie(){						//Cierra tabla
	echo "</table>";
}
function cInput($Titulo,$Tipo,$Lon,$Campo,$Alin,$Valor,$MaxLon,$Mayuscula,$Ed,$Nota){
require ('config.php');

//cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo
  echo "<tr><td align=$Alin>$Gfont $Titulo &nbsp; </font></td>";
  if(strlen($Tipo)>1){
      if($Ed){				// No se puede modificar el campo solo se edita
   	    echo "<td>$Gfont $Valor &nbsp; $Nota</td></tr>";
      }else{
          if($Mayuscula){
              echo "<td><input type=$Tipo name=$Campo size=$Lon value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>$Gfont $Nota</td></tr>";
          }else{
              echo "<td><input type=$Tipo name=$Campo size=$Lon value='$Valor' MAXLENGTH='$MaxLon'>$Gfont $Nota</td></tr>";
          }
     }
   }
}

function Btc($Txt,$Elm){

session_start();

$Usr   = $_COOKIE['USERNAME'];
$Fecha = date("Y-m-d");
$Hora  = date("H:i");

$lUp   = mysql_query("INSERT INTO bit (fecha,hora,usr,accion,elemento)
         VALUES
         ('$Fecha','$Hora','$Usr','$Txt','$Elm')");

}


function IncrementaFolio($Campo,$Suc){

	  $FolA    = mysql_query("SELECT $Campo FROM cia WHERE id='$Suc'");
	  $lUp     = mysql_query("UPDATE cia SET $Campo = $Campo + 1 WHERE id='$Suc'");
	  $Fol     = mysql_fetch_array($FolA);
	  $FolioU  = $Fol[$Campo];

	 return $FolioU;

}

# http://www.lawebdelprogramador.com

# tiene que recibir la hora inicial y la hora final

function RestarHoras($horaini,$horafin)
{
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);
 
	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);
 
	$dif=$fin-$ini;
 
	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H:i:s",mktime($difh,$difm,$difs));
}

?>
