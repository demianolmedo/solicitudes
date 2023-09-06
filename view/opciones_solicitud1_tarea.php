<?php
session_start();

if (!$_SESSION['login'] ){
    echo '<script type="text/javascript">
    window.open("http://atc/TDI/", "_self");
    </script>';
}
session_start();

if (!$_SESSION['login'] ){
    echo '<script type="text/javascript">
    window.open("http://atc/TDI/", "_self");
    </script>';
}
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("../script/conex_doc.php");



$elegido1 = explode(",", $_POST["elegido"]);
$elegido = $elegido1[0];
$elegido_n = $elegido1[1];


$sql = "SELECT * FROM LISTA WHERE id_rel=".$elegido."";
$resultado = mysql_query($sql, $con_doc);
$row_cnt = mysql_num_rows($resultado);
$lista = "";
while ($data = mysql_fetch_array($resultado)) {
    if ($data['sms']=="SI") {$colorsms= "style='color:#B20000'";} else {$colorsms="";}
    $lista .= "<option ".$colorsms." value='" . $data['id'] . ",".$data['opciones']." ".$data['url'].",".$data['sms'].",".$data['plantilla']."'>".$data['opciones']."&nbsp;&nbsp;&nbsp;&nbsp;" . $data['url'] . "</option>";
}



//echo $sql; exit;

mysql_close($con_doc);

$html = "";
if ($_POST["elegido"] == 3 || $_POST["elegido"] == 6 || $_POST["elegido"] == 9 || $_POST["elegido"] == 12 || $_POST["elegido"] == 13) {
    $html = '
    <section class="content">
    <div class="row">

    <div class="col-sm-6">
        <div class="form-group">      
            <input type="text" class="form-control float-right form-control-sm" id="titulo_s" name="titulo_s" placeholder="TITULO DE LA SOLICITUD" maxlength="50" required>       
        </div>
    </div>

    </div>
    </section>
	';
}

else  {
    $html = '
    <section class="content">
    <div class="row">

    <div class="col-sm-6">
        <div class="form-group">      
            <select class="form-control form-control-sm" id="titulo_s" name="titulo_s" onchange="val()" required>
            <option selected disabled value="">Opciones</option>
            '.$lista.'
            </select>     
            
        </div>
    </div>




    </div>
    </section> 
    <script>
        function val() {
            $("#boton").show();
        }
    </script>
	';
}


echo $html;



