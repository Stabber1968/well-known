<?php
require_once('../Controllers/init.php');
//
$user = new User();

$info = $user->getValueOfAnyTable('backup','id','=','1');
$info = $info->results();

$host = Config::get('mysql/host');
$username = Config::get('mysql/username');
$password = Config::get('mysql/password');
$database_name = Config::get('mysql/db');
$filename='database_backup_'.date('m_d_y').'.sql';


$command = "mysqldump --host ".$host." -u ".$username." -p'".$password."' ".$database_name." > ../DB/".$filename;
exec($command);

sleep(15);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once "../vendor/autoload.php";
$mail = new PHPMailer(true);

//Enable SMTP debugging.
$mail->SMTPDebug = 3;
//Set PHPMailer to use SMTP.
$mail->isSMTP();
//Set SMTP host name
$mail->Host = $info[0]->smtp_host;
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;
//Provide username and password
$mail->Username = $info[0]->smtp_username;
$mail->Password = $info[0]->smtp_password;
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = $info[0]->smtp_encryption;
//Set TCP port to connect to
$mail->Port = $info[0]->smtp_port;
$mail->SMTPDebug=0;

$superAdminEmail = $info[0]->send_email;

//send
$mail->setFrom('from@site.com', 'WeezGarden Backup server');
$mail->addAddress($superAdminEmail);
$mail->addAddress($superAdminEmail, 'support');
$mail->isHTML(true);
$mail->Subject = 'Database Backup';
$mail->Body    = "Database Backup";
$mail->AltBody = 'Body in plain text for non-HTML mail clients';
$mail->addAttachment('../DB/'.$filename);
try {
    $mail->send();
} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}


