<!-- ANGELO POLGROSSI | 04124856320 -->

<?php

    if ($_POST["user"] == ""){

        header("Location: ingre_workers.php");
    }
?>

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

if( isset( $_COOKIE['user']) == true){

    $userjoined = $_COOKIE['user'];
}   

else {

    $userjoined = $_POST["user"];
}

require './conn.php'; $conn = DataConnect();
$consultusers = "SELECT * FROM USUARIOS where email = '$userjoined' and pass IS NULL and Clase IS NULL and CodClie IS NULL";

$stmtusers = $conn->prepare($consultusers);
$stmtusers->execute();
$rowtotals= $stmtusers->fetchAll(PDO::FETCH_ASSOC);
$stmtusers->closeCursor();

if (isset($rowtotals)) {

    setcookie('user',"", time() - (3600 * 24 * 24));

    setcookie('user',$userjoined, time() + (3600 * 24 * 24));

    header("Location: workers.php");

    exit();

} else { 
    
    
    echo "<div class='alert alert-danger' role='alert'>
    Datos ingresados de forma incorrecta!
  </div>";
    echo        
            "<br>
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