<!-- ANGELO POLGROSSI | 04124856320 -->
<?php
$desde = $_REQUEST["desde"];
$hasta = $_REQUEST["hasta"];
?>
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Asistencia desde: <?php echo $desde;?> hasta: <?php echo $hasta;?></title> <!--title of page-->
        <link rel="shortcut icon" href="favicon.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    </head>

    <body>
        <div
            style="
            background: url('piscina.jpg') no-repeat center center fixed;
            background-size: cover;
            ">
                <div class="container">
                    <div class="row min-vh-100 justify-content-center align-items-center">
                        <div class="col-auto p-5">
                        
                            <?php

                                if (strtotime($desde) < strtotime($hasta)) { 

                                    $password = $_REQUEST["password"];

                                    if (strtotime($desde) >= strtotime('2023-08-14') || $password == "$0p0rt3ca") {

                                        echo "<h1 class='text-light text-center'>Desde: $desde / Hasta: $hasta</h1><br>";

                                        require './conn.php'; $conn = DataConnect();

                                        $consultusers = "SELECT * FROM ASSIST where fecha >= '$desde' and fecha <= '$hasta' ORDER BY fecha";

                                        $stmtusers = $conn->prepare($consultusers);
                                        $stmtusers->execute();
                                        $rowtotals= $stmtusers->fetchAll(PDO::FETCH_ASSOC);
                                        $stmtusers->closeCursor();

                                        $dia = "";

                                        $dia2;

                                        if (isset($rowtotals)) 
                                            {   
                                                $stmt1 = $conn->prepare($consultusers);
                                                $stmt1->execute();
                                                $rowtotals= $stmt1->fetchAll(PDO::FETCH_ASSOC);
                                                $stmt1->closeCursor();
                                                foreach($rowtotals as $rowclieusers) 
                                                {   

                                                    $dia2 = $rowclieusers['fecha'];

                                                    if ($dia != $dia2) {

                                                        echo "</tbody></table>";

                                                        echo "<h2 class='text-light text-center'>";
                                                        
                                                        echo date('l-d', strtotime($dia2));

                                                        echo "</h2>";

                                                        echo "<table class='table table-light table-bordered'>
                                                        <thead>
                                                            <tr>
                                                            <th class = 'text-center' scope='col'>Nombre</th>
                                                            <th class = 'text-center' scope='col'>Entrada</th>
                                                            <th class = 'text-center' scope='col'>Salida</th>
                                                            </tr>
                                                        </thead><tbody class='table-group-divider'>";
                                                        
                                                    } 

                                                    $dia = $rowclieusers['fecha'];

                                                    echo "
                                                        <tr>
                                                        <td class = 'text-center'>$rowclieusers[email]</td>
                                                        <td class = 'text-center'>$rowclieusers[fecha_entrada]</td>
                                                        <td class = 'text-center'>$rowclieusers[fecha_salida]</td>
                                                        </tr>
                                                    ";
                                                }
                                                
                                                echo "</tbody></table>";
                                            }
                                        else
                                            {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                No se encontro registro de asistencia en la fecha seleccionada.
                                                </div>";
                                            }

                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>
                                            No se encuentran registros *desde* la fecha seleccionada.
                                            </div>";
                                    }

                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                            La fecha *desde*, debe ser menor a la fecha *hasta*.
                                            </div>";
                                }

                            ?>
                            <br><a class='btn btn-success btn-lg' href='AssistsView.php' role='button'>Regresar</a>

                        </div>
                    </div>
                </div>
        </div>
    </body>
</html>