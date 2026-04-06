<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ver asistencia</title> <!--title of page-->
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
        <form action="AssistsView2.php" method="post">
            <h3 class="text-light text-center">EJ: 2023-08-01</h3>
            <h3 class="text-light text-center">EJ: AÑO-MES-DIA</h3>
        <br>
                <div class="form-floating mb-3"> 
            <input type="text"  name="desde" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Desde: </label>
</div>
    <div class="form-floating mb-3"> 
                
                <input type="text"  name="hasta" class="form-control" placeholder="name@example.com"> 
                <label for="floatingInput">Hasta:</label>
    </div>

    <div class="mb-3"> 
                
                <input type="text" class="form-control form-control-sm" name="password"> 
    </div>

            <input type="submit" class="btn btn-success w-100" value="Buscar"> 
        </form>
</div>
    </body>
</html>