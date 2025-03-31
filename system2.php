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
      background: url('salonestudiosjuridicos.jpg') no-repeat center center fixed;
      background-size: cover;
      ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
            <?php

            $input = $_REQUEST["input"];

            $input2 = $_REQUEST["input2"];

            if ($input == "") {
              header("Location: index.php");
    
              exit();
            } else {
            
              if ($input2 != "") {
                
                $consultclie= "SELECT * FROM SACLIE where Clase = '$input'";
                $consultclie2= "SELECT * FROM SACLIE where Clase = '$input'";
    
                require './conn.php'; $conn = DataConnect();

                $stmtclie = $conn->prepare($consultclie);
                $stmtclie->execute();
                $rowclie = $stmtclie->fetchAll(PDO::FETCH_ASSOC)[0];
                $stmtclie->closeCursor();

                $stmtclie2 = $conn->prepare($consultclie2);
                $stmtclie2->Execute();
                $rowclie2 = $stmtclie2->fetchAll(PDO::FETCH_ASSOC)[0];
                $stmtclie2->closeCursor();
    
                $nullornotclie = is_null($rowclie);
                $nullornotclie2 = is_null($rowclie2);
    
                if ($nullornotclie == false or $nullornotclie2 == false ) {
                  echo " <p class='h1 text-light'>Abogado inscrito!</p><br>";
                  if ($nullornotclie == false && $nullornotclie2 == true) {
                    $consultSOLV = "SELECT * FROM SOLV WHERE CodClie = '$rowclie[CodClie]' and Status = 1";
                    $stmtSOLV = $conn->prepare($consultSOLV);
                    $stmtSOLV->execute();
                    $rowSOLV = $stmtSOLV->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtSOLV->closeCursor();
                    $nullornotSOLV = is_null($rowSOLV);

                    $consultIns = "SELECT * FROM SACLIE_08 WHERE CodClie = '$rowclie[CodClie]'";
                    $stmtIns = $conn->prepare($consultIns);
                    $stmtIns->execute();
                    $rowIns = $stmtIns->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtIns->closeCursor();

                    if ($nullornotSOLV == false) 
                    {
                        echo "<p class='text-light'>Solvente hasta: $rowSOLV[hasta]</p><br>";
                    } else {
                      echo "<p class='text-light'>Solvencia no registrada</p><br>";
                    }
                    echo "<p class='text-light'>CI: $rowclie[CodClie]</p><br>";
                    echo "<p class='text-light'>Inpre: $rowclie[Clase]</p><br>";
                    echo "<p class='text-light'>Fecha de Inscripcion: $rowIns[Fecha]</p><br>";
                    echo "<p class='text-light'>Numero de Inscripcion: $rowIns[Numero]</p><br>";
                    echo "<p class='text-light'>Folio: $rowIns[Folio]</p><br>";
                    echo "<p class='text-light'>$rowclie[Descrip]</p><br>";
                    if ($nullornotSOLV == false) 
                    {
                    echo "<p class='text-light'>CarnetNum: $rowSOLV[CarnetNum2]</p><br>";
                    }
                    else{
                      echo "<p class='text-light'>No posee carnet de seguridad</p><br>";
                    }
                  } else {
                    $consultSOLV = "SELECT * FROM SOLV WHERE CodClie = '$rowclie[CodClie]' and Status = 1";
                    $stmtSOLV = $conn->prepare($consultSOLV);
                    $stmtSOLV->execute();
                    $rowSOLV = $stmtSOLV->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtSOLV->closeCursor();
                    $nullornotSOLV = is_null($rowSOLV);

                    $consultIns = "SELECT * FROM SACLIE_08 WHERE CodClie = '$rowclie[CodClie]'";
                    $stmtIns = $conn->prepare($consultIns);
                    $stmtIns->execute();
                    $rowIns = $stmtIns->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtIns->closeCursor();

                    if ($nullornotSOLV == false) 
                    {
                        echo "<p class='text-light'>Solvente hasta: $rowSOLV[hasta]</p><br>";
                    } else {
                      echo "<p class='text-light'>Solvencia no registrada</p><br>";
                    }
                    echo "<p class='text-light'>CI: $rowclie[CodClie]</p><br>";
                    echo "<p class='text-light'>Inpre: $rowclie[Clase]</p><br>";
                    echo "<p class='text-light'>Fecha de Inscripcion: $rowIns[Fecha]</p><br>";
                    echo "<p class='text-light'>Numero de Inscripcion: $rowIns[Numero]</p><br>";
                    echo "<p class='text-light'>Folio: $rowIns[Folio]</p><br>";
                    echo "<p class='text-light'>$rowclie[Descrip]</p><br>";
                    if ($nullornotSOLV == false) 
                    {
                    echo "<p class='text-light'>CarnetNum: $rowSOLV[CarnetNum2]</p><br>";
                    }
                    else{
                      echo "<p class='text-light'>No posee carnet de seguridad</p><br>";
                    }
                  }

                $consulttotal = "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' OR ID3 like '%$rowclie[ID3]%'";
                $consult= "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' and FechaE like '%$input2%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%$input2%'
                ORDER BY FechaE;";
                $consultnew= "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' and FechaE like '%2021%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2021%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2024%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2024%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2023%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2023%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2022%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2022%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2020%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2020%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2019%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2019%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2018%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2018%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2017%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2017%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2016%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2016%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2015%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2015%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2014%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2014%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2013%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2013%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2012%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2012%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2011%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2011%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2010%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2010%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2009%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2009%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2008%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2008%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2007%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2007%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2006%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2006%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2005%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2005%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2004%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2004%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2003%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2003%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2002%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2002%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2001%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2001%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2000%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2000%'
                ORDER BY FechaE;";

$consultnew2= "SELECT * FROM SAACXC where CodClie like '%$rowclie[CodClie]%' and FechaE like '%2021%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2024%'
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2023%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2022%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2020%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2019%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2018%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2017%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2016%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2015%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2014%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2013%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2012%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2011%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2010%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2009%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2008%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2007%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2006%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2005%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2004%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2003%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2002%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2001%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2000%' 
ORDER BY FechaE;";

$stmtnew2 = $conn->prepare($consultnew2);
                $stmtnew2->execute();
                $rownew2= $stmtnew2->fetchAll(PDO::FETCH_ASSOC)[0];
                $stmtnew2->closeCursor();

$nullornotnew2 = is_null($rownew2);
    
$stmt3 = $conn->prepare($consult);
$stmt3->execute();
$row3 = $stmt3->fetchAll(PDO::FETCH_ASSOC)[0];
$stmt3->closeCursor();

$nullornot3 = is_null($row3);

$stmtcodclie = $conn->prepare($consult);
$stmtcodclie->execute();

if ($nullornot3 == false)
{
  echo " <p class='h2 text-light'>Operaciones en el año $input2: </p><br>";
  while( $row = $stmtcodclie->fetchAll(PDO::FETCH_ASSOC)[0] ) {
    echo "<p class='text-light'>" .$row['FechaE'] . " " .$row['NumeroD']. " " .$row['OrdenC']. " ". $row['Notas1']. " " 
    . $row['Notas2'] . " " . $row['Notas3'] . " " . $row['Notas4'] . " " . $row['Notas5']
    . $row['Notas6'] . " " . $row['Notas7'] . "</p>";
  }
}
    
$stmt2 = $conn->prepare($consult);
$stmt2->execute();
$row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC)[0];
$stmt2->closeCursor();
    
                $nullornot2 = is_null($row2);
    
                if ($nullornot2 == TRUE) {
                  if ($nullornotclie == false && $nullornotclie2 == false ){
                  echo " <p class='text-light'>Sin transacciones en el año $input2...</p>";
                  }
                }
    
                $stmtnew = $conn->prepare($consultnew);
                $stmtnew->execute();
                $rownew = $stmtnew->fetchAll(PDO::FETCH_ASSOC)[0];
                $stmtnew->closeCursor();
    
                $nullornotnew = is_null($rownew);
    
                if ($nullornotnew == true) {
                  if($nullornotnew2 == false){
                    echo "<br>
                  <div class='btn-group'>
      <a href='all.php?ci=$rowclie[CodClie]&ip=1' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
      <a href='search2.php' class='btn btn-success'>Realizar otra busqueda</a>
      <br>
    </div>
    <br>
                  <br>
                  <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
                  }
                  else{
                echo "<br>
                <form action='search2.php'>
                  <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
                </form>
                  <br>
                  <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";}
                } else {
                  echo "<br>
                  <div class='btn-group'>
      <a href='all.php?ci=$rowclie[CodClie]&ip=1' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
      <a href='search2.php' class='btn btn-success'>Realizar otra busqueda</a>
      <br>
    </div>
    <br>
                  <br>
                  <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
                }

              } else {
                echo "<div class='alert alert-danger' role='alert'>
              Abogado no inscrito o identifacion erronea, intente de nuevo
            </div>";

            echo "<br>
            <form action='search2.php'>
              <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
            </form>
              <br>
              <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
              }
    
              } else {

                $consultclie= "SELECT * FROM SACLIE where Clase = '$input'";
                $consultclie2= "SELECT * FROM SACLIE where Clase = '$input'";

                require './conn.php'; $conn = DataConnect();

            $stmtclie = $conn->prepare($consultclie);
            $stmtclie->execute();
            $rowclie = $stmtclie->fetchAll(PDO::FETCH_ASSOC)[0];
            $stmtclie->closeCursor();

            $stmtclie2 = $conn->prepare($consultclie2);
            $stmtclie2->Execute();
            $rowclie2 = $stmtclie2->fetchAll(PDO::FETCH_ASSOC)[0];
            $stmtclie2->closeCursor();

            $nullornotclie = is_null($rowclie);
            $nullornotclie2 = is_null($rowclie2);

            if ($nullornotclie == false or $nullornotclie2 == false ) {
              echo " <p class='h1 text-light'>Abogado inscrito!</p><br>";
              if ($nullornotclie == false && $nullornotclie2 == true) {
                $consultSOLV = "SELECT * FROM SOLV WHERE CodClie = '$rowclie[CodClie]' and Status = 1";
                    $stmtSOLV = $conn->prepare($consultSOLV);
                    $stmtSOLV->execute();
                    $rowSOLV = $stmtSOLV->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtSOLV->closeCursor();
                    $nullornotSOLV = is_null($rowSOLV);

                    $consultIns = "SELECT * FROM SACLIE_08 WHERE CodClie = '$rowclie[CodClie]'";
                    $stmtIns = $conn->prepare($consultIns);
                    $stmtIns->execute();
                    $rowIns = $stmtIns->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtIns->closeCursor();

                if ($nullornotSOLV == false) 
                {
                    echo "<p class='text-light'>Solvente hasta: $rowSOLV[hasta]</p><br>";
                } else {
                  echo "<p class='text-light'>Solvencia no registrada</p><br>";
                }
                echo "<p class='text-light'>CI: $rowclie[CodClie]</p><br>";
                echo "<p class='text-light'>Inpre: $rowclie[Clase]</p><br>";
                echo "<p class='text-light'>Fecha de Inscripcion: $rowIns[Fecha]</p><br>";
                echo "<p class='text-light'>Numero de Inscripcion: $rowIns[Numero]</p><br>";
                echo "<p class='text-light'>Folio: $rowIns[Folio]</p><br>";
                echo "<p class='text-light'>$rowclie[Descrip]</p><br>";
                if ($nullornotSOLV == false) 
                {
                echo "<p class='text-light'>CarnetNum: $rowSOLV[CarnetNum2]</p><br>";
                }
                else{
                  echo "<p class='text-light'>No posee carnet de seguridad</p><br>";
                }
              } else {
                $consultSOLV = "SELECT * FROM SOLV WHERE CodClie = '$rowclie[CodClie]' and Status = 1";
                    $stmtSOLV = $conn->prepare($consultSOLV);
                    $stmtSOLV->execute();
                    $rowSOLV = $stmtSOLV->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtSOLV->closeCursor();
                    $nullornotSOLV = is_null($rowSOLV);

                    $consultIns = "SELECT * FROM SACLIE_08 WHERE CodClie = '$rowclie[CodClie]'";
                    $stmtIns = $conn->prepare($consultIns);
                    $stmtIns->execute();
                    $rowIns = $stmtIns->fetchAll(PDO::FETCH_ASSOC)[0];
                    $stmtIns->closeCursor();

                if ($nullornotSOLV == false) 
                {
                    echo "<p class='text-light'>Solvente hasta: $rowSOLV[hasta]</p><br>";
                } else {
                  echo "<p class='text-light'>Solvencia no registrada</p><br>";
                }
                echo "<p class='text-light'>CI: $rowclie[CodClie]</p><br>";
                echo "<p class='text-light'>Inpre: $rowclie[Clase]</p><br>";
                echo "<p class='text-light'>Fecha de Inscripcion: $rowIns[Fecha]</p><br>";
                echo "<p class='text-light'>Numero de Inscripcion: $rowIns[Numero]</p><br>";
                echo "<p class='text-light'>Folio: $rowIns[Folio]</p><br>";
                echo "<p class='text-light'>$rowclie[Descrip]</p><br>";
                if ($nullornotSOLV == false) 
                {
                echo "<p class='text-light'>CarnetNum: $rowSOLV[CarnetNum2]</p><br>";
                }
                else{
                  echo "<p class='text-light'>No posee carnet de seguridad</p><br>";
                }
              }

                  $consulttotal = "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' OR ID3 like '%$rowclie[ID3]%'";
                $consult= "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' and FechaE like '%2025%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2025%'
                ORDER BY FechaE;";
                $consultnew= "SELECT * FROM SAFACT where CodClie like '%$rowclie[CodClie]%' and FechaE like '%2021%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2021%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2024%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2024%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2023%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2023%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2022%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2022%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2020%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2020%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2019%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2019%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2018%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2018%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2017%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2017%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2016%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2016%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2015%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2015%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2014%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2014%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2013%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2013%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2012%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2012%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2011%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2011%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2010%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2010%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2009%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2009%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2008%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2008%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2007%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2007%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2006%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2006%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2005%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2005%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2004%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2004%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2003%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2003%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2002%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2002%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2001%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2001%'
                OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2000%' OR ID3 like '%$rowclie[ID3]%' and FechaE like '%2000%'
                ORDER BY FechaE;";

$consultnew2= "SELECT * FROM SAACXC where CodClie like '%$rowclie[CodClie]%' and FechaE like '%2021%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2024%'
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2023%'
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2022%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2020%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2019%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2018%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2017%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2016%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2015%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2014%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2013%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2012%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2011%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2010%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2009%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2008%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2007%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2006%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2005%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2004%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2003%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2002%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2001%' 
OR CodClie like '%$rowclie[CodClie]%' and FechaE like '%2000%' 
ORDER BY FechaE;";

$stmt3 = $conn->prepare($consult);
            $stmt3->execute();
            $row3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
            $stmt3->closeCursor();

            $nullornot3 = is_null($row3);

            $stmtcodclie = $conn->prepare($consult);
            $stmtcodclie->execute();
            $rows = $stmtcodclie->fetchAll(PDO::FETCH_ASSOC);
            $stmtcodclie->closeCursor();

            if ($nullornot3 == false)
            {
                echo " <p class='h2 text-light'>Operaciones recientes: </p><br>";
                foreach($rows as $row) {
                  echo "<p class='text-light'>".$row['FechaE'] . " " .$row['NumeroD']. " " .$row['OrdenC']. " ". $row['Notas1']. " " 
                  . $row['Notas2'] . " " . $row['Notas3'] . " " . $row['Notas4'] . " " . $row['Notas5']
                  . $row['Notas6'] . " " . $row['Notas7'] . "</p>";
                }
            }

            $stmt2 = $conn->prepare($consult);
            $stmt2->execute();
            $row2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            $stmt2->closeCursor();

            $nullornot2 = is_null($row2);

            if ($nullornot2 == TRUE && $nullornotclie == false) {
              if ($nullornotclie == false && $nullornotclie2 == false)
              echo " <p class='text-light'>Sin transacciones recientes...</p>";
            }

            $stmtnew = $conn->prepare($consultnew);
            $stmtnew->execute();
            $rownew = $stmtnew->fetchAll(PDO::FETCH_ASSOC);
            $stmtnew->closeCursor();

            $nullornotnew = is_null($rownew);

            if ($nullornotnew == true) {
              if($nullornotnew2 == false){
                echo "<br>
              <div class='btn-group'>
  <a href='all.php?ci=$rowclie[CodClie]&ip=1' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
  <a href='search2.php' class='btn btn-success'>Realizar otra busqueda</a>
  <br>
</div>
<br>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
              }
              else{
            echo "<br>
            <form action='search2.php'>
              <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
            </form>
              <br>
              <form action='index.php'>
              <div class='btn-group'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";}
            } else {
              echo "<br>
              <div class='btn-group'>
  <a href='all.php?ci=$rowclie[CodClie]&ip=1' class='btn btn-success active' aria-current='page'>Todas las operaciones</a>
  <a href='search2.php' class='btn btn-success'>Realizar otra busqueda</a>
  <br>
</div>
<br>
              <br>
              <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
            }
            } else {
              echo "<div class='alert alert-danger' role='alert'>
              Abogado no inscrito o identifacion erronea, intente de nuevo
            </div>";

            echo "<br>
            <form action='search2.php'>
              <input type='submit' class='btn btn-success w-100' value='Realizar otra busqueda'>
            </form>
              <br>
              <form action='index.php'>
                  <div class='btn-group'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
            }

            
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