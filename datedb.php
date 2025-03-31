<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
    
    header("Location: index.php");
    
    exit();
}

$_SESSION["datedb"] = $_SESSION["nameuser"];

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
                    <p class='h1 text-light'>Datos de registro</p>
                    <p class='h5 text-light'>Estos son los datos registrados al momento de la inscripcion
                        en el Colegio de Abogados del Estado Carabobo
                    </p>
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

            echo "<div class='accordion' id='accordionExample'>
            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingOne'>
                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOne'
                 aria-expanded='true' aria-controls='collapseOne'>
                 Cedula
                </button>
              </h2>
              <div id='collapseOne' class='accordion-collapse collapse show' aria-labelledby='headingOne'>
                <div class='accordion-bod'>
                $rowclie[CodClie]
                </div>
              </div>
            </div>

            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingTwo'>
                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseTwo'
                 aria-expanded='false' aria-controls='collapseTwo'>
                 Inpre
                </button>
              </h2>
              <div id='collapseTwo' class='accordion-collapse collapse show' aria-labelledby='headingTwo'>
                <div class='accordion-bod'>
                $rowclie[Clase]
                </div>
              </div>
            </div>

            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingThree'>
                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseThree'
                 aria-expanded='false' aria-controls='collapseThree'>
                 Nombres
                </button>
              </h2>
              <div id='collapseThree' class='accordion-collapse collapse show' aria-labelledby='headingThree'>
                <div class='accordion-bod'>
                $rowclie[Descrip]
                </div>
              </div>
            </div>
          
            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingFour'>
                <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseFour'
                 aria-expanded='false' aria-controls='collapseFour'>
                 Rif
                </button>
              </h2>
              <div id='collapseFour' class='accordion-collapse collapse show' aria-labelledby='headingFour'>
                <div class='accordion-bod'>
                $rowclie[ID3]
                </div>
              </div>
            </div>
          
            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingFive'>
                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseFive'
                 aria-expanded='false' aria-controls='collapseFive'>
                 Direccion
                </button>
              </h2>
              <div id='collapseFive' class='accordion-collapse collapse' aria-labelledby='headingFive'>
                <div class='accordion-bod'>
                $rowclie[Direc1] <br> $rowclie[Direc2]
                </div>
              </div>
            </div>

            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingSix'>
                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseSix'
                 aria-expanded='false' aria-controls='collapseSix'>
                 Telefonos
                </button>
              </h2>
              <div id='collapseSix' class='accordion-collapse collapse' aria-labelledby='headingSix'>
                <div class='accordion-bod'>
                $rowclie[Telef] <br> $rowclie[Movil]
                </div>
              </div>
            </div>

            <div class='accordion-item'>
              <h2 class='accordion-header' id='headingSeven'>
                <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseSeven'
                 aria-expanded='false' aria-controls='collapseSeven'>
                 Correo Electronico
                </button>
              </h2>
              <div id='collapseSeven' class='accordion-collapse collapse' aria-labelledby='headingSeven'>
                <div class='accordion-bod'>
                $rowclie[Email]
                </div>
              </div>
            </div>
            </div>";
                    
            ?>
            
            <br>
            <a href='datedb2.php' class='btn btn-primary' aria-current='page'>¿Desea cambiar sus datos?</a>
            <br>
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