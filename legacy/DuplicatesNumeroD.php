<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Duplicates for NumeroD</title> <!--title of page-->
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

                                $consultusers = "SELECT NumeroD, COUNT(NumeroD) FROM SOLV GROUP BY NumeroD HAVING COUNT(NumeroD) > 1";

                                $stmtusers = sqlsrv_query( $conn, $consultusers);
                                $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);

                                $nullornotusers = is_null($rowclieusers);

                                if ($nullornotusers == false) 
                                    {   
                                        echo "<ul class='list-group'>";

                                        $stmt1 = sqlsrv_query( $conn, $consultusers);
                                        while( $rowclieusers = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC) ) 
                                        {

                                            echo "<li class='list-group-item'>" . $rowclieusers['NumeroD'] . "</li>";

                                            echo "<br>";

                                        }

                                        echo "</ul>";

                                    }
                                else
                                    {
                                        echo "<div class='alert alert-danger' role='alert'>
                                        No found duplicates for NumeroD.
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