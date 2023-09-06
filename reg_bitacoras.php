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
            <form action="script/guarda_bitacora.php" method="post">
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

                                            <h3 class="profile-username text-center">REGISTRO DE BITACORAS</b></h3>
                                            <hr>
                                            <br>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>TITULO DE LA BITACORA</label>
                                                    <input type="text" class="form-control form-control-sm" placeholder="TITULO DEL EVENTO" id="titulo" name="titulo" maxlength="50" required>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>FECHA INICIO</label>
                                                    <input type="text" class="form-control float-right form-control-sm" id="date_ini" name="date_ini">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" id="cambiarfecha" name="cambiarfecha">FECHA FINALIZACION</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control float-right form-control-sm" id="date_fin" name="date_fin" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <label>DESCRIPCION</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control form-control-sm" rows="4" placeholder="DESCRIPCION ..." required id="descripcion" name="descripcion" desc></textarea>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" class="btn btn-block btn-warning btn-xs" id="guardar" name="guardar">GUARDAR</button>


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
            </form>

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

    <script language="javascript">
        $(document).ready(function() {
            $("#cambiarfecha").on('change', function() {

                if ($('#cambiarfecha').is(":checked")) {

                    $("#date_fin").prop("disabled", false);
                    if ($("#date_ini").val() > $("#date_fin").val()) {
                        $("#guardar").prop("disabled", true);
                    }

                    $("#date_fin").on('change', function() {
                        if ($("#date_ini").val() > $("#date_fin").val()) {
                            $("#guardar").prop("disabled", true);
                        }
                        else {$("#guardar").prop("disabled", false);}
                    });

                    $("#date_ini").on('change', function() {
                        if ($("#date_ini").val() > $("#date_fin").val()) {
                            $("#guardar").prop("disabled", true);
                        }
                        else {$("#guardar").prop("disabled", false);}
                    });

                } else {
                    $("#date_fin").prop("disabled", true);
                    $("#guardar").prop("disabled", false);
                }

            });

        });
    </script>


</body>

</html>