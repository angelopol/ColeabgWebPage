<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["nameuser"])) {
    
    header("Location: index.php");
    
    exit();
}

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
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inicio</title> 
        <link rel="shortcut icon" href="favicon.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    </head>

    <body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <div
    style="
      background: url('salonestudiosjuridicos.jpg') no-repeat center center fixed;
      background-size: cover;
    ">
    <nav class="navbar fixed-top" style="background-color: rgba(255,255,255,0);">
  <div class="container-fluid">
  <a class="navbar-brand">
  <small class='text-light fs-6'><?php 

$serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
  require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

  $user = $_SESSION["nameuser"];
  $consultusers = "SELECT * FROM USUARIOS where email like '%$user%'";
  $stmtusers = sqlsrv_query( $conn, $consultusers);
  $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
  $consult2= "SELECT * FROM SACLIE where CodClie like '%$rowclieusers[CodClie]%'";

  $stmt2 = sqlsrv_query( $conn, $consult2);
  $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);
  
  echo "$row2[Descrip]";
  ?>
  </small>
  </a>
  
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
            <a class="nav-link" aria-current="page" href="recpass.php">Cambiar contraseña</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="recemail.php">Cambiar email</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" href="delete.php">Eliminar usuario</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="datedb.php">Datos de registro en el Colegio de Abogados</a>
          </li>
         

      </div>
    </div>
        </ul>
  </div>
</nav>


            <div class="container">
            
                <div class="row min-vh-100 justify-content-center align-items-center">
                
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>¡Bienvenido!, ya puedes visualizar tus operaciones...</p>
                    <br>
                    <br>
                    
        <?php
        $user = $_SESSION["nameuser"];
        

        $serverName = "sql5073.site4now.net"; 
        $connectionInfo = array( "Database"=>"db_a9e04b_coleabg", "UID"=>"db_a9e04b_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
        require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

        $consultusers = "SELECT * FROM USUARIOS where email like '%$user%'";
        $stmtusers = sqlsrv_query( $conn, $consultusers);
        $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);

        $consult= "SELECT * FROM SAFACT where CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2023%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2023%'
                ORDER BY FechaE;";

            $stmt3 = sqlsrv_query( $conn, $consult);
            $row3 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_ASSOC);

            $consult2= "SELECT * FROM SACLIE where CodClie like '%$rowclieusers[CodClie]%'";

            $stmt2 = sqlsrv_query( $conn, $consult2);
            $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC);

            $nullornot3 = is_null($row3);

            $stmtcodclie = sqlsrv_query( $conn, $consult);

            if ($nullornot3 == false)
            {
                echo " <p class='h2 text-light'>Operaciones recientes: </p><br>";
                while( $row = sqlsrv_fetch_array( $stmtcodclie, SQLSRV_FETCH_ASSOC) ) {
                echo "<p class='text-light'>" .$row['OrdenC']. " ". $row['Notas1']. " " 
                . $row['Notas2'] . " " . $row['Notas3'] . " " . $row['Notas4'] . " " . $row['Notas5']
                . $row['Notas6'] . " " . $row['Notas7'] . "</p>";
                }
            } else {
                echo " <p class='text-light'>Sin operaciones recientes...</p>";
            }

            $consultnew= "SELECT * FROM SAFACT where CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2021%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2021%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2022%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2022%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2020%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2020%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2019%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2019%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2018%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2018%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2017%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2017%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2016%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2016%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2015%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2015%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2014%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2014%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2013%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2013%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2012%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2012%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2011%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2011%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2010%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2010%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2009%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2009%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2008%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2008%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2007%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2007%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2006%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2006%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2005%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2005%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2004%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2004%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2003%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2003%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2002%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2002%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2001%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2001%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2000%' OR ID3 like '%$rowclieusers[CodClie]%' and FechaE like '%2000%'
                ORDER BY FechaE;";

                $consultnew2= "SELECT * FROM SAACXC where CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2022%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2021%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2020%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2019%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2018%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2017%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2016%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2015%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2014%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2013%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2012%'
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2011%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2010%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2009%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2008%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2007%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2006%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2005%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2004%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2003%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2002%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2001%' 
                OR CodClie like '%$rowclieusers[CodClie]%' and FechaE like '%2000%' 
                ORDER BY FechaE;";

            $stmtnew = sqlsrv_query( $conn, $consultnew);
            $rownew = sqlsrv_fetch_array( $stmtnew, SQLSRV_FETCH_ASSOC);

            $nullornotnew = is_null($rownew);

            $stmtnew2 = sqlsrv_query( $conn, $consultnew2);
            $rownew2 = sqlsrv_fetch_array( $stmtnew2, SQLSRV_FETCH_ASSOC);

            $nullornotnew2 = is_null($rownew2);

            if ($nullornotnew == true) {
              if ($nullornotnew2 == false) {

                echo "<br>
              <div>
  <a href='all.php?ci=$rowclieusers[CodClie]&ip=2' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
</div>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
        </form>";
                } else {
            echo "<br>
            <div class='btn-group'>
              <form action='index.php'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
        </form>";
                }
            } else {

              
              echo "<br>
              <div>
  <a href='all.php?ci=$rowclieusers[CodClie]&ip=2' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
</div>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
        </form>";
                
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