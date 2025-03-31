<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ingresar</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    
</head>
<body>
    <div style=" background: url('domo.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
                        <div class="mb-3">
                        <p class='h5 text-light'>Monto:</p>
                        <br>
                        <p class='h5 text-light'>Periodo del pago:</p>
                        <br>
                        <p class='h5 text-light'>Datos de pago:</p>
                        <br>
                        <form action="paid2.php" method="post" enctype="multipart/form-data">

                        <p class='h5 text-light'>Numero de referencia:</p>

                        <input type="number" name="ref" class="form-control" required>
                        <br> 

                        <p class='h5 text-light'>Captura comprobante del pago:</p>

                        <input type="file" class="form-control" id="formFile" name="fileToUpload" id="fileToUpload" required>
                        <br>

                        <p class='h5 text-light'>Nota:</p>

                        <textarea class="form-control" name = "nota"></textarea>

                        <br>

                        <input type="submit" value="Enviar" class = "btn btn-success w-100" name="submit">
                        </form>
                        </div>
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