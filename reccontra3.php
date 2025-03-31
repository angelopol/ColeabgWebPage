<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();
if (empty($_SESSION["correo"])) {
    
    header("Location: reccontra.php");
    
    exit();
}

if (empty($_SESSION["pass"])) {
    
    header("Location: reccontra.php");
    
    exit();
}

if (empty($_SESSION["cod"])) {
    
    header("Location: reccontra.php");
    
    exit();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recuperar Contraseña</title>
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
                <div class="row vh-100 justify-content-center align-items-center">
                    <div class="col-auto p-5">
<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php'; // Only file you REALLY need
require 'Exception.php'; // If you want to debug
require 'SMTP.php';

                $cod = $_POST["cod"];

                if ($cod == "") {
                    header("Location: index.php");
    
              exit();
                } else {
   

                        if ($cod == $_SESSION["cod"]) {
                                    

                                        $mail = new PHPMailer(true);

                                        try {

                                            if (!filter_var($_SESSION["correo"], FILTER_VALIDATE_EMAIL)) {
                                                throw new Exception('Dirección de correo electrónico no válida.');
                                              }

                                            //Server settings
                                            $mail->SMTPDebug = 0;                      //Enable verbose debug output
                                            $mail->isSMTP();                                            //Send using SMTP
                                            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                            $mail->Username   = 'coleabgca@gmail.com';                     //SMTP username
                                            $mail->Password   = 'nqqirendnyzasbyf';                               //SMTP password
                                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                                            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                                            //Recipients
                                            $mail->setFrom('coleabgca@gmail.com', 'Colegio de Abogados
                                            del Estado Carabobo');
                                            $mail->addAddress($_SESSION["correo"]);     //Add a recipient
                                            //$mail->addAddress('ellen@example.com');               //Name is optional
                                            //$mail->addReplyTo('info@example.com', 'Information');
                                            //$mail->addCC('cc@example.com');
                                            //$mail->addBCC('bcc@example.com');

                                            //Attachments
                                            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                                            //Content
                                            $mail->isHTML(true); 
                                            $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
                                            $mail->Subject = 'Datos de ingreso al sistema del Colegio de Abogados del Estado Carabobo';
                                            $mail->Body    = '<h1>Sus datos de usuario son:</h1>
                                            <br>'
                                            .
                                            '<b>Contraseña: ' . $_SESSION["pass"] . '</b>' . '<br>' .
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>';
                                            $mail->AltBody = '<h1>Sus datos de usuario son:</h1>
                                            <br>'
                                            .
                                            '<b>Contraseña: ' . $_SESSION["pass"] . '</b>' . '<br>' .
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>';
                                            
                                            if (!$mail->send()) {
                                                throw new Exception($mail->ErrorInfo);
                                              }
                                              echo "<div class='alert alert-success' role='alert'>
                                              Email con sus datos de usuario enviado a su correo: $_SESSION[correo]</div><br>";
                                              
                                            } catch (Exception $e) {
                                                echo "<div class='alert alert-danger' role='alert'>
                                              Ha ocurrido un error al momento de enviar el email
                                              con sus datos de usuario
                                              al correo electronico proporcionado $_SESSION[correo]</div><br>";
                                            }

                                            echo "<div class='alert alert-info' role='alert'>
                                            Si desea cambiar sus datos de usuario <a href= 'ingre.php'>ingrese</a> al sistema
                                             del Colegio de Abogados
                                            del Estado Carabobo y vaya al menu que se muestra tocando el boton
                                            con las tres rayas en el lado superior derecho</div>";
                                        
                                              echo        
                                            
                                              "<br>
                                              <form action='index.php'>
                                          <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
                                          </form>";

                                          session_destroy();
                                               
                        
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                            El codigo de confirmacion no coincide, por favor intentelo de nuevo
                </div>";
                    echo        
            "<br>
            <form action='reccontra.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                        }

        
                    }
        

?>

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