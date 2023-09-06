       <!-- Navbar -->
       <nav class="main-header navbar navbar-expand  navbar-dark navbar-<?php echo $color; ?>">
           <!-- Left navbar links -->
           <ul class="navbar-nav">
               <li class="nav-item">
                   <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
               </li>
               <li class="nav-item d-none d-sm-inline-block">
                   <a href="tablero.php?id=<?php echo $_REQUEST["id"]; ?>" class="nav-link">T.D.I.: <?php echo $tablero; ?></a>
               </li>

           </ul>

           <ul class="navbar-nav ml-auto">
               <!-- Messages Dropdown Menu -->
               <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="#" title="INTEGRANTES DE LA SOLICITUD">
                       <i class="fas fa-users"></i>
                       <span class="badge badge-warning navbar-badge"><?php echo $x; ?></span>
                   </a>
                   <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                       <?php $sql2 = "SELECT PERSON_ID, AP_PICTURE1, FULL_NAME FROM APPLPORDB.PERSONS WHERE PERSON_ID IN (" . $mienbros2 . ")";
                        $resultado2 = mysql_query($sql2, $con_doc);
                        $integrantes_solicitud = "";
                        while ($data2 = mysql_fetch_array($resultado2)) {
                        ?>
                           <a href="#" class="dropdown-item">
                               <!-- Message Start -->
                               <div class="media">
                                   <img onerror="src='../portal/images/pe_pictures/117x117/SIN_FOTO.png'" src="../portal/images/pe_pictures/117x117/<?php echo $data2["AP_PICTURE1"]; ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">

                                   <div class="media-body">
                                       <small>
                                           <?php echo $data2["FULL_NAME"];
                                            $integrantes_solicitud .= '<option value="' . $data2["PERSON_ID"] . '">' . $data2["FULL_NAME"] . '</option>'; ?>
                                       </small>
                                   </div>
                               </div>
                               <!-- Message End -->
                           </a>
                           <div class="dropdown-divider"></div>
                       <?php  } ?>

                   </div>
               </li>
               <!-- Notifications Dropdown Menu  
               <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                       <i class="far fa-bell"></i>
                       <span class="badge badge-danger navbar-badge">5</span>
                   </a>
                   <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                       <span class="dropdown-item dropdown-header">5 NOTIFICACIONES</span>
                       <div class="dropdown-divider"></div>
                       <a href="#" class="dropdown-item">
                           <i class="far fa-clock mr-1"></i> VENCIDOS
                           <span class="float-right text-muted text-sm">2</span>
                       </a>
                       <div class="dropdown-divider"></div>
                       <a href="#" class="dropdown-item">
                           <i class="far fa-clock mr-1"></i> POR VENCER
                           <span class="float-right text-muted text-sm">3</span>
                       </a>

                   </div>
               </li> -->

               <!-- Notifications Dropdown Menu -->
               <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" title="INFORMACION DE LA SOLICITUD">
                       <i class="nav-icon fas fa-book"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                       <span class="dropdown-item dropdown-header">INFO SOLICITUD</span>
                       <div class="dropdown-divider"></div>
                       <div class="media-body">
                           <div class="container">
                               <small class="text-sm text-muted">
                                   <?php echo $data["detalle"]; ?>
                               </small>
                           </div>
                       </div>
                   </div>
               </li>

               <!-- Notifications Dropdown Menu -->
               <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" title="OBSERVACIONES">
                       <i class="nav-icon far fa-comment"></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                       <span class="dropdown-item dropdown-header">OBSERVACIONES</span>
                       <div class="dropdown-divider"></div>
                       <div class="media-body">
                           <div class="container">
                               <small class="text-sm text-muted">
                                   <?php echo $data["obs"]; ?>
                               </small>
                           </div>
                       </div>
                   </div>
               </li>

               <!-- Notifications Dropdown Menu -->
               <li class="nav-item dropdown">
                   <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" title="BITACORA">
                       <i class='fas fa-search'></i>
                   </a>
                   <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                       <span class="dropdown-item dropdown-header">BITACORA</span>
                       <div class="dropdown-divider"></div>
                       <div class="media-body">
                           <div class="container">
                               <small class="text-sm text-muted">
                                   <?php echo $data["bitacora"]; ?>
                               </small>
                           </div>
                       </div>
                   </div>
               </li>

               <li class="nav-item dropdown">
                   <a class="nav-link" href="informe_solicitud.php?id=<?php echo $data["id"]; ?>" aria-expanded="false" title="INFORME GENERAL DE LA SOLICITUD">
                       <i class="fas fa-eye"></i>
                   </a>
               </li>

               <!-- Notifications Dropdown Menu -->
               <?php if (strlen($data["adjunto"]) > 0) { ?>
                   <li class="nav-item dropdown">
                       <a class="nav-link" href="adjuntos/<?php echo $data["adjunto"]; ?>" aria-expanded="false" title="ARCHIVO ADJUNTO">
                           <i class="fas fa-download"></i>
                       </a>
                   </li>
               <?php } ?>

               

               <!-- Notifications Dropdown Menu
               <li class="nav-item dropdown" title="CERRAR">
                   <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                       <i class="icon fas fa-ban"></i>
                   </a>
               </li> -->

           </ul>

       </nav>
       <!-- /.navbar -->