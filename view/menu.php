       <?php
include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");

        include("script/conex.php");
        

        $sql = "SELECT AP_PICTURE1 FROM PERSONS WHERE PERSON_ID=" . $dpilogin . "";
        $resultado = mysql_query($sql, $con);
        $data = mysql_fetch_array($resultado);
        $foto = $data['AP_PICTURE1'];

        include("script/conex_doc.php");

        $sol_ven="";
        $sql1 = "SELECT A.id_rel, B.asignar_a, B.asignar_a_n, COUNT(A.id_rel) AS T FROM tablero A INNER JOIN (SELECT AREA_N, asignar_a_n, asignar_a, id FROM SOLICITUDES WHERE estado='EN PROCESO' AND asignar_a like '%".$dpilogin."%') B ON A.id_rel=B.id WHERE fecha_fin<NOW() AND estado IN ('PROCESO', 'PRUEBAS') GROUP BY A.id_rel, B.asignar_a, B.asignar_a_n";
        $resultado1 = mysql_query($sql1, $con_doc);
        $row_cnt = mysql_num_rows($resultado1);
        while ($data1 = mysql_fetch_array($resultado1)) {
            $sol_ven .= '<li class="nav-item">
            <a href="http://atc/TDI/tablero.php?id='.$data1["id_rel"].'" class="nav-link">
            <i class="far fa-circle nav-icon"></i>
            <p>SOL N°: '.$data1["id_rel"].' </p>
            </a>
        </li> ';
        }


        mysql_close($con);
        $logo_data='<img src="imagen/datacom_solo_menu.png" alt="" width="90%" style="padding-top:2px; opacity: .5" /> ';
        $logo_tdi='<img src="imagen/TDI_LOGO.png" alt="" width="40%" style="padding-top:3px; opacity: .9" />';
        ?>
       <!-- Main Sidebar Container  -->
       <aside class="main-sidebar sidebar-dark-primary elevation-4">
           <!-- Brand Logo -->
           <div class="d-flex justify-content-center">
               <?php echo $logo_data; ?>
           </div>           
           <div class="dropdown-divider"></div>
           <!-- Sidebar -->
           <small>
               <div class="sidebar">
                   <!-- Sidebar user panel (optional) -->
                   <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                       <div class="image">
                           <img onerror="src='../portal/images/pe_pictures/117x117/SIN_FOTO.png'" src="../portal/images/pe_pictures/117x117/<?php echo $foto; ?>" class="img-circle elevation-2" alt="User Image">
                       </div>
                       <div class="info">
                           <a href="#" class="d-block"><?php echo $uname; ?></a>
                       </div>
                   </div>

                   <!-- Sidebar Menu -->
                   <nav class="mt-2">
                       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                           <li class="nav-item">
                               <a href="/TDI" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/index.php") {
                                                                    echo "active";
                                                                } ?>">
                                   <i class="nav-icon fas fa-edit"></i>
                                   <p>
                                       REALIZAR UNA SOLICITUD
                                   </p>
                               </a>
                           </li>

                           <li class="nav-item">
                               <a href="missolicitudes.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/missolicitudes.php") {
                                                                                echo "active";
                                                                            } ?>">
                                   <i class="nav-icon fas far fa-bell"></i>
                                   <p>
                                       SOLICITUDES ACTIVAS
                                   </p>
                               </a>
                           </li>

                           <li class="nav-item">
                               <a href="missolicitudes_fin.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/missolicitudes_fin.php") {
                                                                                echo "active";
                                                                            } ?>">
                                   <i class="nav-icon fas fa-thumbs-up"></i>
                                   <p>
                                       SOLICITUDES CERRADAS
                                   </p>
                               </a>
                           </li>

                           <?php if ($menu_reportes == "SI") { ?>
                               

                               <li class="nav-item <?php if ($_SERVER['PHP_SELF'] == "/TDI/reporte_dias_actividades.php") { echo "menu-open"; } ?>">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon far fa-calendar-alt"></i>
                                    <p>
                                    CRONOGRAMA DE TAREAS
                                    <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">    
                                    <li class="nav-item">
                                        <a href="/TDI/reporte_dias_actividades.php?area=1" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reporte_dias_actividades.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>DESARROLLO</p>
                                        </a>
                                    </li>  
                                    <li class="nav-item">
                                        <a href="/TDI/reporte_dias_actividades.php?area=2" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reporte_dias_actividades.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>PBX</p>
                                        </a>
                                    </li> 
                                    <li class="nav-item">
                                        <a href="/TDI/reporte_dias_actividades.php?area=3" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reporte_dias_actividades.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>MICROINFORMATICA</p>
                                        </a>
                                    </li>        
                                    <li class="nav-item">
                                        <a href="/TDI/reporte_dias_actividades.php?area=4" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reporte_dias_actividades.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>WFO</p>
                                        </a>
                                    </li>                       
                                </ul>
                            </li>

                            <li class="nav-item <?php if ($_SERVER['PHP_SELF'] == "/TDI/cronograma_sol_cerradas.php") { echo "menu-open"; } ?>">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>
                                    SOL. CERRADAS X DIA
                                    <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">    
                                    <li class="nav-item">
                                        <a href="/TDI/cronograma_sol_cerradas.php?area=1" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/cronograma_sol_cerradas.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>DESARROLLO</p>
                                        </a>
                                    </li>  
                                    <li class="nav-item">
                                        <a href="/TDI/cronograma_sol_cerradas.php?area=2" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/cronograma_sol_cerradas.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>PBX</p>
                                        </a>
                                    </li> 
                                    <li class="nav-item">
                                        <a href="/TDI/cronograma_sol_cerradas.php?area=3" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/cronograma_sol_cerradas.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>MICROINFORMATICA</p>
                                        </a>
                                    </li>        
                                    <li class="nav-item">
                                        <a href="/TDI/cronograma_sol_cerradas.php?area=4" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/cronograma_sol_cerradas.php?area=1") { echo ""; } ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>WFO</p>
                                        </a>
                                    </li>                       
                                </ul>
                            </li>

                            <li class="nav-item">
                               <a href="/TDI/reportes_cuadro.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reportes_cuadro.php") {
                                                                    echo "active";
                                                                } ?>">
                                   <i class="nav-icon fas fa-chart-pie"></i>
                                   <p>
                                       REPORTE E INDICADORES
                                   </p>
                               </a>
                           </li>

                           <li class="nav-item">
                               <a href="/TDI/buscador.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/buscador.php") {
                                                                    echo "active";
                                                                } ?>">
                                   <i class="nav-icon fas fa-search"></i>
                                   <p>
                                       BUSCADOR
                                   </p>
                               </a>
                           </li>

                           <?php }
                            if ($menu_bitacora == "SI") { ?>

                               <li class="nav-item">
                                   <a href="reg_bitacoras.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/reg_bitacoras.php") {
                                                                                    echo "active";
                                                                                } ?>">
                                       <i class="nav-icon far fa-circle text-warning"></i>
                                       <p>
                                           REGISTRO DE BITACORAS
                                       </p>
                                   </a>
                               </li>

                               <li class="nav-item">
                                   <a href="bitacoras_activas.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/bitacoras_activas.php") {
                                                                                        echo "active";
                                                                                    } ?>">
                                       <i class="nav-icon far fa-circle text-secondary"></i>
                                       <p>
                                           BITACORAS ACTIVAS
                                       </p>
                                   </a>
                               </li>

                               <li class="nav-item">
                                   <a href="bitacoras_cerradas.php" class="nav-link <?php if ($_SERVER['PHP_SELF'] == "/TDI/bitacoras_cerradas.php") {
                                                                                        echo "active";
                                                                                    } ?>">
                                       <i class="nav-icon far fa-circle text-secondary"></i>
                                       <p>
                                           BITACORAS CERRADAS
                                       </p>
                                   </a>
                               </li>

                           <?php } ?>

                           <li class="nav-item">
                               <a href="index.php?do=cerrar" class="nav-link">
                                   <i class="nav-icon far fa-circle text-danger"></i>
                                   <p>
                                       SALIR DEL SISTEMA
                                   </p>
                               </a>
                           </li>

                           <?php if (($l5_unidad=='22' || $l5_unidad=="35") && ($l7_cargo=='29' || $l7_cargo=='12' || $l7_cargo=='75' || $l7_cargo=='74')) { ?>
                           <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-bullhorn"></i>
                                    <p>
                                    SOL. VENCIDAS
                                    <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php echo $sol_ven; ?>                                    
                                </ul>
                            </li>
                            <?php } ?>





                       </ul>
                   </nav>
           </small>
           <!-- /.sidebar-menu -->

           </div>
           <!-- /.sidebar -->
       </aside>