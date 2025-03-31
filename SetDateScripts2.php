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

$ScriptName = $_REQUEST["ScriptName"];
$date = $_REQUEST["date"];
$days = $_REQUEST["days"];

if ($date == "" || $ScriptName == "") {
    echo "<div class='alert alert-danger' role='alert'>
                Es necesario que rellene todos los campos. 
                </div>";
            echo "<br>
                <form action='SetDateScripts.php'>
                <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>

                </form>";
}
else {

    $serverName = "sql5111.site4now.net"; 
    $connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
    require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

        $consultusers = "SELECT * FROM DateScripts where ScriptName = '$ScriptName'";

        $stmtusers = sqlsrv_query( $conn, $consultusers);
        $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
        $nullornotusers = is_null($rowclieusers);

        if ($nullornotusers == false) {

            $consultusers = "UPDATE DateScripts SET date = '$date' WHERE ScriptName = '$ScriptName';UPDATE DateScripts SET days = '$days' WHERE ScriptName = '$ScriptName'";
            
            $stmtusers = sqlsrv_query( $conn, $consultusers);

                if ($stmtusers == false) {
                    echo "<div class='alert alert-danger' role='alert'>
                        Ha ocurrido un error, por favor intente de nuevo.
                        </div>";
                    echo "<br>
                        <form action='SetDateScripts.php'>
                        <button type='submit' id='buttom' class='btn btn-primary'>Intentar de nuevo</button>
                        </form>";
                } else {
                    echo "<div class='alert alert-success' role='alert'>
                    Date of: $ScriptName, '$date', successfuly updated!
                        </div>";
                    echo "<br>
                        <form action='SetDateScripts.php'>
                        <button type='submit' id='buttom' class='btn btn-primary'>Continuar</button>
                        </form>";
                }

        } else {


            $consultusers = "INSERT INTO DateScripts (ScriptName, date, days)
            VALUES ('$ScriptName', '$date', '$days')";
            
            $stmtusers = sqlsrv_query( $conn, $consultusers);

                if ($stmtusers == false) {
                    echo "<div class='alert alert-danger' role='alert'>
                        Ha ocurrido un error, por favor intente de nuevo.
                        </div>";
                    echo "<br>
                        <form action='SetDateScripts.php'>
                        <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                        </form>";
                } else {
                    echo "<div class='alert alert-success' role='alert'>
                        Date of: $ScriptName, '$date', successfuly stored!
                        </div>";
                    echo "<br>
                        <form action='SetDateScripts.php'>
                        <button type='submit' id='buttom' class='btn btn-primary'>Continuar</button>
                        </form>";
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