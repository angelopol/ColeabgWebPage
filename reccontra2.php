<!-- ANGELO POLGROSSI | 04124856320 -->
<?php

session_start();

if (empty($_SESSION["reccontra"])) {
    
    header("Location: reccontra.php");
    
    exit();
}

unset( $_SESSION["reccontra"] );
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

                $ci = $_POST["ci"];
                $ip = $_POST["ip"];
                $correo = $_POST["correo"];

                if ($ci == "" or $ip == "" or $correo == "") {
                    header("Location: index.php");
    
              exit();
                } else {
                    

                $consultclie= "SELECT * FROM USUARIOS where CodClie like '%$ci%'";
                $consultclie2= "SELECT * FROM USUARIOS where email = '$correo'";
                $consultclieip= "SELECT * FROM USUARIOS where Clase = '$ip'";
    
                $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

                $stmtclieip = sqlsrv_query( $conn, $consultclieip);
                $rowclieip = sqlsrv_fetch_array( $stmtclieip, SQLSRV_FETCH_ASSOC);
    
                $nullornotclieip = is_null($rowclieip);

                $stmtclie = sqlsrv_query( $conn, $consultclie);
                $rowclie = sqlsrv_fetch_array( $stmtclie, SQLSRV_FETCH_ASSOC);

                $nullornotclie = is_null($rowclie);

                $stmtclie2 = sqlsrv_query( $conn, $consultclie2);
                $rowclie2 = sqlsrv_fetch_array( $stmtclie2, SQLSRV_FETCH_ASSOC);

                $nullornotclie2 = is_null($rowclie2);

                    if ($nullornotclie == false and $nullornotclie2 == false and $nullornotclieip == false) {

                        if ($rowclieip['CodClie'] == $rowclie['CodClie'] and $rowclieip['CodClie'] == $rowclie2['CodClie']) {
                                    
                            $cod = bin2hex(random_bytes(10));

                            $_SESSION["cod"] = $cod;
                            $_SESSION["pass"] = $rowclie['pass'];
                            $_SESSION["correo"] = $correo;
                                    

                                        $mail = new PHPMailer(true);

                                        try {

                                            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
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
                                            $mail->addAddress("$correo");     //Add a recipient
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
                                            $mail->Subject = 'Codigo de Confirmacion';
                                            $mail->Body    = '<h1>El codigo para recuperar su contraseña es:</h1>
                                            <br>
                                            <strong>' . $cod . '</strong>' . '<br>' .
                                            '<br>' .
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>';
                                            $mail->AltBody = '<h1>El codigo para recuperar su contraseña es:</h1>
                                            <br>
                                            <strong>Usuario: ' . $cod . '</strong>' . '<br>' .
                                            '<br>' .
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>';
                                            
                                            if (!$mail->send()) {
                                                throw new Exception($mail->ErrorInfo);
                                              }
                                            
                                              echo "<div class='alert alert-info' role='alert'>
                                              Email con el codigo de confirmacion enviado a su correo: $correo<br>
                                              <em>Si no visualiza el email verifique en la carpeta de spam, si no logra
                                              conseguirlo, intente el <a href = 'reccontra.php'>proceso</a> de nuevo o 
                                              contacte con el <a href = 'soport.php'>soporte</a></em></div><br>";
                                              
                                            } catch (Exception $e) {
                                                echo "<div class='alert alert-danger' role='alert'>
                                              Ha ocurrido un error al momento de enviar el email
                                              con el codigo de confirmacion
                                              al correo electronico proporcionado $correo, por favor
                                               intente el <a href = 'reccontra.php'>proceso</a> de nuevo o 
                                              contacte con el <a href = 'soport.php'>soporte</a></div><br>";
                                            }

                                        echo "<p class='h3 text-light'>Ingrese el codigo de confirmacion</p>
                                        <form action='reccontra3.php' method='post'>
                                            
                                            <div class='form-floating mb-3'>                
                                            <input type='text' name='cod' class='form-control' placeholder='name@example.com' required> 
                                                <label for='floatingInput'>Codigo de confirmacion</label>                    
                                              </div>
                                              <input type='submit' class='btn btn-success w-100' value='Confirmar'>";
                                        
                                              echo        
                                              "
                                              <br>
                                              <br>
                                              <form action='index.php'>
                                          <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
                                          </form>";
                                               
                        
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                            Datos de usuario no coinciden!
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

                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                    Datos de usuarios incorrectos!
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