<?php
session_start();

if (!$_SESSION['login'] ){
    echo '<script type="text/javascript">
    window.open("http://atc/TDI/", "_self");
    </script>';
}

include_once "../models/rutas.php";
$cfgProgDir = '../../phpSecurePages/';
include($cfgProgDir . "secure.php");







include("../script/conex_doc.php");
include("head.php");

include("../script/perfiles.php");



$elegido1 = explode(",", $_POST["elegido"]);
$elegido = $elegido1[0];
$elegido_n = $elegido1[1];


$sql = "SELECT * FROM LISTA WHERE id_rel=".$elegido."";
$resultado = mysql_query($sql, $con_doc);
$row_cnt = mysql_num_rows($resultado);
$datos = "";
while ($data = mysql_fetch_array($resultado)) {    
    $datos = $data['datos'];
}

$area = $_POST["area"];

?>



<div class="form-group" id="descripcion">
    <textarea class="textarea" placeholder="Texto aqui" id="detalle" name="detalle"></textarea>
</div>



<section class="content" id="contacto">
    <div class="row">

        <div class="col-sm-6">
            <div class=" form-group">
                <div class="select2-blue">
                    <select class="select2 form-control-sm" multiple="multiple" data-placeholder="RESPONSABLE/S DEL REQUERIMIENTO ..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="responsable" name=responsable[] required>
                        <?php
                        $sql = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USUARIOS_ACTIVOS ORDER BY DESCRIPTION";
                        $resultado1 = mysql_query($sql, $con_doc);
                        $row_cnt = mysql_num_rows($resultado1);
                        while ($data = mysql_fetch_array($resultado1)) {
                            echo "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
                        }
                        
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-sm-6" <?php echo $asignar_a; ?>>
            <div class="form-group">
                <div class="select2-blue">
                    <select class="select2 form-control-sm" multiple="multiple" data-placeholder="ASIGNAR A ..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="asignar_a" name=asignar_a[]>
                        <?php

                        if ($area == "1") {
                            $sql = $sql_desa;
                        } elseif ($area == "2") {
                            $sql = $sql_pbx;
                        } elseif ($area == "3") {
                            $sql = $sql_micro;
                        } elseif ($area == "4") {
                            $sql = $sql_wfo;
                        } elseif ($area == "5") {
                            $sql = $sql_tdi;
                        }

                        $resultado1 = mysql_query($sql, $con_doc);
                        $row_cnt = mysql_num_rows($resultado1);
                        while ($data = mysql_fetch_array($resultado1)) {
                            echo "<option value='" . $data['USER_ID'] . "'>" . $data['DESCRIPTION'] . "</option>";
                        }
                        mysql_close($con_doc); ?>
                    </select>
                </div>
            </div>
            <!-- /.form-group -->
        </div>

    </div>
</section>



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


<button class="btn btn-block btn-primary btn-xs" id="boton">SOLICITAR</button>

<?php include("script.php"); ?>

<script>
    $(function() {
        var HTMLstring = '<div><p>Hello, world</p><p>Summernote can insert HTML string</p></div>';
        //$('.textarea').summernote('pasteHTML', HTMLstring);
        // Summernote
        $('.textarea').summernote(
            
            {
            //lang: 'fr-FR',       
            placeholder: 'ESCRIBE AQUI EL DETALLE <br> <smal>(PARA PEGAR LA PLANTILLA DE LOS CAMPOS REQUERIDOS MOSTRADOS EN LA VENTANA EMERGENTE PRESIONAR CTRL+V)</small>',     
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

<script language="javascript">
    $(document).ready(function() {

        $('#adjunto').bind('change', function() {

            var fileName = this.files[0].name;
            var ext = fileName.split('.');
            // ahora obtenemos el ultimo valor despues el punto
            // obtenemos el length por si el archivo lleva nombre con mas de 2 puntos
            ext = ext[ext.length - 1];

            if (ext != 'doc' && ext != 'docx' && ext != 'xls' && ext != 'xlsx' && ext != 'ppt' && ext != 'pptx' && ext != 'zip' && ext != 'rar' && ext != 'wav') {
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