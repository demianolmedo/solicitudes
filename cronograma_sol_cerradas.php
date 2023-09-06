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



function convertirMes($mesIngles) {
    $meses = array(
        'January' => 'Enero',
        'February' => 'Febrero',
        'March' => 'Marzo',
        'April' => 'Abril',
        'May' => 'Mayo',
        'June' => 'Junio',
        'July' => 'Julio',
        'August' => 'Agosto',
        'September' => 'Septiembre',
        'October' => 'Octubre',
        'November' => 'Noviembre',
        'December' => 'Diciembre'
    );

    if (array_key_exists($mesIngles, $meses)) {
        return $meses[$mesIngles];
    } else {
        return false; // En caso de que el mes no exista en el array
    }
}

function convertirArea($area) {
    $areaN = array(
        '1' => 'Desarrollo',
        '2' => 'PBX',
        '3' => 'MICROINFORMATICA',
        '4' => 'WFO'
    );
    if (array_key_exists($area, $areaN)) {
        return $areaN[$area];
    } else {
        return false; // En caso de que el mes no exista en el array
    }
}


//FILTRO AREA
$filtro="";
$area=" - TODOS";
if(isset($_REQUEST["area"])){$filtro=" AND area=".$_REQUEST["area"] ."";}


// Obtener datos de la base de datos
$sql = "SELECT asignar_a_n nombre, count(id) total, STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') fecha FROM SOLICITUDES where (estado='COMPLETADO' ".$filtro.") group by (STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d')) , asignar_a_n";
$result = mysql_query($sql, $con_doc);

