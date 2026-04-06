<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Datos de Registro</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div
    style="
      background: url('canchabeisbol.jpg') no-repeat center center fixed;
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
                $nombre = $_POST["nombre"];
                $rif = $_POST["rif"];
                $direc = $_POST["direc"];
                $direc2 = $_POST["direc2"];
                $telef = $_POST["telef"];
                $telef2 = $_POST["telef2"];
                $email = $_POST["email"];

                $contra = $_POST["contra"];
                $contra2 = $_POST["contra2"];

                $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

                if ($contra2 == "" or $contra == "" ) {
                    session_start();
                    session_destroy();
                    header("Location: index.php");
    
              exit();
                } else {


                    if ($contra == $contra2) {
                      session_start();

                        $consultusers = "SELECT * FROM USUARIOS where email = '$_SESSION[nameuser]'";

                            $stmtusers = sqlsrv_query( $conn, $consultusers);
                            $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);

                        if ($contra == $rowclieusers['pass']) {

                                        try {
                                            $mail = new PHPMailer(true);

                                            if (!filter_var($_SESSION['nameuser'], FILTER_VALIDATE_EMAIL)) {
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
                                            //$mail->addAddress("$_SESSION[nameuser]");     //Add a recipient
                                            $mail->addAddress('informaticacolegioabogados@gmail.com');               //Name is optional
                                            //$mail->addReplyTo('info@example.com', 'Information');
                                            //$mail->addCC('cc@example.com');
                                            //$mail->addBCC('bcc@example.com');

                                            //Attachments
                                            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                                            //Content
                                            $mail->isHTML(true); 
                                            $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
                                            $mail->Subject = "Solicitud de cambio de datos $rowclieusers[CodClie]";
                                            $mail->Body    = '<h3>Usuario:
                                            </h3>
                                            
                                            <br>' . "$rowclieusers[CodClie], $rowclieusers[Clase], $rowclieusers[email]<br>" .
                    
                                            '<h4>Datos: </h4><br>' . "$ci<br>$ip<br>$nombre<br>$rif<br>$direc<br>$direc2<br>$telef<br>$telef2
                                            <br>$email" ;
                                            $mail->AltBody = '<h3>Usuario:
                                            </h3>
                                            
                                            <br>' . "$rowclieusers[CodClie], $rowclieusers[Clase], $rowclieusers[email]<br>" .
                    
                                            '<h4>Datos: </h4><br>' . "$ci<br>$ip<br>$nombre<br>$rif<br>$direc<br>$direc2<br>$telef<br>$telef2
                                            <br>$email" ;
                                            
                                            if (!$mail->send()) {
                                                throw new Exception($mail->ErrorInfo);
                                              }
                                            
                                            } catch (Exception $e) {
                                                
                                            }

                                            try {
                                                $mail = new PHPMailer(true);
    
                                                if (!filter_var($_SESSION['nameuser'], FILTER_VALIDATE_EMAIL)) {
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
                                                //$mail->addAddress("$_SESSION[nameuser]");     //Add a recipient
                                                $mail->addAddress("$_SESSION[nameuser]");               //Name is optional
                                                //$mail->addReplyTo('info@example.com', 'Information');
                                                //$mail->addCC('cc@example.com');
                                                //$mail->addBCC('bcc@example.com');
    
                                                //Attachments
                                                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                                                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
                                                //Content
                                                $mail->isHTML(true); 
                                                $mail->CharSet = 'UTF-8';                                 //Set email format to HTML
                                                $mail->Subject = "Solicitud de actualizacion de datos procesada!";
                                                $mail->Body    = '<h1>En un lapso de 72 horas sus datos nuevos seran reflejados</h1>
                                                
                                                <br>' . "<p>Si la operacion no se realiza dentro de ese lapso de tiempo lo invitamos
                                                a intentarlo de nuevo o en todo caso contactar con el soporte</p>" . '<br>' .
                                                "<p><em>Si no ha sido usted quien ha realizado esta operacion lo invitamos a contactar con el 
                                                soporte para solucionar el problema lo antes posible. <br><br>" . 
                                                '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>' ;
                                            $mail->AltBody = '<h5>En un lapso de 72 horas sus datos nuevos seran reflejados</h5>
                                                
                                            <br>' . "<p><em>Si la operacion no se realiza dentro de ese lapso de tiempo lo invitamos
                                            a intentarlo de nuevo o en todo caso contactar con el soporte</em></p>" . '<br><br>' .
                                            "<p><em>Si no ha sido usted quien ha realizado esta operacion lo invitamos a contactar con el 
                                            soporte para solucionar el problema lo antes posible. <br><br>" . 
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                        de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>' ;
                                                
                                                if (!$mail->send()) {
                                                    throw new Exception($mail->ErrorInfo);
                                                  }
                                                
                                                } catch (Exception $e) {
                                                    
                                                }

                                        echo "<div class='alert alert-success' role='alert'>
                                        Solicitud de actualizacion de datos procesada!
                                        </div>";

                                        echo "<div class='alert alert-info' role='alert'>
                                        En un lapso de 72 horas sus datos nuevos seran reflejados
                                                        
                                        <br><p><em>Si la operacion no se realiza dentro de ese lapso de tiempo lo invitamos
                                        a intentarlo de nuevo o en todo caso contactar con el <a href='soport.php'>soporte</a></em></p>
                                        </div>";
                        
                                                echo        
                                                "<br>
                                                <form action='ingre.php'>
                                                <input type='submit' class='btn btn-light w-100' value='Inicio'>
                                                </form>
                                                <br>
                                                <form action='index.php'>
                                                <div class='btn-group'>
                                                <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
                                                <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
                                                </div>
                                                </form>";
                                            
                                    
                        
                        
                                   } else {
                            echo "<div class='alert alert-danger' role='alert'>
                    La contraseña es incorrecta, por favor intente de nuevo!
                </div>";
                    echo        
            "<br>
            <form action='datedb2.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intentar de nuevo</button>
            </form>
            <br>
                                        <form action='ingre.php'>
                                        <input type='submit' class='btn btn-light' value='Inicio'>
                                        </form>
            <br>
            <form action='index.php'>
              <div class='btn-group'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
        </form>";
                        }

                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                    La contraseña no coincide con su confirmacion, por favor intente de nuevo!
                </div>";
                    echo        
            "<br>
            <form action='datedb2.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intentar de nuevo</button>
            </form>
            <br>
                                        <form action='ingre.php'>
                                        <input type='submit' class='btn btn-light' value='Inicio'>
                                        </form>
            <br>
            <form action='index.php'>
              <div class='btn-group'>
              <a href='logout.php' class='btn btn-danger' aria-current='page'>Cerrar sesion</a>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </div>
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