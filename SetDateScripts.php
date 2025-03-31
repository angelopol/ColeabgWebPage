<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Set Date Scripts</title> <!--title of page-->
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
        <form action="SetDateScripts2.php" method="post">
        <br>
                <div class="form-floating mb-3"> 
            
            <input type="text"  name="ScriptName" class="form-control" placeholder="name@example.com" required> 
            <label for="floatingInput">Script Name</label>
            </div>

            <div class="form-floating mb-3"> 
                        
                        <input type="text"  name="date" class="form-control" placeholder="name@example.com" required> 
                        <label for="floatingInput">Date</label>
            
            </div>

            <div class="form-floating mb-3"> 
                        
                        <input type="text"  name="days" class="form-control" placeholder="name@example.com" required> 
                        <label for="floatingInput">Days</label>
            
            </div>

            <input type="submit" class="btn btn-success w-100" value="Register date"> 
        </form>
        <br><a class='btn btn-success' href='HomeFailed.php' role='button'>Return</a>
        
        </div>
        </div>
        </div>
</div>
    </body>
</html>