$sqlT = "SELECT count(id) total FROM SOLICITUDES where (estado='COMPLETADO' AND STR_TO_DATE(SUBSTRING(OBS, 1, 20), '%Y-%m-%d') >= DATE_FORMAT(CURDATE(), '%Y-%m-01') ".$filtro.") ";
$resultT = mysql_query($sqlT, $con_doc);
$Total = mysql_fetch_array($resultT);

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
                                <div class="card card-primary card-outline" id="informe" name="informe" style='padding:10px'>

                                <?php      
                                    $datos = array();
                                    while ($data = mysql_fetch_array($result)) {                                                                                      
                                            $fecha = date("Y-m-d", strtotime($data["fecha"] . " +$i days"));
                                            $integrantes = $data['nombre'].":&nbsp;&nbsp;&nbsp; <b style='color:blue'>  ".$data['total']."</b><br>";
                                            $datos[$fecha] .= $integrantes;
                                          }

                                    // Calendario
                                    $mes_actual = date('n');
                                    $año_actual = date('Y');

                                    // Obtener el primer día del mes
                                    $primer_dia = mktime(0, 0, 0, $mes_actual, 1, $año_actual);
                                    $primer_dia_semana = date('N', $primer_dia);

                                    // Obtener el número de días en el mes
                                    $num_dias_mes = date('t', $primer_dia);
                                    
                                    $mesEspanol = convertirMes(date('F', mktime(0, 0, 0, $mes_actual, 1)));
                                    
                                    if(isset($_REQUEST["area"])){$areaNombre = convertirArea($_REQUEST["area"]);}else{$areaNombre = "Todos";}

                                    echo "<p></p><h5><b>SOLICITUDES CERRADAS ".$areaNombre." <small style='font-size: 12px;color:blue'>(TOTAL DE SOLICITUDES CERRDAS POR DIA)</small></h5><h5>".$mesEspanol." - ".$año_actual."</b>  <small style='font-size: 12px;color:blue'>Total solo Solicitudes (no incluye tareas): ".$Total['total']."</small></h5>";
                                    // Crear el calendario
                                    echo "<table>";
                                    echo "<tr style='background-color:#007BFF;color:white;'>";
                                    echo "<th style='width: 16%;'>Lun</th>";
                                    echo "<th style='width: 16%;'>Mar</th>";
                                    echo "<th style='width: 16%;'>Mie</th>";
                                    echo "<th style='width: 16%;'>Jue</th>";
                                    echo "<th style='width: 16%;'>Vie</th>";
                                    echo "<th style='width: 10%;'>Sáb</th>";
                                    echo "<th style='width: 10%;'>Dom</th>";
                                    echo "</tr>";

                                    echo "<tr style='background-color:#ffffff;'>";
                                    // Rellenar los días de la semana antes del primer día del mes
                                    for ($i = 1; $i < $primer_dia_semana; $i++) {
                                        echo "<td></td>";
                                    }

                                    // Rellenar los días del mes
                                    for ($dia = 1; $dia <= $num_dias_mes; $dia++) {
                                        $fecha = date('Y-m-d', mktime(0, 0, 0, $mes_actual, $dia, $año_actual));
                                        $dato = isset($datos[$fecha]) ? $datos[$fecha] : "";
                                        if(date('l', strtotime($fecha))=== 'Saturday' || date('l', strtotime($fecha))=== 'Sunday'){
                                            if($dia==date('d')){echo "<td style='background-color:#E8E8E8;'><b style='color:red;'>$dia</b><br>&nbsp;</td>";}
                                            else{echo "<td style='vertical-align: top;'><span class='badge badge-danger'>".$dia."</span><br>&nbsp;</td>";}                                            
                                        }else{
                                            if($dia==date('d')){echo "<td style='background-color:#E8E8E8;'><b>$dia</b><br>$dato</td>";}
                                            else{echo "<td style='vertical-align: top;'><span class='badge badge-secondary'>".$dia."</span><br>$dato</td>";}
                                            
                                            }
                                            

                                        // Si es el último día de la semana, cerrar la fila y empezar una nueva
                                        if (($dia + $primer_dia_semana - 1) % 7 == 0) {
                                            echo "</tr><tr style='background-color:#ffffff;'>";
                                        }
                                    }

                                    // Rellenar los días de la semana después del último día del mes
                                    while (($dia + $primer_dia_semana - 1) % 7 != 0) {
                                        echo "<td></td>";
                                        $dia++;
                                    }

                                    echo "</tr>";
                                    echo "</table>";



                                    //MES ANTERIOR

                                                                        // Calendario
                                                                        $mes_anterior = date('n', strtotime('-1 month'));
                                                                        $año_anterior = date('Y');
                                    
                                                                        // Obtener el primer día del mes
                                                                        $primer_dia = mktime(0, 0, 0, $mes_anterior, 1, $año_anterior);
                                                                        $primer_dia_semana = date('N', $primer_dia);
                                    
                                                                        // Obtener el número de días en el mes
                                                                        $num_dias_mes = date('t', $primer_dia);
                                                                        
                                                                        $mesEspanol = convertirMes(date('F', mktime(0, 0, 0, $mes_anterior, 1)));
                                    
                                                                        echo "<p></p><b><h5>".$mesEspanol." - ".$año_anterior."</b></h5>";
                                                                        // Crear el calendario
                                                                        echo "<table>";
                                                                        echo "<tr style='background-color:#007BFF;color:white;'>";
                                                                        echo "<th style='width: 16%;'>Lun</th>";
                                                                        echo "<th style='width: 16%;'>Mar</th>";
                                                                        echo "<th style='width: 16%;'>Mie</th>";
                                                                        echo "<th style='width: 16%;'>Jue</th>";
                                                                        echo "<th style='width: 16%;'>Vie</th>";
                                                                        echo "<th style='width: 10%;'>Sáb</th>";
                                                                        echo "<th style='width: 10%;'>Dom</th>";
                                                                        echo "</tr>";
                                    
                                                                        echo "<tr style='background-color:#ffffff;'>";
                                                                        // Rellenar los días de la semana antes del primer día del mes
                                                                        for ($i = 1; $i < $primer_dia_semana; $i++) {
                                                                            echo "<td></td>";
                                                                        }
                                    
                                                                        // Rellenar los días del mes
                                                                        for ($dia = 1; $dia <= $num_dias_mes; $dia++) {
                                                                            $fecha = date('Y-m-d', mktime(0, 0, 0, $mes_anterior, $dia, $año_anterior));
                                                                            $dato = isset($datos[$fecha]) ? $datos[$fecha] : "";
                                                                            if(date('l', strtotime($fecha))=== 'Saturday' || date('l', strtotime($fecha))=== 'Sunday'){
                                                                                echo "<td style='vertical-align: top;'><span class='badge badge-danger'>".$dia."</span><br>&nbsp;</td>";                                         
                                                                            }else{
                                                                                echo "<td style='vertical-align: top;'><span class='badge badge-secondary'>".$dia."</span><br>$dato</td>";                                                                                
                                                                                }
                                                                                
                                    
                                                                            // Si es el último día de la semana, cerrar la fila y empezar una nueva
                                                                            if (($dia + $primer_dia_semana - 1) % 7 == 0) {
                                                                                echo "</tr><tr style='background-color:#ffffff;'>";
                                                                            }
                                                                        }
                                    
                                                                        // Rellenar los días de la semana después del último día del mes
                                                                        while (($dia + $primer_dia_semana - 1) % 7 != 0) {
                                                                            echo "<td></td>";
                                                                            $dia++;
                                                                        }
                                    
                                                                        echo "</tr>";
                                                                        echo "</table>";

                                ?>

                                </div>

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
    <script src="https://superal.github.io/canvas2image/canvas2image.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
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