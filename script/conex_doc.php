<?php

$con_doc=mysql_connect("150.1.88.224","usccentera","alcc2378001");
mysql_set_charset('utf8', $con_doc);
	if(!$con_doc)
        echo"ERROR_CONEXION_BD";

        $consul_doc = mysql_select_db("bdd_doc");
        if (!$consul_doc)
            echo "ERROR_CONSULTA_BD";

?>