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
        <title>Serach By CodClie</title> <!--title of page-->
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
                        <h1 calss="text-center text-light">Buscar Abogado</h1>
        <form action="SearchByCodClie2.php" method="post">
        <br>
                <div class="form-floating mb-3"> 
            
            <input type="text"  name="input" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Cedula</label>
        </div>

            <input type="submit" class="btn btn-success w-100" value="Buscar"> 
        </form>
        <br>
        <form action='SearchByClase.php'>
        <input type="submit" class="btn btn-outline-success w-100" value="Buscar por inpre">
        </form>
        <br>
        <form action='index.php'>
        <button type="submit" id="buttom" class="btn btn-warning">Salir</button>
        </form>
        </div>
        </div>
        </div>
</div>
    </body>
</html>