<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
} else {
    header("Location: pageuser.php");
    
    exit();
} 

$_SESSION["reccontra"] = 1;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar Contraseña</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">


</head>
<body>
    <div style=" background: url('piscina.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>Ingresa tus datos de usuario</p>
            <form action="reccontra2.php" method="post">
                
                
                <br>
                <div class="form-floating mb-3">                
                <input type="email"  name="correo" class="form-control" placeholder="name@example.com" required> 
                    <label for="floatingInput">Email</label>                    
                  </div>
                  <p class='text-light'>No recuerdas tu email?, contacta
                con el<a href="soport.php"> Soporte</a></p>
                
                <div class="form-floating mb-3"> 
            
                    <input type="number"  name="ci" max="99999999" class="form-control" placeholder="name@example.com" required> 
                    <label for="floatingInput">Cedula</label>
        </div>
        <div class="form-floating mb-3"> 
            
            <input type="number"  name="ip" max="999999" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Inpre</label>
</div>
<br>
                    <input type="submit" class="btn btn-success w-100" value="Recuperar">
                    
                    
            </form>
            <br>
            
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