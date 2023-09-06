<script type="text/javascript">

	var navegador = navigator.userAgent;

	if (navegador.indexOf("Chrome") != -1) {

		//alert("Estás usando Chrome");

	}

	else if (navegador.indexOf("Firefox") != -1) {

		//alert("Estás usando Firefox");

	}

	else if (navegador.indexOf("Safari") != -1) {

		//alert("Estás usando Safari");

	}

	else if (navegador.indexOf("MSIE") != -1) {

		alert("Por favor abrir la aplicacion con Chrome");

        window.close();

        window.location.replace("http://atc");

	}

	else {

        alert("Por favor abrir la aplicacion con Chrome");

        window.close();

        window.location.replace("http://atc");

	}

</script>





<?php

include_once "models/rutas.php";

$cfgProgDir = '../phpSecurePages/';

include($cfgProgDir . "secure.php");



if ($l7_cargo==69 || $l7_cargo==71){

    alert("Las solicitudes TDI deben ser realizadas por su supervisor inmediato.");    

    window.location.replace("http://atc");}



include("script/conex_doc.php");

include("script/perfiles.php");



$do=$_REQUEST["do"];



if (isset($do) && ($do=='cerrar')) { 

    session_destroy();

    header("Location: index.php"); 

}



$resultado = mysql_query($sql_area, $con_doc);

$row_cnt = mysql_num_rows($resultado);

$solicitudes = "";

while ($data = mysql_fetch_array($resultado)) {

    $area .= "<option value='" . $data['id'] . ",".$data['area']."'>&nbsp;&nbsp;&nbsp;&nbsp;" . $data['area'] . "</option>";

}

mysql_close($con_doc);



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

        echo "<script>const str = '" . $_GET["msg"] . "'; const msgArray = str.split(','); const msgAlert=msgArray[0]+'\\n'+msgArray[1]; alert(msgAlert)</script>";

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

                        <!-- <B>Bienvenido <?php echo date('d-m-Y'); ?><button onclick="executeFunction()"></button></B> -->
                        <B>Bienvenido <?php echo date('d-m-Y'); ?></B>

                        <script>
                            function executeFunction() {
                                // Use AJAX to call the PHP function
                                var xhr = new XMLHttpRequest();
                                xhr.open("GET", "python.php?function=myFunction", true);
                                xhr.send();
                            }
                        </script>

                    </div><!-- /.col -->

                </div><!-- /.container-fluid -->

            </div>

            <!-- /.content-header -->





            <!-- Main content -->

            <form action="script/guarda_solicitud.php" method="post" id="form1" name="form1" enctype="multipart/form-data">



                <section class="content">

                    <div class="container-fluid">

                        <div class="row">

                            <div class="col-md-3">



                                <!-- Profile Image -->

                                <div class="card card-primary card-outline">

                                    <div class="card-body box-profile">

                                        <!-- /.<div class="text-center">

                                            <img class="profile-user-img img-fluid img-circle" onerror="src='../portal/images/pe_pictures/117x117/SIN_FOTO.png'" src="../portal/images/pe_pictures/117x117/<?php echo $foto; ?>" alt="<?php echo $uname; ?>">

                                        </div> -->

                                        <h3 class="profile-username text-center">SOLICITUD DE SOPORTE</h3>

                                        <ul class="list-group list-group-unbordered mb-3">

                                            <li class="list-group-item">

                                                <select class="form-control form-control-sm" name="area" id="area" required>

                                                    <option selected disabled>SELECCIONAR AREA</option>

                                                    <?php echo $area; ?>

                                                </select>



                                            </li>

                                            <li class="list-group-item" id="f1"> </li>

                                        </ul>

                                    </div>

                                    <!-- /.card-body -->

                                </div>

                                <!-- /.card -->



                            </div>

                            <!-- /.col -->



                            <div class="col-md-9">



                                <li class="list-group-item" id="f2">

                                </li>



                                



                                







                            </div>

                            <!-- /.col -->



                        </div>

                        <!-- /.row -->

                    </div><!-- /.container-fluid -->

                </section>



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

            $("#area").on('change', function() {

                $("#area option:selected").each(function() {

                    area = $(this).val();

                    $.post("view/opciones_solicitud.php", {

                        area: area

                    }, function(data) {

                        $("#f1").html(data);

                        $("#f2").html("");

                        $("#descripcion").hide();

                        $("#contacto").hide();

                        $("#archivo").hide();

                        $("#boton").hide();

                        $("#description1").val("");

                    });

                });

            });







        });

    </script>



    <script type="text/javascript">

        $(document).ready(function(e) {

            $("#form1").submit(function() {

                $.post(url, $.param($(this).serializeArray()), function(data) {});

            });



        });

    </script>



</body>



</html>