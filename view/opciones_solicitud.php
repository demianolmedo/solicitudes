<?php

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

include("../script/perfiles.php");



$area_1 = explode(",", $_POST["area"]);

$area = $area_1[0];

$area_n = $area_1[1];





$solicitud_1 = explode(",", $_POST["solicitud"]);

$solicitud = $solicitud_1[0];

$solicitud_n = $solicitud_1[1];





$sql = "SELECT * FROM SOLICITUD WHERE estado='A' AND id_rel=" . $area. " " . $perfil . "";

$resultado = mysql_query($sql, $con_doc);

$row_cnt = mysql_num_rows($resultado);

$solicitud = "";

while ($data = mysql_fetch_array($resultado)) {

    $solicitud .= "<option value='" . $data['id'] . ",".$data['solicitud']."'>&nbsp;&nbsp;&nbsp;&nbsp;" . $data['solicitud'] . "</option>";

}

mysql_close($con_doc);





$html = "";



$html = '

    <select class="form-control form-control-sm" id="solicitud" name="solicitud" required>

    <option selected disabled>SOLICITUD</OPTION>

    ' . $solicitud . '   

    </select>

	';





$scr = '    <script language="javascript">

$(document).ready(function() {



    $("#solicitud").on(\'change\', function() {

        $("#solicitud option:selected").each(function() {

            elegido = $(this).val();

            $.post("view/opciones_solicitud1.php", {

                elegido: elegido,

                area: '.$area.'

            }, function(data) {

                $("#f2").html(data);

                $("#descripcion").show();

                $("#contacto").show();

                $("#archivo").show();

                $("#boton").show();

            })

        });

    });

    

});

</script>';

echo $html . " " . $scr;

