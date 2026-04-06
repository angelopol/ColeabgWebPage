<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>All Operations Failed</title> <!--title of page-->
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
                                require './conn.php'; $conn = DataConnect();

                                $consultusers = "SELECT * FROM SOLV where Status = 0 ORDER BY NumeroD";

                                $stmtusers = sqlsrv_query( $conn, $consultusers);
                                $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);

                                $nullornotusers = is_null($rowclieusers);

                                $OrdenC = "";
                                $Note = "";

                                if ($nullornotusers == false) 
                                    {   
                                        $stmt1 = sqlsrv_query( $conn, $consultusers);
                                        while( $rowclieusers = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC) ) 
                                        {   
                                            $OrdenC = "not found";
                                            $Note = "not found";

                                            $consult = "SELECT * FROM SAFACT where NumeroD = '$rowclieusers[NumeroD]'";

                                            $stmt = sqlsrv_query( $conn, $consult);
                                            $rowclie = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);

                                            $nullornot = is_null($rowclie);

                                            if ($nullornot == false) 
                                                {   
                                                    $stmt2 = sqlsrv_query( $conn, $consult);
                                                    while( $rowclie = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) 
                                                    {
                                                        $OrdenC = $rowclie['OrdenC'];
                                                        $Note = $rowclie['Notas1'] . " " . $rowclie['Notas2'] . " " . $rowclie['Notas3'];

                                                    }
                                                }

                                            echo "<div class='alert alert-warning' role='alert'>Ci: " .$rowclieusers['CodClie']. "; Fecha: "
                                            . $rowclieusers['FechaE'] . "; Numero de Factura: " . $rowclieusers['NumeroD'] . "; OrdenC: " . $OrdenC . 
                                            "; Nota: " . $Note . "; Resuelto: " . $rowclieusers['Solved'] . "</div>";

                                        }
                                    }
                                else
                                    {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        No found bad operations.
                                        </div>";
                                    }

                            ?>

                            <br><a class='btn btn-success' href='HomeFailed.php' role='button'>Return</a>
            
                        </div>
                    </div>
                </div>
        </div>
    </body>
</html>