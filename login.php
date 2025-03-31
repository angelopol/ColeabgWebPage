<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Datos incorrectos</title>
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

$userjoined = $_POST["user"];
$passjoined = $_POST["password"];

if ($userjoined == "" or $passjoined == "") {
    
    header("Location: index.php");
    
    exit();

} else {

    $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

    $consultusers = "SELECT * FROM USUARIOS where email = '$userjoined'";
    $consultpass = "SELECT * FROM USUARIOS where pass = '$passjoined'";

    $stmtusers = sqlsrv_query( $conn, $consultusers);
    $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
    $nullornotusers = is_null($rowclieusers);

    $stmtpass = sqlsrv_query( $conn, $consultpass);
    $rowcliepass = sqlsrv_fetch_array( $stmtpass, SQLSRV_FETCH_ASSOC);
    $nullornotpass = is_null($rowcliepass);

    if ($nullornotpass == false && $nullornotusers == false) {

        session_start();
    
        $_SESSION["nameuser"] = $userjoined;

        header("Location: pageuser.php");

    } else { 
        
        
        echo "<div class='alert alert-danger' role='alert'>
        Datos ingresados de forma incorrecta!
    </div>";
        echo        
                "<br>
                <form action='ingre.php'>
                <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                </form>
                <br>
                <form action='index.php'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
    }

}

?>

</div>
        </div>
        </div>
        <div class="sticky-bottom">
                <a class="img-fluid" href="soport.php">
                <img src="contact2.png" alt="Soporte" width="100" height="100">
              </a>
                </div>
</div>
    </body>
</html>