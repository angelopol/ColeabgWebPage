<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
    
    header("Location: index.php");
    
    exit();
}

if (empty($_SESSION["datedb"])) {
    
  header("Location: datedb.php");
  
  exit();
}

unset( $_SESSION["datedb"] );

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1600)) {
    
    session_unset();     
    session_destroy();   
    header("Location: index.php");
    
    exit();

}
$_SESSION['LAST_ACTIVITY'] = time(); 


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Datos de Registro</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <div style=" background: url('canchabeisbol.jpg') no-repeat center center fixed;
      background-size: cover; ">

<nav class="navbar fixed-top" style="background-color: rgba(255,255,255,0);">
  <div class="container-fluid">
  <a class="navbar-brand"></a>
  
    <button class="navbar-toggler" type="button" style="background-color:#FFFFFF;" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="pageuser.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="recpass.php">Cambiar contraseña</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="recemail.php">Cambiar email</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="delete.php">Eliminar usuario</a>
          </li>
      </div>
    </div>
        </ul>
  </div>
</nav>
      
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>Actualiza tus datos de registro</p>
                    <p class='text-light'>Evite usar puntos "." o guiones "-" para separar los numeros</p>
            <form action="datedb3.php" method="post">
            <br>
            
                <?php

$serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
  require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

    $consultusers = "SELECT * FROM USUARIOS where email like '%$_SESSION[nameuser]%'";

                $stmtusers = sqlsrv_query( $conn, $consultusers);
                $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);

                $consultclie= "SELECT * FROM SACLIE where CodClie like '%$rowclieusers[CodClie]%'";

    $stmtclie = sqlsrv_query( $conn, $consultclie);
    $rowclie = sqlsrv_fetch_array( $stmtclie, SQLSRV_FETCH_ASSOC);

                echo"
                
            
                <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Cedula</p>
                </label>
                <input name='ci' type='number' max='99999999' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[CodClie]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Inpre</p>
                </label>
                <input name='ip' type='number' max='999999' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Clase]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Nombres</p>
                </label>
                <input name='nombre' type='text' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Descrip]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Rif</p>
                </label>
                <input name='rif' type='number' max='999999999' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[ID3]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Direccion</p>
                </label>
                <input name='direc' type='text' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Direc1]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <input name='direc2' type='text' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Direc2]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Telefonos</p>
                </label>
                <input name='telef' type='number' min = '999999999' max='9999999999' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Telef]'
                 required>                    
        </div>
        <div class='mb-3'> 
                
                <input name='telef2' type='number' min = '999999999' max='9999999999' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Movil]'
                 required>                    
        </div>
        <div class='mb-3'> 
                <label for='exampleFormControlInput1' class='form-label'><p class='h5 text-light'>Correo Electronico</p>
                </label>
                <input name='email' type='email' class='form-control' id='exampleFormControlInput1'
                 placeholder='$rowclie[Email]'
                 required>                    
        </div>
"?>
<br>
<div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="inputGroup-sizing-sm">Contraseña</span>
                <input name="contra" type="password" class="form-control" aria-describedby="inputGroup-sizing-sm" pattern=".{8,}" required>
                
                
                <input name="contra2" type="password" class="form-control" placeholder="Confirmar" aria-describedby="inputGroup-sizing-sm" pattern=".{8,}" required>
                    
                </div>  
<br>
                    <input type="submit" class="btn btn-success w-100" value="Actualizar">
                    
            
            </form>
            <br>
            <form action='index.php'>
              <div class='btn-group'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
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