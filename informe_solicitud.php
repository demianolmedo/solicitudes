<?php
session_start();

if (!$_SESSION['login'] ){
    echo '<script type="text/javascript">
    window.open("http://atc/TDI/", "_self");
    </script>';
}

include_once "models/rutas.php";
$cfgProgDir = '../phpSecurePages/';
include($cfgProgDir . "secure.php");

include("script/conex_doc.php");
include("script/perfiles.php");



$resultado = mysql_query($sql_tablero, $con_doc);
$data = mysql_fetch_array($resultado);
$tablero = $data["area_n"] . " - " . $data["solicitud_n"] . " - " . $data["titulo"];
$area = $data["area_n"];
$solicitud = $data["solicitud_n"];
$titulo = $data["titulo"];
$detalle = $data["detalle"];
$responsable = $data["responsable_n"];
$staff_tdi = $data["asignar_a_n"];
$adjunto = $data["adjunto"];
$estado = $data["estado"];
$obs = $data["obs"];
$solicitante = $data["usuario_solicitud_n"];
$fecha_solicitud = $data["fecha_solicitud"];
$bitacora = $data["bitacora"];
$adjunto_cierre = $data["adjuntos_cierre"];
$encaminada = $data["id_solicitud_padre"];
if($data["satisfaccion"]=='SI'){$satisfaccion = 'CLIENTE SATISFECHO';}
if($data["satisfaccion"]=='NO'){$satisfaccion = 'CLIENTE NO SATISFECHO';}
$satisfaccion_nombre = $data["usuario_voto"];
$satisfaccion_fecha = $data["fecha_voto"];


if (!$data["asignar_a_n"]) {
    header('Location: missolicitudes.php');
    die();
}
if ($tablero == " -  - ") {
    header('Location: missolicitudes.php');
    die();
}


$sql_all1 = "SELECT min(fecha_ini) ini, max(fecha_fin) fin FROM tablero where id_rel=" . $_REQUEST["id"] . "";
$resultado_all1 = mysql_query($sql_all1, $con_doc);
$data = mysql_fetch_array($resultado_all1);
$diasPlanificados = round((strtotime($data["fin"])-strtotime($data["ini"])) / (60 * 60 * 24))+1;
if($data["fin"]<date('Y-m-d')){$excedidosDiasSol='red';}else{$excedidosDiasSol='black';}


$sql_all = "SELECT * FROM tablero WHERE id_rel=" . $_REQUEST["id"] . " ORDER BY fecha_ini";
$resultado_all = mysql_query($sql_all, $con_doc);
// $data1 = mysql_fetch_array($resultado1);
$row_all = mysql_num_rows($resultado_all);

$sql_proceso="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PROCESO'";
$resultado_pro = mysql_query($sql_proceso, $con_doc);
// $data_pro = mysql_fetch_array($resultado_pro);
$proceso = mysql_num_rows($resultado_pro);

$sql_pruebas="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='PRUEBAS'";
$resultado_pru = mysql_query($sql_pruebas, $con_doc);
// $data_pru = mysql_fetch_array($resultado_pru);
$pruebas = mysql_num_rows($resultado_pru);

$sql_completado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='COMPLETADO'";
$resultado_com = mysql_query($sql_completado, $con_doc);
// $data_com = mysql_fetch_array($resultado_com);
$completado = mysql_num_rows($resultado_com);

$sql_eliminado="SELECT * FROM tablero WHERE id_rel='" . $_REQUEST["id"] . "' and estado='ELIMINADO'";
$resultado_eli = mysql_query($sql_eliminado, $con_doc);
// $data_eli = mysql_fetch_array($resultado_eli);
$eliminado = mysql_num_rows($resultado_eli);




mysql_close($con_doc);

?>
<!DOCTYPE html>
<html>

