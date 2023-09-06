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


include("script/perfiles.php");

include("script/conex_doc.php");
$responsable_select = "";
$sql = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USUARIOS_ACTIVOS ORDER BY DESCRIPTION";
$resultado1 = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $responsable_select .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

// LOS QUERYS ESTAN EN PERFILES
$staff_desarrollo = "";
$resultado1 = mysql_query($sql_desa, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $staff_desarrollo .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

$staff_pbx = "";
$resultado1 = mysql_query($sql_pbx, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $staff_pbx .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

$staff_micro = "";
$resultado1 = mysql_query($sql_micro, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $staff_micro .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

$staff_wfo = "";
$resultado1 = mysql_query($sql_wfo, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $staff_wfo .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

$staff_todos = "";
$resultado1 = mysql_query($sql_tdi, $con_doc);
while ($data = mysql_fetch_array($resultado1)) {
    $staff_todos .= "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
}

?>
<!DOCTYPE html>
<html>

<head>
    <?php include "view/head.php"; ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed ">
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


            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="col-sm-6">
                        <B>Bienvenido <?php echo date('d-m-Y'); ?></B>
                    </div><!-- /.col -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="card card-primary card-outline">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">SOLICITUDES ACTIVAS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body" id="tabl"  style="display: none">
                            <div class="col-sm-12">
                                <small>
                                    <table id="example1" class="table table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>SOL.</th>
                                                <th>AREA</th>
                                                <th>SOLICITUD</th>
                                                <th>CIUDAD</th>
                                                <th>TITULO</th>
                                                <th>SOLICITANTE</th> 
                                                <th>RESPONSABLE</th>
                                                <th>STAFF TDI</th>
                                                <th>ESTADO</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>DETALLES</th>
                                                <th>OBS</th>
                                                <th>DETALLE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $dep="";
                                            include("script/conex_doc.php");
                                            $resultado = mysql_query($sql_missolicitudes, $con_doc);
                                            while ($data = mysql_fetch_array($resultado)) {
                                                
                                                if ($data["departamento_usuario_solicitud"]=="1"){$dep="LA PAZ";}
                                                if ($data["departamento_usuario_solicitud"]=="2"){$dep="SANTA CRUZ";}
                                                if ($data["departamento_usuario_solicitud"]=="3"){$dep="COCHABAMBA";}
                                                if ($data["departamento_usuario_solicitud"]=="4"){$dep="ORURO";}
                                                if ($data["departamento_usuario_solicitud"]=="5"){$dep="POTOSI";}
                                                if ($data["departamento_usuario_solicitud"]=="6"){$dep="TARIJA";}
                                                if ($data["departamento_usuario_solicitud"]=="7"){$dep="BENI";}
                                                if ($data["departamento_usuario_solicitud"]=="8"){$dep="PANDO";}
                                                if ($data["departamento_usuario_solicitud"]=="9"){$dep="CHUQUISACA";}
                                            ?>
                                                <tr>
                                                    <td><?php echo $data["id"]; ?></td>
                                                    <td><?php echo $data["area_n"] ; ?></td>
                                                    <td><?php echo $data["solicitud_n"]; ?></td>
                                                    <td><?php echo $dep; ?></td>
                                                    <td title="IR AL TABLERO DE TAREAS DE LA SOLICITUD"><b><a href="tablero.php?id=<?php echo $data["id"]; ?>"><?php if($data["titulo"]>''){echo $data["titulo"];}else{echo 'TAREA';} ?></a></b></td>
                                                    <td><?php echo $data["usuario_solicitud_n"]; ?></td>
                                                    <td title="CAMBIAR LOS RESPONSABLES DE LA SOLICITUD"><?php echo "<a style='color:#000000;' href='#'  data-toggle='modal' data-target='#modal_responsable_" . $data["id"] . "'>" . $data["responsable_n"] . "</a>"; ?></td>
                                                    <td><?php
                                                        if ($asignar_a_n_p == "staff") {
                                                            if (!$data["asignar_a_n"]) {
                                                                echo "<a href='#' style='color:#000000;' data-toggle='modal' data-target='#modal_asignar_" . $data["id"] . "'>ASIGNAR</a>";
                                                            } else {
                                                                echo "<a href='#' style='color:#000000;' data-toggle='modal' data-target='#modal_asignar_" . $data["id"] . "'>" . $data["asignar_a_n"] . "</a>";
                                                            }
                                                        } else {
                                                            echo $data["asignar_a_n"];
                                                        }
                                                        ?></td>
                                                    <td title="CAMBIAR ESTADO DE LA SOLICITUD Y COLOCAR OBSERVACIONES"><a href="#" style='color:#000000;' data-toggle="modal" data-target="#modal_estado_<?php echo $data["id"]; ?>"><?php echo $data["estado"]; ?></a></td>
                                                    <td><?php echo $data["fecha_solicitud"]; ?><br>
                                                    <?php 
                                                        $dateDifference = abs(strtotime(date("Y-m-d H:i:s")) - strtotime($data["fecha_solicitud"]));

                                                        $years  = floor($dateDifference / (365 * 60 * 60 * 24));
                                                        $months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                                        $days   = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));
                                                        $horas  = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24 - $days * 60 * 60 * 24) / (60 * 60 ));
                                                        $min    = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24 - $days * 60 * 60 * 24 - $horas * 60 *60) / (60 ));
                                                        
                                                        echo "A:".$years ." M:".$months." D:".$days." H:".$horas." M:".$min;
                                                     ?>
                                                    </td>
                                                    <td><textarea><?php echo $data["detalle"]; ?></textarea></td>
                                                    <td>.</td>
                                                    <td>

                                                    <div class="container">
                                                        <div class="row">

                                                            <div class="col-sm-1 d-flex justify-content-center" title="INFORMACION DE LA SOLICITUD">
                                                                <a href="#" style='color:#000000; padding:3px' data-toggle="modal" data-target="#modal_<?php echo $data["id"]; ?>"><i class="nav-icon fas fa-book"></i></a>
                                                            </div>

                                                            <div class="col-sm-1 d-flex justify-content-center" title="OBSERVACIONES">
                                                                <a href="#" style='color:#000000; padding:3px' data-toggle="modal" data-target="#modal_obs_<?php echo $data["id"]; ?>"><i class="nav-icon fas fa-comment"></i></a>
                                                            </div>

                                                            <div class="col-sm-1 d-flex justify-content-center" title="BITACORA">
                                                            <?php echo '<a href="#" style="color:#000000; padding:3px" data-toggle="modal" data-target="#modal_bitacora_' . $data["id"] . '"><i class="fas fa-search"></i> </a>';  ?>
                                                            </div>

                                                            <div class="col-sm-1 d-flex justify-content-center" title="USUARIO QUE REALIZO LA SOLICITUD">
                                                            <a href="#" style="color:#000000; padding:3px" data-toggle="modal" data-target="#modal_usuariocreacion_<?php echo $data["id"]; ?>"><i class="fas fa-user"></i></a>
                                                            </div>

                                                            <div class="col-sm-1 d-flex justify-content-center" title="INFORME DE LA SOLICITUD">
                                                            <a href="informe_solicitud.php?id=<?php echo $data["id"]; ?>" style="color:#000000; padding:3px" ><i class="fas fa-eye"></i></a>
                                                            </div>

                                                            <?php if(!$data["adjunto"]) {} else {?>
                                                            <div class="col-sm-1 d-flex justify-content-center" title="ARCHIVO ADJUNTO">
                                                            <a href="adjuntos/<?php echo $data["adjunto"]; ?>" style="color:#000000; padding:3px" ><?php echo "<i class='fas fa-download'></i>"; ?></a>
                                                            </div>
                                                            <?php } ?>

                                                            <?php if(!$data["id_solicitud_padre"]) {} else {?>
                                                            <div class="col-sm-1 d-flex justify-content-center" title="SOLICITUD ENCAMINADA">
                                                            <a href="#" style='color:#000000; padding:3px' data-toggle="modal" data-target="#modal_enca_<?php echo $data["id"]; ?>"><i class="nav-icon fas fa-recycle"></i></a></a>
                                                            </div>
                                                            <?php } ?>
                                                            
                                                                                                                    
                                                        </div>
                                                    </div>       
                                                    
                                                    </td>                                                
                                                    
                                                </tr>
                                            <?php }
                                            mysql_close($con_doc); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>SOL.</th>
                                                <th>AREA</th>
                                                <th>SOLICITUD</th>
                                                <th>CIUDAD</th>
                                                <th>TITULO</th>
                                                <th>SOLICITANTE</th> 
                                                <th>RESPONSABLE</th>
                                                <th>STAFF TDI</th>
                                                <th>ESTADO</th>
                                                <th>FECHA SOLICITUD</th>
                                                <th>DETALLES</th>
                                                <th>OBS</th>
                                                <th>DETALLE</th>  

                                            </tr>
                                        </tfoot>
                                    </table>
                                </small>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </section>


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

    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js">
    </script>
    <script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
    </script>

    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js">
    </script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.1/css/buttons.dataTables.min.css">


    <script>
        $(document).ready(function() {



            $('#example1 tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" style="width: 100%;"/>');
            });

            $("#example1").DataTable({
                order: [[0, 'asc']], 

                paging: true,
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ],

                'columnDefs': [
                    //hide the second & fourth column
                    
                    {
                        'visible': false,
                        'targets': [5,10,11]
                    },
                    { "width": "18%", 
                      "targets": [4,6,7,9],
                     }
                     ,
                    { "width": "11%", 
                      "targets": [12],
                     },
                     
                ],

                "language": {
                    "url": "script/datatable_Spanish.json",
                    "processing": "<img src='imgagen/ZZ5H.gif'> Loading..."
                },
                initComplete: function() {
                    // Apply the search
                    this.api()
                        .columns()
                        .every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value).draw();
                                }
                            });
                        });
                }




            });

        })
    </script>


        <script>           
                $( "#tabl" ).slideUp( 300 ).delay( 1000 ).fadeIn( 400 );     
        </script>

    <?php
    $resultado = mysql_query($sql_missolicitudes, $con_doc);


    while ($row_mcc = mysql_fetch_array($resultado)) {

        if (strlen($row_mcc["asignar_a_n"]) < 1) {
            $holder = "ASIGNAR A ...";
            $tipo = "asignar";
        } else {
            $holder = "CAMBIAR A ...";
            $tipo = "cambiar";
        }
        if (strlen($row_mcc["bitacora"]) < 1) {
            $bitacora = "";
        } else {
            $bitacora = "bitacora";
        } ?>

        <!-- /.modal DETALLE SOLICITUD -->
        <div class="modal fade" id="modal_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> DETALLE SOLICITUD: <?php echo $row_mcc["id"];?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    <div class="form-group" id="descripcion">
                        <textarea class="textarea" placeholder="Texto aqui" id="detalle_<?php echo $row_mcc["id"]; ?>" name="detalle_<?php echo $row_mcc["id"]; ?>" disabled><?php echo $row_mcc["detalle"]; ?></textarea>
                    </div>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- /.modal DETALLE BITACORA -->
        <div class="modal fade" id="modal_bitacora_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> BITACORA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $row_mcc["bitacora"]; ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal ESTADO -->
        <div class="modal fade" id="modal_estado_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> <?php echo "SOL.: " . $row_mcc["id"] . " TITULO: " . $row_mcc["titulo"]; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="script/cambiar_estado.php" method="post" id="form_asignar" name="form_asignar"  enctype="multipart/form-data" required>
                            <select class="form-control" id="estado" name="estado">
                                <?php if (strlen($row_mcc["asignar_a_n"]) > 0) { ?>
                                    <option value="EN PROCESO" <?php if ($row_mcc["estado"] == "EN PROCESO") {echo "selected";} ?>>EN PROCESO</option>
                                    <option value="COMPLETADO" <?php if ($row_mcc["estado"] == "COMPLETADO") {echo "selected";} ?>>COMPLETADO</option>
                                <?php } else { ?>
                                    <option value="SIN ASIGNAR" <?php if ($row_mcc["estado"] == "SIN ASIGNAR") {echo "selected"; } ?>>SIN ASIGNAR</option>
                                    <option value="RECHAZADO" <?php if ($row_mcc["estado"] == "RECHAZADO") {echo "selected"; } ?>>RECHAZADO</option>
                                <?php } ?>
                            </select>
                            
                            <input id="id" name="id" type="hidden" value=<?php echo $row_mcc["id"]; ?>>
                            <input id="bitacora" name="bitacora" type="hidden" value="<?php echo $bitacora; ?>">
                            <div class="dropdown-divider"></div>
                            <textarea class="textarea" placeholder="Texto aqui" id="obs" name="obs" required></textarea>
                            <div class="dropdown-divider"></div> 
                            <?php if ($row_mcc["estado"] == "EN PROCESO") { ?> 
                                
                                <div class="col-sm-6" id="archivo">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="custom-file" id="archivo">
                                                    <input type="file" class="custom-file-input" id="adjunto" name="adjunto" accept=".doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                                    <label class="custom-file-label form-control-sm" for="exampleInputFile">ADJUNTAR ARCHIVO</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script language="javascript">
                                        $(document).ready(function() {

                                            $('#adjunto').bind('change', function() {

                                                var fileName = this.files[0].name;
                                                var ext = fileName.split('.');
                                                // ahora obtenemos el ultimo valor despues el punto
                                                // obtenemos el length por si el archivo lleva nombre con mas de 2 puntos
                                                ext = ext[ext.length - 1];

                                                if (ext != 'doc' && ext != 'docx' && ext != 'xls' && ext != 'xlsx' && ext != 'ppt' && ext != 'pptx' && ext != 'zip' && ext != 'rar' && ext != 'wav' && ext != 'jpg'  && ext != 'jpeg' && ext != 'png') {
                                                    alert('El archivo no tiene la extensión adecuada');
                                                    this.value = ''; // reset del valor
                                                    this.files[0].name = '';
                                                }


                                                if (this.files[0].size > 50000000) {
                                                    alert("EL ARCHIVO ADJUNTO NO DEBE SER MAYOR A 50 MB");
                                                    $("#adjunto").val("");
                                                }

                                            });

                                        });
                                    </script>

                                    
                                
                            <?php }?>
                            <div class="dropdown-divider"></div>
                            <button type="submit" class="btn btn-block btn-outline-primary btn-xs" id="boton">ACTUALIZAR</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal ASIGNAR -->
        <div class="modal fade" id="modal_asignar_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> <?php echo "SOL.: " . $row_mcc["id"] . " TITULO: " . $row_mcc["titulo"]; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="script/asignar_a.php" method="post" id="form_asignar" name="form_asignar">

                            <select class="select2 form-control-sm" multiple="multiple" data-placeholder="<?PHP echo $holder; ?>" data-dropdown-css-class="select2-blue" style="width: 100%;" id="asignar_a_<?php echo $row_mcc["id"]; ?>" name=asignar_a[] required>
                                <?php
                                $area = $row_mcc["area"];
                                include("script/conex.php");
                                if ($area == "1") {
                                    echo $staff_desarrollo;
                                } elseif ($area == "2") {
                                    echo $staff_pbx;
                                } elseif ($area == "3") {
                                    echo $staff_micro;
                                } elseif ($area == "4") {
                                    echo $staff_wfo;
                                } elseif ($area == "5") {
                                    echo $staff_todos;
                                }

                                ?>
                            </select>
                            <input id="id" name="id" type="hidden" value=<?php echo $row_mcc["id"]; ?>>
                            <input id="tipo" name="tipo" type="hidden" value=<?php echo $tipo; ?>>
                            <input id="bitacora" name="bitacora" type="hidden" value=<?php echo $bitacora; ?>>
                            <div class="dropdown-divider"></div>
                            <button type="submit" class="btn btn-block btn-outline-primary btn-xs" id="boton">ASIGNAR SOLICITUD</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /.modal ACTUALIZAR RESPONSABLE -->
        <div class="modal fade" id="modal_responsable_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> <?php echo "SOL.: " . $row_mcc["id"] . " TITULO: " . $row_mcc["titulo"]; ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="script/cambia_responsable.php" method="post" id="form_responsable" name="form_responsable">

                            <select class="select2 form-control-sm" multiple="multiple" data-placeholder="ACTUALIZAR RESPONSABLE..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="responsable<?php echo $row_mcc["id"]; ?>" name=responsable[] required>
                                <?php echo $responsable_select; ?>
                            </select>
                            <input id="id" name="id" type="hidden" value=<?php echo $row_mcc["id"]; ?>>
                            <input id="tipo" name="tipo" type="hidden" value=<?php echo $tipo; ?>>
                            <input id="bitacora" name="bitacora" type="hidden" value=<?php echo $bitacora; ?>>
                            <div class="dropdown-divider"></div>
                            <button type="submit" class="btn btn-block btn-outline-primary btn-xs" id="boton">ACTUALIZAR RESPONSABLE</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- /.modal OBSERVACIONES -->
        <div class="modal fade" id="modal_obs_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> OBSERVACIONES:</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <?php echo $row_mcc["obs"]; ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <!-- /.modal USURIO CREACION DE LA SOLICITUD -->
        <div class="modal fade" id="modal_usuariocreacion_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> USUARIO QUE REALIZO LA SOLICITUD</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo $row_mcc["usuario_solicitud_n"]; ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- /.modal SOLICITUD ENCAMINADA-->
        <div class="modal fade" id="modal_enca_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-recycle" style="opacity: 50%;"></i></small>SOLICITUD ENCAMINADA</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        SOLICITUD PROCEDENTE N°: <?php echo $row_mcc["id_solicitud_padre"]; ?>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->



        <!-- /.modal -->
    <?php 
    }
    mysql_close($con_doc); ?>

<script>
$(function() {
    var HTMLstring = '<div><p>Hello, world</p><p>Summernote can insert HTML string</p></div>';
    //$('.textarea').summernote('pasteHTML', HTMLstring);
    // Summernote
    $('.textarea').summernote(
        
        {
        //lang: 'fr-FR',       
        placeholder: 'DETALLE...',     
        height: 400,
        toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['style', ['bold', 'italic', 'underline']],
            ['font', ['fontsize', 'fontname', 'strikethrough', 'underline']],
            ['font', ['strikethrough', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['para', ['ul', 'ol']],
            ['link', ['link']],
            ['table', ['table']],
            ['picture', ['picture']],
            ['view', ['codeview']]
        ],
        onImageUpload: function(files, editor, welEditable) {
            for (var i = files.lenght - 1; i >= 0; i--) {
                sendFile(files[i], this);
            }
        }
    });
    $('.note-editor .note-editable').css("line-height", 1);
})
</script>


</body>

</html>