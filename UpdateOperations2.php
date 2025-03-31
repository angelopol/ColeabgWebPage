<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Results of process</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div
    style="
      background: url('piscina.jpg') no-repeat center center fixed;
      background-size: cover;
    ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">

<?php

$ci = $_REQUEST["ci"];
$hasta = $_REQUEST["hasta"];
$FechaE = $_REQUEST["FechaE"];
$NumeroD = $_REQUEST["NumeroD"];

if ($ci == "" || $hasta == "") {
    echo "<div class='alert alert-danger' role='alert'>
                Es necesario que rellene todos los campos. 
                </div>";
            echo "<br>
                <form action='solv.php'>
                <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>

                </form>";
}
else {

    $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

    $consultusers = "SELECT * FROM SOLV where CodClie = '$ci' and Status = 1";

    $stmtusers = sqlsrv_query( $conn, $consultusers);
    $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
    $nullornotusers = is_null($rowclieusers);

    if ($nullornotusers == true) {

        echo "<div class='alert alert-danger' role='alert'>
            Ha ocurrido un error: no existe ningun registro con esos datos, por lo tanto no es posile completar el proceso de actualizacion, por favor intente de nuevo.
            </div>";
        echo "<br>
            <form action='solv.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>";

    } else {

        while ($row = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC)) 
        {
            $DbFechaE = $row['FechaE'];
            $DbNumeroD = $row['NumeroD'];
        }

        if ($DbFechaE > $FinallyFechaE) {

            echo "<div class='alert alert-danger' role='alert'>
                Ha ocurrido un error: la fecha de facturacion de la factura ingresada es inferior a la fecha de facturacion de la 
                ultima factura registrada, por favor intente de nuevo.
                </div>";
            echo "<br>
                <form action='solv.php'>
                <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                </form>";

        } else 
        {
            if ($DbNumeroD == $NumeroD) 
            {
                echo "<div class='alert alert-danger' role='alert'>
                    Ha ocurrido un error: la fecha de facturacion de la factura ingresada es inferior a la fecha de facturacion de la 
                    ultima factura registrada, por favor intente de nuevo.
                    </div>";
                echo "<br>
                    <form action='solv.php'>
                    <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                    </form>";
        } else {
            
            $consultcreate= "UPDATE ASSIST set fecha_salida = '$fecha_salida' where email = '$_COOKIE[user]' and fecha = '$fecha'";

            $stmtcreate = sqlsrv_query( $conn, $consultcreate);

            if ($stmtcreate == false) {
                echo "<div class='alert alert-danger' role='alert'>
                    Ha ocurrido un error, por favor intente de nuevo!
                    </div>";
                echo "<br>
                    <form action='ingre_workers.php'>
                    <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>

                    </form>";

            } else {

                echo "<div class='alert alert-success' role='alert'>
                    Su asistencia de salida ha sido registrada de forma exitosa!
                    </div>";
                echo "</form>
                    <br>
                    <form action='ingre_workers.php'>
                    <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
                    </form>";
            }
        }
    }

    }
}
?>

</div>
</div>
</div>
</div>
</body>
</html>