<?php
session_start();
    
    if( isset( $_COOKIE['user']) == false )
    {
        header("Location: ingre_workers.php");
        
                    exit();
    }   

    session_start();
   
    $_SESSION["assist"] = 1;
?>

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
<body data-temp="<?= $temp = $_COOKIE['user']?>">
    <div style=" background: url('domo.jpg') no-repeat center center fixed;
      background-size: cover; ">
            <div class="container">
                <div class="row min-vh-100 justify-content-center align-items-center">
                    <div class="col-auto">
                    <p class='h1 text-light text-center'>Marca tu asistencia</p>

                    <?php echo
                    "<p class='h1 text-light text-center'>$_COOKIE[user]</p>";
                    ?>
					<br>
						<div class="d-grid gap-2 col-6 mx-auto">
						<button class="btn btn-danger btn-lg" type="send" id="btnIniciar" >Entrada</button>
						<br>
						<button class="btn btn-success btn-lg" type="button" id="btnDetener" >Salida</button>
                        <p class="text-light text-center" id="message"></p>
						</div>
					</div>
				</div>
			</div>
            <nav class="navbar sticky-bottom bg-body-tertiary">
  <div class="container-fluid">
  <p class="text-light" id="latitud"></p>
        <p class="text-light" id="longitud"></p>
  </div>
</nav>
	</div>
	<script src="workers.js">
	</script>
</body>

</html>