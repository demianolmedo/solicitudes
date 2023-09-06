<?php

$con=mysql_connect("HOST","USER","PASS");
mysql_set_charset('utf8', $con);
	if(!$con)
        echo"ERROR_CONEXION_BD";

        $consul= mysql_select_db("APPLPORDB");
        if (!$consul)
            echo "ERROR_CONSULTA_BD";
            
 
?>
