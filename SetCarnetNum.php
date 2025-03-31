<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Set Carnet Num</title> <!--title of page-->
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
                    <h1 class="text-light text-center">Set Carnet Number</h1>
                    <br>
        <form action="SetCarnetNum2.php" method="post">
        <br>
                <div class="form-floating mb-3"> 
            
            <input type="text"  name="ci" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Cedula</label>
            </div>

            <div class="form-floating mb-3"> 
                        
                        <input type="number"  name="number" class="form-control" maxlength="10" minlength="10" placeholder="name@example.com" required> 
                        <label for="floatingInput">Carnet Number</label>
            
            </div>

            <div class="form-floating mb-3"> 
                        
                        <input type="number"  name="number2" class="form-control" maxlength="10" minlength="10" placeholder="name@example.com" required> 
                        <label for="floatingInput">Confirm Carnet Number</label>
            
            </div>

            <input type="submit" class="btn btn-success w-100" value="Registrar"> 
        </form>

        <br><a class='btn btn-success' href='solv.php' role='button'>Registrar Solvencia</a>
        <br>
        <br><a class='btn btn-success' href='HomeFailed.php' role='button'>Return</a>
        
        </div>
        </div>
        </div>
</div>
    </body>
</html>