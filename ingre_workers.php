<!-- ANGELO POLGROSSI | 04124856320 -->

<?php
        session_start();
   
        if( isset( $_COOKIE['user']) == true)
        {

            header("Location: login_workers.php");
            
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
                    <p class='h1 text-light'>Marca tu asistencia</p>
            <form action="login_workers.php" method="post">
                
                
                <br>
                <div class="form-floating mb-3">
        
                <input name="user" type="text" class="form-control" placeholder="name@example.com" required>
                <label for="floatingInput">Usuario</label>
                  </div>
                <br>
                    <input type="submit" class="btn btn-success w-100" value="Ingresar">
            </form>
            <br>
            </div>
        </div>
        </div>
</div>
</body>
</html>