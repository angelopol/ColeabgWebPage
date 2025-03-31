<!-- ANGELO POLGROSSI | 04124856320 -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacta con el Soporte</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>
<body>
    <div style=" background: url('canchabeisbol.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
                    <p class='h1 text-light'>Contacta con el Soporte</p>
            
            <form action="suport2.php" method="post">
                
                
                <br>
                <div class="form-floating mb-3">
        
                <input name="nombre" type="text" class="form-control" placeholder="name@example.com" required>
                <label for="floatingInput">Nombre y Apellido</label>
                  </div>
                  <p class='text-light'>Evite usar puntos "." o guiones "-" para separar los numeros</p>

                <div class="input-group input-group-sm mb-3"> 
                <span class="input-group-text" id="inputGroup-sizing-sm">Cedula</span>

                    <input type="number"  name="ci" max="99999999" aria-describedby="inputGroup-sizing-sm" class="form-control" required> 
                    <span class="input-group-text" id="inputGroup-sizing-sm">Inpre</span>

                    <input type="number"  name="ip" max="999999" aria-describedby="inputGroup-sizing-sm" class="form-control" required> 
            
        </div>
        <div class="input-group input-group-sm mb-3"> 
                <span class="input-group-text" id="inputGroup-sizing-sm">Telefono</span>

                    <input type="number"  name="telefono" min = "999999999" max="9999999999" aria-describedby="inputGroup-sizing-sm" class="form-control" required> 
                    <span class="input-group-text" id="inputGroup-sizing-sm">Email</span>

                    <input type="email"  name="email" class="form-control" aria-describedby="inputGroup-sizing-sm" required> 
            
        </div>

<div class="form-floating mb-3">
<textarea name="mensaje" type="text" class="form-control" placeholder="Leave a comment here" pattern=".{8,}" style="height: 100px" required></textarea>
                <label for="floatingPassword">Mensaje</label>
                </div>
                <p class='text-light'>No logras contactarnos?, envianos un email a la direccion informaticacolegioabogados@gmail.com</p>
                
<br>
                    <input type="submit" class="btn btn-success w-100" value="Enviar">
                    
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