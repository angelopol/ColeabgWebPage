<!-- ANGELO POLGROSSI | 04124856320 -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrarse</title>
    <link rel="shortcut icon" href="favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <div
    style="
      background: url('salonestudiosjuridicos.jpg') no-repeat center center fixed;
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
                $correo2 = $_POST["correo2"];
                $contra = $_POST["contra"];
                $contra2 = $_POST["contra2"];


                if ($ci == "" or $ip == "" or $correo == "" or $contra == "" ) {
                    header("Location: index.php");
    
              exit();
                } else {

                $consultclie= "SELECT * FROM SACLIE where CodClie like '%$ci%'";
                $consultclie2= "SELECT * FROM SACLIE where ID3 like '%$ci%'";
                $consultclieip= "SELECT * FROM SACLIE where Clase like '%$ip%'";
    
                $serverName = "sql5111.site4now.net"; 
$connectionInfo = array( "Database"=>"db_aa07eb_coleabg", "UID"=>"db_aa07eb_coleabg_admin", "PWD"=>"$0p0rt3ca" ,'ReturnDatesAsStrings'=>true);
require './conn.php'; $conn = sqlsrv_connect(DataConnect()[0], DataConnect()[1]);

                $stmtclieip = sqlsrv_query( $conn, $consultclieip);
                $rowclieip = sqlsrv_fetch_array( $stmtclieip, SQLSRV_FETCH_ASSOC);
    
                $nullornotclieip = is_null($rowclieip);

                if ($nullornotclieip == false) {
                    $stmtclie = sqlsrv_query( $conn, $consultclie);
                $rowclie = sqlsrv_fetch_array( $stmtclie, SQLSRV_FETCH_ASSOC);
    
                $nullornotclie = is_null($rowclie);

                $stmtclie2 = sqlsrv_query( $conn, $consultclie2);
                $rowclie2 = sqlsrv_fetch_array( $stmtclie2, SQLSRV_FETCH_ASSOC);
    
                $nullornotclie2 = is_null($rowclie2);

                    if ($nullornotclie == false or $nullornotclie2 == false) {

                        if ($rowclieip['CodClie'] == $rowclie['CodClie'] or $rowclieip['CodClie'] == $rowclie2['CodClie']) {

                            $consultusers = "SELECT * FROM USUARIOS where CodClie like '%$ci%'";

                            $stmtusers = sqlsrv_query( $conn, $consultusers);
                            $rowclieusers = sqlsrv_fetch_array( $stmtusers, SQLSRV_FETCH_ASSOC);
    
                            $nullornotusers = is_null($rowclieusers);

                            $consultusersip = "SELECT * FROM USUARIOS where Clase like '%$ip%'";

                            $stmtusersip = sqlsrv_query( $conn, $consultusersip);
                            $rowclieusersip = sqlsrv_fetch_array( $stmtusersip, SQLSRV_FETCH_ASSOC);
    
                            $nullornotusersip = is_null($rowclieusersip);

                            if ($nullornotusers == true && $nullornotusersip == true) {

                                $consultusersco = "SELECT * FROM USUARIOS where email like '%$correo%'";

                            $stmtusersco = sqlsrv_query( $conn, $consultusersco);
                            $rowclieusersco = sqlsrv_fetch_array( $stmtusersco, SQLSRV_FETCH_ASSOC);
    
                            $nullornotusersco = is_null($rowclieusersco);

                            if ($nullornotusersco == true) {

                            if ($correo == $correo2) {
                                
                                if ($contra == $contra2) {

                                    $consultcreate= "INSERT INTO USUARIOS (email, pass, Clase, CodClie)
                                    VALUES ('$correo', '$contra', '$rowclie[Clase]', '$rowclie[CodClie]' );";
                                    
                                    $stmtcreate = sqlsrv_query( $conn, $consultcreate);
                                    
                                    if ($stmtcreate == false) {
                                        echo "<div class='alert alert-danger' role='alert'>
                    Ha ocurrido un error, por favor intente de nuevo!
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>"
            ;
                                    } else {

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
                                            $mail->addAddress("$correo", "$ci");     //Add a recipient
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
                                            $mail->Subject = 'Bienvenido al Colegio de Abogados del Estado 
                                            Carabobo!';
                                            $mail->Body    = '<h1>Sus datos de usuario son:</h1>
                                            <br>
                                            <b>Usuario: ' . $correo . '</b>' . '<br>' .
                                            '<b>Contraseña: ' . $contra . '</b>' . '<br>' .
                                            '<p><em>Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com</em></p>';
                                            $mail->AltBody = 'Sus datos de usuario son: Usuario: ' . $correo . ' '. 'Contraseña: ' . $contra . ' ' . 
                                            'Si tiene algun problema con respecto al funcionamiento
                                            de la pagina web no dude en contactarnos al correo electronico informaticacolegioabogados@gmail.com';
                                            
                                            if (!$mail->send()) {
                                                throw new Exception($mail->ErrorInfo);
                                              }
                                            
                                              echo "<div class='alert alert-info' role='alert'>
                                              Email con sus datos de usuario enviado
                                              al correo electronico proporcionado $correo</div>";
                                              
                                            } catch (Exception $e) {
                                                echo "<div class='alert alert-danger' role='alert'>
                                              Ha ocurrido un error al momento de enviar el email
                                              con sus datos de usuario
                                              al correo electronico proporcionado $correo</div>";
                                            }

                                        echo "<div class='alert alert-success' role='alert'>
                    Usuario creado de forma exitosa, ya puede ingresar al sistema!
                </div>";
                echo "</form>
                                        <br>
                                        <form action='ingre.php'>
                                        <input type='submit' class='btn btn-success w-100' value='Ingresar'>
                                        </form>";
                    echo        
            "</form>
            <br>
            <form action='index.php'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
                                    }

                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                    Las contraseñas no coinciden, por favor intente de nuevo!
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                        
                                Los correos electronicos no coinciden, por favor intente de nuevo...
                    </div>";
                        echo        
                "<br>
                <form action='regis.php'>
                <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
                </form>
                <br>
                <form action='index.php'>
            <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
            </form>";
                            }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>
                                Correo electronico ya registrado!
                    
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                            }
                        
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                            Usuario ya registrado!
                    
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                        }
                        } else {
                            echo "<div class='alert alert-danger' role='alert'>
                            Cedula e Inpre no coinciden!
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                        }

                    } else {
                        echo "<div class='alert alert-danger' role='alert'>
                    Abogado no inscrito o datos de identidad ingresados de forma incorrecta!
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
            <button type='submit' id='buttom' class='btn btn-primary'>Intente de nuevo</button>
            </form>
            <br>
            <form action='index.php'>
        <button type='submit' id='buttom' class='btn btn-warning'>Salir</button>
        </form>";
                    }

                } else {
                    echo "<div class='alert alert-danger' role='alert'>
                    Abogado no inscrito o datos de identidad ingresados de forma incorrecta!
                </div>";
                    echo        
            "<br>
            <form action='regis.php'>
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