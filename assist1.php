<?php

session_start(); 

    if( isset( $_SESSION['assist']) == false )
        {

                header("Location: ingre_workers.php");
                
                
                        exit();
            }

session_destroy();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultado</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div
    style="
      background: url('domo.jpg') no-repeat center center fixed;
      background-size: cover;
    ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">

<?php

require './conn.php'; $conn = DataConnect();

date_default_timezone_set('America/Caracas');

$fecha = date('Y-m-d');

$fecha_entrada = date('Y-m-d H:i:s');

$consultusers = "SELECT * FROM ASSIST where email = '$_COOKIE[user]' and fecha = '$fecha'";

$stmtusers = $conn->prepare($consultusers);
$stmtusers->execute();
$rowtotals= $stmtusers->fetchAll(PDO::FETCH_ASSOC);
$stmtusers->closeCursor();


if (isset($rowtotals)) {

    $consultcreate= "INSERT INTO ASSIST (email, fecha_entrada, fecha)
    VALUES ('$_COOKIE[user]', '$fecha_entrada', '$fecha' );";

    $stmtcreate = $conn->prepare($consultcreate);
    $stmtcreate->execute();

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
            Su asistencia de entrada ha sido registrada de forma exitosa!
            </div>";
        echo "</form>
            <br>
            <form action='ingre_workers.php'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
    }

}

else {

    echo "<div class='alert alert-danger' role='alert'>
            Ya ha marcado su asistencia de entrada anteriormente...
            </div>";
        echo "<br>
            <form action='ingre_workers.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>

            </form>";

}
?>

</div>
</div>
</div>
</div>
</body>
</html>