<head>
    <?php include "view/head.php"; ?>
    <style>
        table {
        border-collapse: collapse;
        width: 100%;
        }

        td, th {
        border: 1px solid #ebecf2;
        text-align: left;
        padding: 8px;
        }

        tr:nth-child(even) {
        background-color: #ebecf2;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php
    if (!$_GET["msg"]) {
    } else {
        echo "<script>alert('" . $_GET["msg"] . "')</script>";
    }
    ?>

    <div class="wrapper">

        <!-- Navbar -->
        <?php include "view/navbar.php"; ?>
        <!-- /.navbar -->


        <!-- menu -->
        <?php include_once "view/menu.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">


            &nbsp

            <!-- Main content -->

            <small>
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">

                                <!-- Profile Image -->
                                <div class="card card-primary card-outline" id="informe" name="informe">
                                    
                                    <div class="card-body box-profile">
                                        <!-- /.<div class="text-center">
                                            <img class="profile-user-img img-fluid img-circle" onerror="src='../portal/images/pe_pictures/117x117/SIN_FOTO.png'" src="../portal/images/pe_pictures/117x117/<?php echo $foto; ?>" alt="<?php echo $uname; ?>">
                                        </div> -->

                                        <h3 class="profile-username text-center">INFORME DE LA SOLICITUD N° <b><?php echo $_REQUEST["id"]; ?></b> <img src="imagen/print.png" alt=""  width="20px" height="20px" id="pdf" name="pdf"/></h3>
                                        
                                            
                                                <b>USUARIO QUIEN CREO LA SOLICITUD: </b><?php echo $solicitante; ?><br>
                                                <b>FECHA DE LA SOLICITIUD: </b><?php echo $fecha_solicitud; ?><br>
                                                <b>RESPONSABLE DE LA SOLICITUD: </b><?php echo str_replace("<br>"," ",$responsable); ?><br>
                                                <?php if($encaminada>""){?>
                                                <b>SOLICITUD PROCEDENTE N°: </b><a href="http://atc/TDI/informe_solicitud.php?id=<?php echo $encaminada; ?>"><?php echo $encaminada; ?></a>
                                                <?php } ?>
                                            
                                                <hr>

                                                <b>AREA DIRIGIDA DE LA SOLICITUD: </b><?php echo $area; ?><br>
                                                <b>TIPO DE SOLICITUD: </b><?php echo $solicitud; ?><br>
                                                <b>TITULO: </b><?php echo $titulo; ?><br>
                                                <b>ARCHIVO ADJUNTO DE LA SOLICITUD: </b><?php if (!$adjunto) {echo "NO";} else {echo '<a href="adjuntos/'.$adjunto.'">'.$adjunto.'</a>';} ?><br>
                                                <b>DETALLE: </b><br><div style="border: 1px solid #000; padding: 10px;">
                                            
                                                <div class="form-group" id="descripcion">
                                                    <textarea class="textarea" placeholder="Texto aqui" id="detalle" name="detalle" disabled><?php echo $detalle; ?></textarea>
                                                </div>
                                            
                                                </div><br>

                                                <hr>

                                                <b>RESPONSABLE DEL AREA TDI: </b><?php if ($staff_tdi=="") {echo "SIN ASIGNAR";} else {echo str_replace("<br>"," ",$staff_tdi);}?><br>
                                                <b>ESTADO DE LA SOLICITUD: </b><?php echo $estado; ?><br>                                                
                                                <b>FECHA ULTIMO EVENTO: </b> <?php echo date('Y-m-d H:i:s', strtotime(substr($bitacora, 0, 20))); ?><br>
                                                <b>TIEMPO TRANSCURRIDO ENTRE LA SOLICITUD Y EL ULTIMO EVENTO: </b>
                                                    <?php 
                                                        $dateDifference = abs(strtotime(substr($bitacora, 0, 20)) - strtotime($fecha_solicitud));

                                                        $years  = floor($dateDifference / (365 * 60 * 60 * 24));
                                                        $months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                                        $days   = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));
                                                        $horas  = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24 - $days * 60 * 60 * 24) / (60 * 60 ));
                                                        $min    = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24 - $days * 60 * 60 * 24 - $horas * 60 *60) / (60 ));
                                                        
                                                        echo "AÑOS: ".$years ." MESES: ".$months." DIAS: ".$days." HORAS: ".$horas." MINUTOS: ".$min;
                                                     ?><br>
                                                <b>RESPUESTA: </b>
                                                <div class="form-group" id="obs" disabled>
                                                    <textarea class="textarea" placeholder="Texto aqui" id="obs" name="obs" disabled><?php echo $obs; ?></textarea>
                                                </div>
                                                
                                                <b>ARCHIVO ADJUNTO DEL CIERRE: </b><?php if (!$adjunto_cierre) {echo "NO";} else {echo '<a href="adjuntos/'.$adjunto_cierre.'" style="font-size:130%;">'.$adjunto_cierre.'</a>';} ?><br>
                                                

                                                <hr>

                                                <div id="satisfaccion">
                                                    <b>Resultado de la encuesta: </b><?php echo $satisfaccion; ?><br>
                                                    <b>Responsable que emitio el voto: </b><?php echo $satisfaccion_nombre; ?><br>
                                                    <b>Fecha del voto: </b><?php echo $satisfaccion_fecha; ?><br>
                                                </div>

                                                <hr>

                                                <div id="tareas">
                                                    <b hidden>TITULO DE LA SOLICITUD: <?php echo $titulo;?></b><br>
                                                    <?php $row_all=$proceso+$pruebas+$completado+$eliminado; ?>
                                                <a id="tareas_excel" download="sol_<?php echo $_REQUEST["id"]."_".date("Y-m-d"); ?>_tareas_.xls" href="#"> <img src="imagen/excel.jpg" alt=""  width="10px" height="10px" title="DESCARGAR TAREAS EN EXCEL"/></a>&nbsp&nbsp&nbsp
                                                <b><a href="tablero.php?id=<?php echo $_REQUEST["id"]; ?>"> TOTAL DE TAREAS: </a><?php echo $row_all; ?></b><br> <br> 
                                                    <?php if ($row_all!=0) { ?>
                                                        <a href="#proceso"><label style="color:#FFC107;">EN PROCESO:</label> </a><b><?php echo $proceso." / "; if ($row_all==0) {echo "0%";} else {echo round(($proceso*100)/$row_all)."%";}?></b> 
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="#prueba"><label style="color:#28A745;">EN PRUEBA:</label> </a><b><?php echo $pruebas." / "; if ($row_all==0) {echo "0%";} else {echo round(($pruebas*100)/$row_all)."%";}?></b> 
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="#completado"><label style="color:#007BFF;">COMPLETADO:</label> </a><b><?php echo $completado." / "; if ($row_all==0) {echo "0%";} else {echo round(($completado*100)/$row_all)."%";}?></b> 
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="#eliminado"><label style="color:#DC3545;">ELIMINADO:</label> </a><b><?php echo $eliminado." / "; if ($row_all==0) {echo "0%";} else {echo round(($eliminado*100)/$row_all)."%";}?></b><br><br>
                                                    
                                                    <p><b>DURACION PLANIFICADA DE LA SOLICITUD: </b> <b style='color:<?php echo $excedidosDiasSol; ?>'><?php echo $diasPlanificados; ?> Dias</b></p>
                                                    <table>
                                                        <tr>
                                                        <th>TITULO TAREA</th>
                                                        <th>CREACION</th>
                                                        <th>DURACION DIAS</th>
                                                        <th>COMIENZO</th>
                                                        <th>FIN</th>
                                                        <th>STATUS</th>
                                                        <th>BITACORA</th>
                                                        </tr>
                                                        <tr>
                                                        <?php 
                                                            while ($data = mysql_fetch_array($resultado_all)) {
                                                                if($data["estado"]=='PROCESO'){$colorStatus='#FFC107';};
                                                                if($data["estado"]=='PRUEBAS'){$colorStatus='#28A745';};
                                                                if($data["estado"]=='COMPLETADO'){$colorStatus='#007BFF';};
                                                                if($data["estado"]=='ELIMINADO'){$colorStatus='#DC3545';};
                                                                if($data["fecha_fin"]<date('Y-m-d') && ($data["estado"]=='PRUEBAS' || $data["estado"]=='PROCESO')){$colorDiasVencidos='red';}else{$colorDiasVencidos='black';};
                                                                $cuentadias=round((strtotime($data["fecha_fin"])-strtotime($data["fecha_ini"]))/ (60 * 60 * 24))+1;
                                                                echo "<tr><td>".$data["titulo"]."</td><td>".$data["fecha_creacion"]."</td><td>".$cuentadias."</td><td>".$data["fecha_ini"]."</td><td style='color: ".$colorDiasVencidos.";'>".$data["fecha_fin"]."</td><td style='color: ".$colorStatus.";'>".$data["estado"]."</td><td><div style='width: 400px;height: 100px;overflow: scroll;'><div style='width: 100%;height: 100%;'>".$data["bitacora"]."</div></div></td></tr>";
                                                            }?>
                                                        </tr>
                                                    </table>
                                                    <p></p>
                                                    
                                                    <?php if ($proceso!=0) { ?>

                                                    <b>TAREAS </b>EN PROCESO:<br>
                                                    <table id="proceso">
                                                        <tr style="background-color:#FFC107">
                                                            <th style="width:15%">TITULO TAREA EN PROCESO</th>
                                                            <th style="width:10%">F. INICIO - FIN</th>
                                                            <th style="width:15%">INTEGRANTES</th>
                                                            <th style="width:30%">DESCRIPCION</th>
                                                            <th>CANTIDAD</th>
                                                            <th style="width:25%">OBS</th>
                                                            <th style="width:5%">SOL</th>
                                                            <th style="width:30%" hidden="hidden">BITACORA</th>
                                                        </tr>
                                                        <?php 
                                                            while ($data = mysql_fetch_array($resultado_pro)) {
                                                                echo "<tr><td><a href='detalle_tarjeta.php?id=".$data["id_rel"]."&lista=".$data["estado"]."&tarjeta=".$data["id"]."' style='color:#000000;'>".$data["titulo"]."</a></td><td>".$data["fecha_ini"]." - ".$data["fecha_fin"]."</td><td>".$data["integrantes_n"]."</td><td>".$data["descripcion"]."</td><td>".$data["numero"]."</td><td>".$data["obs"]."</td><td>".$data["solicitud_generada"]."</td><td hidden='hidden'>".str_replace('<br>','  ',$data["bitacora"])."</td></tr>";
                                                            }?>
                                                    </table><br>

                                                    <?php } if ($pruebas!=0) { ?>
                                                    
                                                    <b>TAREAS </b>EN PRUEBA:<br>
                                                    <table id="prueba">
                                                        <tr style="background-color:#28A745">
                                                            <th style="width:15%">TITULO TAREA EN PRUEBA</th>
                                                            <th style="width:10%">F. INICIO - FIN</th>
                                                            <th style="width:15%">INTEGRANTES</th>
                                                            <th style="width:30%">DESCRIPCION</th>
                                                            <th>CANTIDAD</th>
                                                            <th style="width:30%">OBS</th>
                                                            <th style="width:5%">SOL</th>
                                                            <th style="width:30%" hidden="hidden">BITACORA</th>
                                                        <?php 
                                                            while ($data = mysql_fetch_array($resultado_pru)) {
                                                                echo "<tr><td><a href='detalle_tarjeta.php?id=".$data["id_rel"]."&lista=".$data["estado"]."&tarjeta=".$data["id"]."' style='color:#000000;'>".$data["titulo"]."</a></td><td>".$data["fecha_ini"]." - ".$data["fecha_fin"]."</td><td>".$data["integrantes_n"]."</td><td>".$data["descripcion"]."</td><td>".$data["numero"]."</td><td>".$data["obs"]."</td><td>".$data["solicitud_generada"]."</td><td hidden='hidden'>".str_replace('<br>','  ',$data["bitacora"])."</td></tr>";
                                                            }?>
                                                    </table><br>

                                                    <?php } if ($completado!=0) { ?>

                                                    <b>TAREAS </b>COMPLETADO:<br>
                                                    <table id="completado">
                                                        <tr style="background-color:#007BFF"r>
                                                            <th style="width:15%">TITULO TAREA COMPLETADO</th>
                                                            <th style="width:10%">F. INICIO - FIN</th>
                                                            <th style="width:15%">INTEGRANTES</th>
                                                            <th style="width:30%">DESCRIPCION</th>
                                                            <th>CANTIDAD</th>
                                                            <th style="width:30%">OBS</th>
                                                            <th style="width:5%">SOL</th>
                                                            <th style="width:30%" hidden="hidden">BITACORA</th>
                                                        </tr>
                                                        <?php 
                                                            while ($data = mysql_fetch_array($resultado_com)) {
                                                                echo "<tr><td><a href='detalle_tarjeta.php?id=".$data["id_rel"]."&lista=".$data["estado"]."&tarjeta=".$data["id"]."' style='color:#000000;'>".$data["titulo"]."</a></td><td>".$data["fecha_ini"]." - ".$data["fecha_fin"]."</td><td>".$data["integrantes_n"]."</td><td>".$data["descripcion"]."</td><td>".$data["numero"]."</td><td>".$data["obs"]."</td><td>".$data["solicitud_generada"]."</td><td hidden='hidden'>".str_replace('<br>','  ',$data["bitacora"])."</td></tr>";
                                                            }?>
                                                    </table><br>

                                                    <?php } if ($eliminado!=0) { ?>

                                                    <b>TAREAS </b>ELIMINADO:<br>
                                                    <table id="eliminado">
                                                        <tr style="background-color:#DC3545">
                                                            <th style="width:15%">TITULO TAREA ELIMINADO</th>
                                                            <th style="width:10%">F. INICIO - FIN</th>
                                                            <th style="width:15%">INTEGRANTES</th>
                                                            <th style="width:30%">DESCRIPCION</th>
                                                            <th>CANTIDAD</th>
                                                            <th style="width:30%">OBS</th>
                                                            <th style="width:5%">SOL</th>
                                                            <th style="width:30%" hidden="hidden">BITACORA</th>
                                                        </tr>
                                                        <?php 
                                                            while ($data = mysql_fetch_array($resultado_eli)) {
                                                                echo "<tr><td><a href='detalle_tarjeta.php?id=".$data["id_rel"]."&lista=".$data["estado"]."&tarjeta=".$data["id"]."' style='color:#000000;'>".$data["titulo"]."</a></td><td>".$data["fecha_ini"]." - ".$data["fecha_fin"]."</td><td>".$data["integrantes_n"]."</td><td>".$data["descripcion"]."</td><td>".$data["numero"]."</td><td>".$data["obs"]."</td><td>".$data["solicitud_generada"]."</td><td hidden='hidden'>".str_replace('<br>','  ',$data["bitacora"])."</td></tr>";
                                                            }?>
                                                    </table><br>
                                                    
                                                <?php } } ?>
                                            <b>FECHA DEL INFORME: </b><?php echo date("Y-m-d H:i:s"); ?>
                                                </div>    
                                    </div>
                                    
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->

                            </div>
                            <!-- /.col -->



                        </div>
                        <!-- /.row -->
                    </div><!-- /.container-fluid -->
                </section>

            </small>

            <!-- Info boxes -->
            <div class="row">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
            </div><!-- /.container-fluid -->

            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2022 SOPORTE DE APLICACIONES</strong>
            <div class="float-right d-none d-sm-inline-block">
                <b>DATACOM SRL</b>
            </div>
        </footer>
    </div>

    </div>
    <!-- ./wrapper -->

    <?php include "view/script.php"; ?>

    <script src="printThis/printThis.js"></script>
    <script src=https://superal.github.io/canvas2image/canvas2image.js"></script>
    <script src=https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="jsPDF/dist/jspdf.min.js"></script>


    <script type="text/javascript">

        function downloadCanvas(canvasId, filename) {
            // Obteniendo la etiqueta la cual se desea convertir en imagen
            var domElement = document.getElementById('informe');
        
            // Utilizando la función html2canvas para hacer la conversión
            html2canvas(domElement, {
                onrendered: function(domElementCanvas) {
                    // Obteniendo el contexto del canvas ya generado
                    var context = domElementCanvas.getContext('2d');
        
                    // Creando enlace para descargar la imagen generada
                    var link = document.createElement('a');
                    link.href = domElementCanvas.toDataURL("image/png");
                    link.download = filename;
        
                    // Chequeando para browsers más viejos
                    if (document.createEvent) {
                        var event = document.createEvent('MouseEvents');
                        // Simulando clic para descargar
                        event.initMouseEvent("click", true, true, window, 0,
                            0, 0, 0, 0,
                            false, false, false, false,
                            0, null);
                        link.dispatchEvent(event);
                    } else {
                        // Simulando clic para descargar
                        link.click();
                    }
                }
            });
        }
        
        // Haciendo la conversión y descarga de la imagen al presionar el botón
        $('#pdf').click(function() {
            downloadCanvas('imagen', 'informe_solicitud_<?php echo $_REQUEST["id"]."_".date("Y-m-d_H_i"); ?>.png');
        });

        //$("#informe").printThis();


    </script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/excellentexport@3.4.3/dist/excellentexport.min.js"></script>
    <script>
        
        let download_xls = document.querySelector("#tareas_excel")
        download_xls.addEventListener("click", ()=>{                     
            ExcellentExport.excel(download_xls, 'tareas')
        })

        let download_xlsx = document.querySelector("#download_xlsx")
        download_xlsx.addEventListener("click", ()=>{                     
            ExcellentExport.convert({ anchor: download_xlsx, filename: 'filename', format: 'xlsx'},[{name: 'Sheet Name Here 1', from: {table: 'proceso'}}])
        })

    </script> 

<script>
    $(function() {
        var HTMLstring = '<div><p>Hello, world</p><p>Summernote can insert HTML string</p></div>';
        //$('.textarea').summernote('pasteHTML', HTMLstring);
        // Summernote
        $('.textarea').summernote(
            
            {
            //lang: 'fr-FR',       
            placeholder: 'ESCRIBE AQUI EL DETALLE (FAVOR ADJUNTAR EL CORREO Y NO COPIAR SU CONTENIDO EN ESTE EDITOR!!!) ',     
            height: 400,
            toolbar: [
                // ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                // ['style', ['bold', 'italic', 'underline']],
                // ['font', ['fontsize', 'fontname', 'strikethrough', 'underline']],
                // ['font', ['strikethrough', 'underline']],
                // ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['para', ['ul', 'ol']],
                // ['link', ['link']],
                // ['table', ['table']],
                // ['picture', ['picture']],
                // ['view', ['codeview']]
            ],
            onImageUpload: function(files, editor, welEditable) {
                for (var i = files.lenght - 1; i >= 0; i--) {
                    sendFile(files[i], this);
                }
            }
        });
        $('.note-editor .note-editable').css("line-height", 1);
        $('.textarea').summernote('disable');
    })
</script>


</body>

</html>