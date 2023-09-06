<?php

$con=mysql_connect("150.1.88.224","usccentera","alcc2378001");
mysql_set_charset('utf8', $con);
	if(!$con)
        echo"ERROR_CONEXION_BD";

        $consul= mysql_select_db("APPLPORDB");
        if (!$consul)
            echo "ERROR_CONSULTA_BD";
            
 
?>