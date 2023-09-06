{
  "data": [
<?php

include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("../script/perfiles.php");

include("../script/conex_doc.php");


$resultado = mysql_query($sql_missolicitudes, $con_doc);
while ($data = mysql_fetch_array($resultado)) {
    echo '[';
    echo '"'.$data["id"].'",';
    echo '"'.$data["area_n"].'",';
    echo '"'.$data["solicitud_n"].'",';
    echo '],';

}

?>
  ]
}



