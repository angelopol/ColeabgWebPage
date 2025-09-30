<?php
require_once __DIR__ . '/load_env.php';
session_start();

if (!isset($_SESSION['assist'])) {
    header('Location: ingre_workers.php');
    exit();
}

require './conn.php';
$conn = DataConnect();
date_default_timezone_set('America/Caracas');

$fecha = date('Y-m-d');
$fecha_salida = date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Resultado</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div style="background: url('domo.jpg') no-repeat center center fixed; background-size: cover;">
        <div class="container">
            <div class="row min-vh-100 justify-content-center align-items-center">
                <div class="col-auto p-5">

<?php
// Check if there is an entry for today for this user
$consultusers = 'SELECT COUNT(1) FROM ASSIST WHERE email = ? AND fecha = ?';
$stmt = $conn->prepare($consultusers);
$stmt->bindValue(1, $_COOKIE['user'], PDO::PARAM_STR);
$stmt->bindValue(2, $fecha, PDO::PARAM_STR);
$stmt->execute();
$exists = (bool)$stmt->fetchColumn();
$stmt->closeCursor();

if ($exists) {
    $consultcreate = 'UPDATE ASSIST SET fecha_salida = ? WHERE email = ? AND fecha = ?';
    $stmtcreate = $conn->prepare($consultcreate);
    $stmtcreate->bindValue(1, $fecha_salida, PDO::PARAM_STR);
    $stmtcreate->bindValue(2, $_COOKIE['user'], PDO::PARAM_STR);
    $stmtcreate->bindValue(3, $fecha, PDO::PARAM_STR);
    $ok = $stmtcreate->execute();
    if (!$ok) {
        echo "<div class='alert alert-danger' role='alert'>Ha ocurrido un error, por favor intente de nuevo!</div>";
        echo "<br><form action='ingre_workers.php'><button type='submit' class='btn btn-primary'>Intente de nuevo</button></form>";
    } else {
        echo "<div class='alert alert-success' role='alert'>Su asistencia de salida ha sido registrada de forma exitosa!</div>";
        echo "<br><form action='ingre_workers.php'><button type='submit' class='btn btn-warning'>Salir</button></form>";
    }
} else {
    echo "<div class='alert alert-danger' role='alert'>Debe marcar primero la asistencia de entrada...</div>";
    echo "<br><form action='ingre_workers.php'><button type='submit' class='btn btn-primary'>Intente de nuevo</button></form>";
}

?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>