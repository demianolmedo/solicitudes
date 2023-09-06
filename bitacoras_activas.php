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
if ($menu_bitacora == "NO") {
    header('Location: missolicitudes.php');
    die();
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
                            <h3 class="card-title">BITACORAS ACTIVAS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <small>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>F. INCIO</th>
                                            <th>F. FIN</th>
                                            <th>USUARIO CREACION</th>
                                            <th>FECHA CREACION</th>
                                            <th>EVENTOS</th>
                                            <th>EVENTOS DETALLE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include("script/conex_doc.php");
                                        $sql = "SELECT * FROM BITACORAS WHERE date_fin IS NULL ";
                                        $resultado = mysql_query($sql, $con_doc);
                                        while ($data = mysql_fetch_array($resultado)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $data["id"]; ?></td>
                                                <td title="ACTUALIZAR BITACORA"><b><a href="actualiza_bitacora.php?id=<?php echo $data["id"]; ?>"><?php echo $data["titulo"]; ?></a></b></td>
                                                <td><textarea class="form-control form-control-sm" rows="4" cols="30" disabled><?php echo $data["descripcion"]; ?></textarea></td>
                                                <td><?php echo $data["date_ini"]; ?></td>
                                                <td><?php echo $data["date_fin"]; ?></td>
                                                <td><?php echo $data["usuario_carga_n"]; ?></td>
                                                <td><?php echo $data["fecha_carga"]; ?></td>
                                                <td><?php if (!$data["bitacora"]) {echo "";} else {echo "<a href='#' class='dropdown-item' data-toggle='modal' data-target='#modal_bitacora_" . $data["id"] . "'><i class='fas fa-search'></i> </a>";} ?></td>
                                                <td><?php echo $data["bitacora"]; ?></td>
                                            </tr>
                                        <?php }
                                        mysql_close($con_doc); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>TITULO</th>
                                            <th>DESCRIPCION</th>
                                            <th>F. INCIO</th>
                                            <th>F. FIN</th>
                                            <th>USUARIO CREACION</th>
                                            <th>FECHA CREACION</th>
                                            <th>EVENTOS</th>
                                            <th>EVENTOS DETALLE</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </small>
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
                        'targets': [8]
                    },
                    {
                        "width": "5%",
                        "targets": 0
                    }
                ],

                "language": {
                    "url": "script/datatable_Spanish.json"
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

    <?php
    $sql = "SELECT * FROM BITACORAS WHERE date_fin IS NULL";
    $resultado = mysql_query($sql, $con_doc);
    $row_mcc = mysql_fetch_array($resultado);

    while ($row_mcc != 0) {
 ?>



        <!-- /.modal OBSERVACIONES -->
        <div class="modal fade" id="modal_bitacora_<?php echo $row_mcc["id"]; ?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> BITACORA DEL EVENTO: <?php echo $row_mcc["titulo"]; ?></h4>
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
    <?php $row_mcc = mysql_fetch_array($resultado);
    }
    mysql_close($con_doc); ?>


</body>

</html>