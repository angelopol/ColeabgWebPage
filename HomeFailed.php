<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Operations pending for solve</title> <!--title of page-->
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

$serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

                                $consultusers = "SELECT * FROM SOLV where (Status = 0 OR Status = 3) and (Solved = 0 OR Solved IS NULL) ORDER BY NumeroD";

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

                                            echo "<div class='alert alert-success' role='alert'>Ci: " .$rowclieusers['CodClie']. "; Fecha: ". $rowclieusers['FechaE']
                                            . "; Numero de Factura: " . $rowclieusers['NumeroD'] . "; OrdenC: " . $OrdenC . "; Nota: " . $Note;

                                            echo "<br><br><a class='btn btn-primary' href='DeleteFailed.php?NumeroD=$rowclieusers[NumeroD]' role='button'>Solved</a></div>";
                                        }
                                    }
                                else
                                    {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        No found bad operations.
                                        </div>";
                                    }

                            ?>

                            <br><a class='btn btn-success' href='AllFailed.php' role='button'>See all results</a>
                            <br><br><a class='btn btn-danger' href='CancelledOperations.php' role='button'>See Cancelled Operations</a>
                            <br><br><a class='btn btn-danger' href='OperationsIncorrectNumeroD.php' role='button'>See Operations with a mistake in NumeroD</a>
                            <br><br><a class='btn btn-danger' href='OperationsStatusTwo.php' role='button'>See Operations with Status 2</a>
                            <br><br><a class='btn btn-danger' href='DuplicateCodClie.php' role='button'>See duplicates for CodClie</a>
                            <br><br><a class='btn btn-danger' href='DuplicatesNumeroD.php' role='button'>See duplicates for NumeroD</a>
                            <br><br><a class='btn btn-danger' href='DuplicateCarnetNum.php' role='button'>See duplicates for CarnetNum</a>
                            <br><br><a class='btn btn-danger' href='PersonsWithoutNORD.php' role='button'>See Persons without Name or Direction</a>
                            <br><br><a class='btn btn-info' href='PersonsWithCarnetNum.php' role='button'>See Persons with CarnetNum</a>
                            <br><br><a class='btn btn-info' href='DateScripts.php' role='button'>See Scripts Date</a>
                            <br><br><a class='btn btn-info' href='SearchOperations.php' role='button'>Search Operations</a>
                            <br><br><a class='btn btn-warning' href='SetCarnetNumFamily.php' role='button'>Insert Familiar data</a>
                            <br><br><a class='btn btn-warning' href='solv.php' role='button'>Insert data</a>
                            <br><br><a class='btn btn-warning' href='SetCarnetNum.php' role='button'>Set CarnetNum</a>
                            <br><br><a class='btn btn-warning' href='SetDateScripts.php' role='button'>Set DateScripts</a>
                            <!--<br><br><a class='btn btn-warning' href='UpdateOperations.php' role='button'>Update data</a>-->
            
                        </div>
                    </div>
                </div>
        </div>
    </body>
</html>