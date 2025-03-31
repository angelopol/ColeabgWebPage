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
$NumeroD = $_REQUEST["NumeroD"];
$CarnetNum = $_REQUEST["CarnetNum"];
$CarnetNum2 = $_REQUEST["CarnetNum2"];

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

    if ($CarnetNum == $CarnetNum2) {

        $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

        $consultusers = "SELECT * FROM SOLV where CodClie = '$ci' and Status = 1";

        $stmtusers = sqlsrv_query( $conn, $consultusers);
        $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
        $nullornotusers = is_null($rowclieusers);

        if ($nullornotusers == true) {

            $consultcarnet = "SELECT * FROM SOLV where CarnetNum2 = '$CarnetNum' and Status = 1";

            $stmtcarnet = sqlsrv_query( $conn, $consultcarnet);
            $rowcliecarnet = sqlsrv_fetch_array( $stmtcarnet, SQLSRV_FETCH_ASSOC);
            $nullornotcarnet = is_null($rowcliecarnet);

            if ($nullornotcarnet == true) {

                $consultusers = "INSERT INTO SOLV (CodClie, hasta, Status, NumeroD, CarnetNum2)
                VALUES ('$ci', '$hasta', 1, '$NumeroD', '$CarnetNum')";
                
                $stmtusers = sqlsrv_query( $conn, $consultusers);

                    if ($stmtusers == false) {
                        echo "<div class='alert alert-danger' role='alert'>
                            Ha ocurrido un error, por favor intente de nuevo.
                            </div>";
                        echo "<br>
                            <form action='solv.php'>
                            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                            </form>";
                    } else {
                        echo "<div class='alert alert-success' role='alert'>
                            Solvencia de CI: $ci, Hasta: $hasta, almacenada correctamente!
                            </div>";
                        echo "<br>
                            <form action='solv.php'>
                            <button type='submit' id='buttom' class='btn btn-primary'>Continuar</button>
                            </form>";
                    }

            } else {
                echo "<div class='alert alert-danger' role='alert'>
                    Ha ocurrido un error: ya existe una persona con ese numero de Carnet.
                    </div>";
                echo "<br>
                    <form action='solv.php'>
                    <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                    </form>"; 
            }

        } else {

                echo "<div class='alert alert-danger' role='alert'>
                        Ha ocurrido un error: ya existe un registro con estos datos, por favor intente de nuevo o <a href='SetCarnetNum.php'>
                        ingrese un numero de carnet</a>.
                        </div>";
                    echo "<br>
                        <form action='solv.php'>
                        <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                        </form>";  

        }

    } else {

        echo "<div class='alert alert-danger' role='alert'>
            El numero de carnet no coincide con su confirmacion.
            </div>";
        echo "<br>
            <h2 class ='text-light text-center'>O</h2>
            <br>
            <form action='solv.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>";

    }
}
?>

</div>
</div>
</div>
</div>
</body>
</html>