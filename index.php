<!-- ANGELO POLGROSSI | 04124856320 -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Colegio de Abogados del Estado Carabobo</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  

  </head>
<body>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<div style=" background: url('entrada.jpg') no-repeat center center fixed;
      background-size: cover; ">
      <nav class="navbar navbar-expand-lg fixed-top bg-light">
            <div class="container-fluid">
              
                  
<?php

session_start();

if (empty($_SESSION)) {

  echo "
  <a class=navbar-brand>
  <img src=logo.png alt=logo width=30 height=30>
  </a>
  <div class='btn-group'>
  <a href='search.php' class='btn btn-outline-success active' aria-current='page'>Buscar solvencia</a>
  </div>
  <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav ms-auto'>
      <li class='nav-item'>
        <a class='nav-link' href='ingre.php'>Iniciar sesion</a>
      </li>
      <li class='nav-item'>
        <a class='nav-link' href='regis.php'>Registrarse</a>
      </li>
      </ul>
      </div>";

} else {
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1600)) {
    
    session_unset();     
    session_destroy();   

}
$_SESSION['LAST_ACTIVITY'] = time();

echo "
<a class=navbar-brand>
<img src=logo.png alt=logo width=30 height=30>
</a>
<div class='btn-group'>
<a href='search.php' class='btn btn-outline-success active' aria-current='page'>Buscar solvencia</a>
</div>
<button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
    <span class='navbar-toggler-icon'></span>
  </button>
  <div class='collapse navbar-collapse' id='navbarSupportedContent'>
    <ul class='navbar-nav ms-auto'>
    <li class='nav-item'>
      <a class='nav-link' href='ingre.php'>Ingresar</a>
    </li>
    <li class='nav-item'>
      <a class='nav-link' href='logout.php'>Cerrar sesion</a>
    </li>
    </ul>
    </div>";
}

?>                  
                  </li>
                  </ul>
            </div>
          </nav>
            <div class="container">
                <div class="row vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
                        <h1 class="display-1 text-white text-center"><strong>COLEGIO DE ABOGADOS DEL</strong></h1>
                        <h1 class="display-1 text-white text-center"><strong>ESTADO CARABOBO</strong></h1>
                    </div>
                </div>
                </div>
                <div class="sticky-bottom">
   
                <div class="container-fluid">
                <div class="row">
    <div class="col-md-4">
    <div class="alert alert-light alert-dismissible fade show" role="alert">
    <strong>¿Necesitas ayuda?, presiona el boton de abajo para contactar con el soporte</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
    </div>
                </div>
                </div>
                <a class="img-fluid" href="soport.php">
                <img src="contact2.png" alt="Soporte" width="100" height="100">
              </a>
</div>
                </div>
                
        
        
</body>
</html>