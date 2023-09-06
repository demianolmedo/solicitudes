<?php



    $sql = "SELECT * FROM SOLICITUDES";
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
                        <h4 class="modal-title"><small><i class="far fa-copy" style="opacity: 50%;"></i></small> DETALLE SOLICITUD:</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <?php echo $row_mcc["detalle"]; ?>      
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
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
                        <form action="script/cambiar_estado.php" method="post" id="form_asignar" name="form_asignar" required>
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
                            <textarea class="form-control" rows="3" placeholder="Observaciones: <?php echo $row_mcc["obs"]; ?>" id="obs" name="obs" required></textarea>
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



        <!-- /.modal -->
    <?php 
    }
    mysql_close($con_doc); ?>