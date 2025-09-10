<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrarse</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>
    <div style=" background: url('salonestudiosjuridicos.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>Crea tu cuenta!</p>
            <form action="regis2.php" method="post">
                <?php require_once __DIR__ . '/src/bootstrap.php'; echo csrf_input(); ?>
                
                
                <br>
                <div class="input-group input-group-sm mb-3">
                <span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
                <input name="correo" type="email" class="form-control" placeholder="Nombre@ejemplo.com" aria-describedby="inputGroup-sizing-sm" required>
                
                <input name="correo2" type="email" class="form-control" placeholder="Confirmar" aria-describedby="inputGroup-sizing-sm" required>
                    
                  </div>
    
                  <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="inputGroup-sizing-sm">Contraseña</span>
                <input name="contra" type="password" class="form-control" placeholder="Minimo 8 caracteres" aria-describedby="inputGroup-sizing-sm" pattern=".{8,}" required>
                
                
                <input name="contra2" type="password" class="form-control" placeholder="Confirmar" aria-describedby="inputGroup-sizing-sm" pattern=".{8,}" required>
                    
                </div>
                <p class='text-light'>Evite usar puntos "." o guiones "-" para separar los numeros</p>
                <div class="form-floating mb-3"> 
            
                    <input type="number"  name="ci" max="99999999" class="form-control" placeholder="name@example.com" required> 
                    <label for="floatingInput">Cedula</label>
        </div>
        <div class="form-floating mb-3"> 
            
            <input type="number"  name="ip" max="999999" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Inpre</label>
</div>
<br>
                    <input type="submit" class="btn btn-success w-100" value="Registrarse">
                    
                    
            </form>
            <br>
            <p class='text-light'>Ya tienes una cuenta?, <a href="ingre.php">ingresar</a></p>
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