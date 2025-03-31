<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

?>
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verifica las operaciones</title> 
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
               
            $CI = $_GET['ci'];
            $IP = $_GET['ip'];

            if ($CI == ""){
              header("Location: index.php");
    
              exit();
            }
            else {
            
            $consulttotal = "SELECT * FROM SAFACT where CodClie like '%$CI%' OR ID3 like '%$CI%' 
            ORDER BY FechaE;";

            
            require './conn.php'; $conn = DataConnect();

            $stmttotal = $conn->prepare($consulttotal);
            $stmttotal->execute();
            $rowtotals= $stmttotal->fetchAll(PDO::FETCH_ASSOC);
            $stmttotal->closeCursor();

                echo " <p class='h2 text-light'>Operaciones: </p><br>";
                foreach($rowtotals as $rowtotal){
                  echo "<p class='h2 text-light'>" . $rowtotal['FechaE'] . "</p><br>" . "<p class='text-light'>".$rowtotal['NumeroD']. " " .$rowtotal['OrdenC']. " ". $rowtotal['Notas1']. " " 
                  . $rowtotal['Notas2'] . " " . $rowtotal['Notas3'] . " " . $rowtotal['Notas4'] . " " . $rowtotal['Notas5']
                  . $rowtotal['Notas6'] . " " . $rowtotal['Notas7'] . "</p>";
                }
                
                if ($IP == 1) {
                  echo "<br>
                  <p class='text-light'>¿No logra conseguir sus operaciones?, intente buscando en <a href='allmore.php?ci=$CI&ip=1'>Ver mas</a></p>
                  
            <form action='search2.php'>
              <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
            </form>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                } 
                if ($IP == 0) {
                  echo "<br>
                  <p class='text-light'>¿No logra conseguir sus operaciones?, intente buscando en <a href='allmore.php?ci=$CI&ip=0'>Ver mas</a></p>
                  
            <form action='search.php'>
              <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
            </form>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                }

                if ($IP == 2) {
                  echo "<br>
                  <p class='text-light'>¿No logra conseguir sus operaciones?, intente buscando en <a href='allmore.php?ci=$CI&ip=2'>Ver mas</a></p>
                  
            <form action='pageuser.php'>
              <input type='submit' class='btn btn-success w-100' value='Regresar'>
            </form>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                }
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