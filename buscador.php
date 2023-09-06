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
                            <h3 class="card-title">BUSCADOR</h3>
                        </div>
                        <div class="card-header">
                        <div class="input-group">
                        <form action="buscador.php" method="post">

                        <div class="row">
                            <div class="col-4">
                                <input type="text" class="form-control form-control-sm" placeholder="Palabra ..." maxlength="50" id="palabra" name="palabra" required>
                            </div>
                            <div class="col-4">
                                <select class="custom-select rounded-0 custom-select-sm" id="campo" name="campo" required>
                                    <option value="">CAMPO</option>
                                    <option value="id">N° SOLICITUD</option>
                                    <option value="responsable_n">RESPONSABLE</option>
                                    <option value="usuario_solicitud_n">SOLICITANTE</option>
                                    <option value="titulo">TITULO</option>
                                    <option value="asignar_a_n">STAFF TDI</option>
                                    <option value="area_n">AREA</option>
                                </select>
                            </div>
                            <div class="col-4">
                            <button type="submit" class="btn btn-block btn-success btn-sm" id='find'>Buscar</button>
                            <div id="buscando" style="display: none">Buscando ...</div>
                            </div>
                        </div>
                        </div>
                        </form>
                        </div>
                        <!-- /.card-header -->
                        
                        <div class="card-body" id="tabl" style="display: none">
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
                                            $sql="SELECT * FROM SOLICITUDES WHERE ".$campo." like '%".$palabra."%'";
                                            //echo $sql;
                                            $resultado = mysql_query($sql, $con_doc);
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
                                                    <td><?php if ($data["estado"]=='COMPLETADO' || $data["estado"]=='RECHAZADO') {echo "<a href='missolicitudes_fin.php?buscador=SI&id=".$data["id"]."'>".$data["id"]."</a>";}else{echo $data["id"];} ?></td>
                                                    <td><?php echo $data["area_n"] ; ?></td>
                                                    <td><?php echo $data["solicitud_n"]; ?></td>
                                                    <td><?php echo $dep; ?></td>
                                                    <td><b><?php if($data["titulo"]>''){echo $data["titulo"];}else{echo 'TAREA';} ?></b></td>
                                                    <td><?php echo $data["usuario_solicitud_n"]; ?></td>
                                                    <td><?php echo $data["responsable_n"]; ?></td>
                                                    <td><?php echo $data["asignar_a_n"]; ?></td>
                                                    <td><?php echo $data["estado"]; ?></td>
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

                                                            <?php if($data["satisfaccion"]=='SI') {?>
                                                            <div class="col-sm-1 d-flex justify-content-center" title="satisfaccion">
                                                            <a href="#" style='color:#000000; padding:3px' data-toggle="modal" data-target="#modal_satis_<?php echo $data["id"]; ?>"><i class="fa-solid fas fa-thumbs-up"></i></a>
                                                            </div>
                                                            <?php }elseif($data["satisfaccion"]=='no') { ?>
                                                            <div class="col-sm-1 d-flex justify-content-center" title="satisfaccion">
                                                            <a href="#" style='color:#000000; padding:3px' data-toggle="modal" data-target="#modal_satis_<?php echo $data["id"]; ?>"><i class="fa-solid fas  fa-thumbs-down"></i></a>
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
                order: [[10, 'desc']], 

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
            // Definimos la función que se ejecutará cuando se haga clic en el botón
            function miFuncion() {
                //alert("¡Has hecho clic en el botón!");
                // Puedes realizar otras acciones aquí
                var divElement = document.getElementById("buscando");     
                divElement.style.display = "block";
            }

            // Obtenemos el botón por su ID
            var boton = document.getElementById("find");

            // Asignamos la función al evento onclick del botón
            boton.onclick = miFuncion;

            $( "#tabl" ).slideUp( 300 ).delay( 500 ).fadeIn( 400 );
            
        </script>




    <?php
    $resultado = mysql_query($sql, $con_doc);


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
                <script>
                    $(function() {
                        var HTMLstring = '<div><p>Hello, world</p><p>Summernote can insert HTML string</p></div>';
                        //$('.textarea').summernote('pasteHTML', HTMLstring);
                        // Summernote
                        $('#detalle_<?php echo $row_mcc["id"]; ?>').summernote(
                            
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
                        $('#detalle_<?php echo $row_mcc["id"]; ?>').summernote('disable');
                    })
                </script>
        <!-- /.modal -->

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

        <!-- /.modal SATISFACCION-->
        <div class="modal fade" id="modal_satis_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-recycle" style="opacity: 50%;"></i></small>ENCUESTA SATISFACCION</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        SATISFECHO: <b><?php echo $row_mcc["satisfaccion"]; ?></b><br>
                        USUARIO: <b><?php echo $row_mcc["usuario_voto"]; ?></b><br>
                        FECHA: <b><?php echo $row_mcc["fecha_voto"]; ?></b><br>
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