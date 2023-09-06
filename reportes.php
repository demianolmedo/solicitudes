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

$nombre = "";
$enproceso = "";
$completado = "";



$sql = "SELECT L3_AREA, USER_NAME, USER_ID, DESCRIPTION FROM APPLPORDB.USERS WHERE L6_PLATAFORMA = '0' AND END_DATE ='0000-00-00 00:00:00' AND L7_CARGO = '29' ORDER BY L3_AREA";

$resultado = mysql_query($sql, $con_doc);
while ($data = mysql_fetch_array($resultado)) {

    $sql1="SELECT SUM(CASE WHEN estado='EN PROCESO' THEN 1 ELSE 0 END) AS ENPROCESO, SUM(CASE WHEN estado='COMPLETADO' THEN 1 ELSE 0 END) AS COMPLETADO FROM SOLICITUDES WHERE asignar_a_n LIKE '%".$data["DESCRIPTION"]."%' GROUP BY estado;";
    $resultado1 = mysql_query($sql1, $con_doc);
    while ($data1 = mysql_fetch_array($resultado1)) {

        $nombre .= "'" . $data["USER_NAME"] . "', ";
        $enproceso .= $data1["ENPROCESO"] . ", ";
        $completado .= $data1["COMPLETADO"] . ", ";

    }


    
}
mysql_close($con_doc);

$nombre1 = substr($nombre, 0, -2);
$enproceso1 = substr($enproceso, 0, -2);
$completado1 = substr($completado, 0, -2);



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
                    <br>
                    <div class="col-md-12">


                        <!-- DONUT CHART -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">REPORTES</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <div style="height:400px;">
                                    <canvas id="densityChart"></canvas>
                                </div>
                                <script>
                                    var densityCanvas = document.getElementById("densityChart");

                                    Chart.defaults.global.defaultFontFamily = "Arial";
                                    Chart.defaults.global.defaultFontSize = 12;

                                    var enprocesodata = {
                                        label: 'EN PROCESO',
                                        data: [<?php echo $enproceso1; ?>],
                                        backgroundColor: 'rgb(90, 170, 255)',
                                        borderColor: 'rgba(255, 255, 255, 0.6)',
                                        yAxisID: "y-axis-valores"
                                    };

                                    var completadodata = {
                                        label: 'COMPLETADO',
                                        data: [<?php echo $completado1; ?>],
                                        backgroundColor: 'rgb(116, 198, 135)',
                                        borderColor: 'rgba(255, 255, 255, 0.6)',
                                        yAxisID: "y-axis-valores"
                                    };

                                    var planetData = {
                                        labels: [<?php echo $nombre1; ?>],
                                        datasets: [enprocesodata, completadodata]
                                    };

                                    var chartOptions = {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            xAxes: [{
                                                barPercentage: 1,
                                                categoryPercentage: 0.7
                                            }],
                                            yAxes: [{
                                                id: "y-axis-valores"
                                            }]
                                        }
                                    };

                                    var barChart = new Chart(densityCanvas, {
                                        type: 'bar',
                                        data: planetData,
                                        options: chartOptions
                                    });
                                </script>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->



                    </div>
                    <!-- /.col (LEFT) -->

                </div>
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






</body>

</html>