<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
} else {
    header("Location: pageuser.php");
    
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingresar</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    
</head>
<body>
    <div style=" background: url('domo.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>Ingresa al Sistema!</p>
      <form action="login.php" method="post">
        <?php require_once __DIR__ . '/src/bootstrap.php'; echo csrf_input(); ?>
                
                
                <br>
                <div class="form-floating mb-3">
        
                <input name="user" type="text" class="form-control" placeholder="name@example.com" required>
                <label for="floatingInput">Usuario o Email</label>
                  </div>
                  <div class="form-floating">
                <input name="password" type="password" class="form-control" placeholder="Password" required>
                <label for="floatingPassword">Contraseña</label>
                </div>
                <br>
                
                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
                    <input type="submit" class="btn btn-success w-100" value="Ingresar">
            </form>
            <br>
            <p class='text-light'>Olvidaste tus datos de inicio de sesion?,<a href="reccontra.php">Recuperar</a></p>
            <p class='text-light'>Aun no tienes una cuenta?, <a href="regis.php">Registrate</a></p>

            <form action='index.php'>
            <button type="submit" id="buttom" class="btn btn-warning">Salir</button>
            </form>
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