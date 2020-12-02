<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-29 下午5:26
 * @function  测试邮箱发送
 */

//引用PHPmailer函数包的文件
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../vendor/phpmailer/phpmailer/src/SMTP.php';*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../vendor/autoload.php';

/*$mail = new PHPMailer();
//发送者
$mail->setFrom('Alben_zyb@163.com');
//接收者
$mail->addAddress('867512099@qq.com');
//邮件主题
$mail->Subject = 'Message sent by PHPMailer';
//邮件内容
$mail->Body = 'Hello! use PHPMailer to send email using PHP';

$mail->IsSMTP();
$mail->SMTPSecure = 'ssl';
//你的邮箱的SMTP服务器地址，以163邮箱为例
$mail->Host = 'smtp.163.com';
$mail->SMTPAuth = true;
$mail->Port = 465;
$mail->CharSet='UTF-8';
//你的邮箱地址，即（一）中你申请的邮箱
$mail->Username = "Alben_zyb@163.com";

//注意！此处的密码并非登录密码，而是（一）中提到的授权码！
$mail->Password = 'QUMTMGKAQJVFRJHI';

//下面这条语句最好加上，以防ssl未认证通过
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

if(!$mail->send())
{
    echo 'Email is not sent.';
    echo 'Email error: ' . $mail->ErrorInfo;
}
else
{
    echo 'Email has been sent.';
}*/

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../../vendor/autoload.php';*/

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'Alben_zyb@163.com';                     // SMTP username
    $mail->Password   = 'QUMTMGKAQJVFRJHI';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('Alben_zyb@163.com', 'Mailer');
    $mail->addAddress('867512099@qq.com', 'Joe User');     // Add a recipient
    $mail->addAddress('1599602540@qq.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}