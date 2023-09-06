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

if ($elegido==1 || $elegido==2){$sql = "SELECT * FROM LISTA WHERE id_rel=".$elegido." ORDER BY opciones";}else{$sql = "SELECT * FROM LISTA WHERE id_rel=".$elegido."";}
$resultado = mysql_query($sql, $con_doc);
$row_cnt = mysql_num_rows($resultado);
$lista = "";
while ($data = mysql_fetch_array($resultado)) {
    if ($data['sms']=="SI") {$colorsms= "style='color:#B20000'";} else {$colorsms="";}
    $lista .= "<option ".$colorsms." value='" . $data['id'] . ",".$data['opciones']." ".$data['url'].",".$data['sms'].",".$data['plantilla']."'>".$data['opciones']."&nbsp;&nbsp;&nbsp;&nbsp;" . $data['url'] . "</option>";
}

$area = $_POST["area"];

$responsables ="";
$sql = "SELECT DESCRIPTION, USER_ID FROM APPLPORDB.USUARIOS_ACTIVOS ORDER BY DESCRIPTION";
$resultado1 = mysql_query($sql, $con_doc);
$row_cnt = mysql_num_rows($resultado1);
while ($data = mysql_fetch_array($resultado1)) {
    $responsables .= "<option value=" . $data['USER_ID'] . ">" . $data['DESCRIPTION'] . "</option>";
}


$asignar_select="";
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
    $asignar_select .=  "<option value=" . $data['USER_ID'] . ">" . $data['DESCRIPTION'] . "</option>";
}

//echo $sql; exit;

mysql_close($con_doc);



$html = "";
if ($_POST["elegido"] == 3 || $_POST["elegido"] == 6 || $_POST["elegido"] == 9 || $_POST["elegido"] == 12 || $_POST["elegido"] == 13) {
    $html = '
    <section class="content">
    <div class="row">

    <div class="col-sm-6">
        <div class="form-group">      
            <input type="text" class="form-control float-right form-control-sm" id="titulo" name="titulo" placeholder="TITULO" maxlength="50" required>       
        </div>
    </div>

    </div>
    </section>
    <br>
	';
}

else  {
    $html = '
    <section class="content">
        <div class="row">

            <div class="col-sm-6">
                <div class="form-group">      
                    <select class="form-control form-control-sm" id="titulo" name="titulo" onchange="val()" required>
                    <option selected disabled value="">Opciones</option>
                    '.$lista.'
                    </select>     
                    
                    
                </div>
            </div>
	';
}

$html_b = '

            <div class="col-sm-12">
            <div class="form-group" id="descripcion">
                <textarea class="textarea" placeholder="Texto aqui" id="detalle" name="detalle"></textarea>
            </div>
            </div>





                    <div class="col-sm-6">
                        <div class=" form-group">
                            <div class="select2-blue">
                            <label>Responsables</label>
                                <select class="select2 form-control-sm" multiple="multiple" data-placeholder="RESPONSABLE/S DEL REQUERIMIENTO ..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="responsable" name=responsable[] required>
                                    '.$responsables.'
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6" '.$asignar_a.'>
                        <div class="form-group">
                            <div class="select2-blue">
                            <label>Asignar a:</label>
                                <select class="select2 form-control-sm" multiple="multiple" data-placeholder="ASIGNAR A ..." data-dropdown-css-class="select2-blue" style="width: 100%;" id="asignar_a" name=asignar_a[]>
                                    '.$asignar_select.'
                                </select>
                            </div>
                        </div>
                        <!-- /.form-group -->
                    </div>

                </div>




                <div class="col-sm-12" id="archivo">
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


            </div>
            </section> 

            <script>
            $(function() {
            //Initialize Select2 Elements
            $(".select2").select2()


            //Initialize Select2 Elements
            $(".select2bs4").select2({
            theme: "bootstrap4"
            })



            //Bootstrap Duallistbox
            $(".duallistbox").bootstrapDualListbox()

            //Colorpicker
            $(".my-colorpicker1").colorpicker()
            //color picker with addon
            $(".my-colorpicker2").colorpicker()

            $(".my-colorpicker2").on("colorpickerChange", function(event) {
            $(".my-colorpicker2 .fa-square").css("color", event.color.toString());
            });

            $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch("state", $(this).prop("checked"));
            });

            $(document).ready(function() {
            bsCustomFileInput.init();
            });

            $(".select2").select2("val", ["'.$dpilogin.'"])

            })
            </script>

            <script>


            function val() {
            $(".textarea"). summernote("reset");
            let text = document.getElementById("titulo").value;
            const myArray = text.split(",");
            let word = myArray[3];
            if (myArray[3]!=""){

            $(".textarea").summernote("pasteHTML", word);

            alert("LLENAR LOS SIGUIENTE CAMPOS PARA LA SOLICITUD REQUERIDA");

            // const input = document.createElement("input");
            // input.setAttribute("value", word.replace(/\:/g, ":         "));
            // document.body.appendChild(input);
            // input.select();
            // document.execCommand("copy");
            // document.body.removeChild(input);

            // document.getElementById("detalle").value = word;

            }


            }

            </script>


            <script>
            $(function() {

            //var HTMLstring = "<p>Hello, world</p><p>Summernote can insert HTML string</p>";
            //$(".textarea").summernote("pasteHTML", HTMLstring);
            // Summernote
            $(".textarea").summernote(

            {
            //lang: "fr-FR",       
            placeholder: "ESCRIBE AQUI EL DETALLE DE TU SOLICITUD...",     
            height: 400,
            toolbar: [
                ["style", ["style", "bold", "italic", "underline", "clear"]],
                ["style", ["bold", "italic", "underline"]],
                ["font", ["fontsize", "fontname", "strikethrough", "underline"]],
                ["font", ["strikethrough", "underline"]],
                ["color", ["color"]],
                ["para", ["ul", "ol", "paragraph"]],
                ["para", ["ul", "ol"]],
                ["link", ["link"]],
                ["table", ["table"]],
                ["picture", ["picture"]],
                ["view", ["codeview"]]
            ],
            onImageUpload: function(files, editor, welEditable) {
                for (var i = files.lenght - 1; i >= 0; i--) {
                    sendFile(files[i], this);
                }
            }
            });
            $(".note-editor .note-editable").css("line-height", 1);
            })
            </script>

            <script language="javascript">
            $(document).ready(function() {

            $("#adjunto").bind("change", function() {

            var fileName = this.files[0].name;
            var ext = fileName.split(".");
            // ahora obtenemos el ultimo valor despues el punto
            // obtenemos el length por si el archivo lleva nombre con mas de 2 puntos
            ext = ext[ext.length - 1];

            if (ext != "doc" && ext != "docx" && ext != "xls" && ext != "xlsx" && ext != "ppt" && ext != "pptx" && ext != "zip" && ext != "rar" && ext != "wav") {
                alert("El archivo no tiene la extensión adecuada");
                this.value = ""; // reset del valor
                this.files[0].name = "";
            }


            if (this.files[0].size > 50000000) {
                alert("EL ARCHIVO ADJUNTO NO DEBE SER MAYOR A 50 MB");
                $("#adjunto").val("");
            }

            });

            });
            </script>

';


echo $html.$html_b;



