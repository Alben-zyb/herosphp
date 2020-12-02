<?php
/**
 * @author    ZhengYiBin<zhengyb@pvc123.com>
 * @date      2020-07-29 下午6:43
 * @function  发送邮件工具类
 */

namespace app\room\utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    //静态属性,静态调用
    static public $mail; //邮箱对象
    /**
     * 构造方法
     * Mailer constructor.
     */
    public function __construct() {
        //初始化本地服务器信息
        self::$mail = new PHPMailer(true);
        try {
            //发件人(服务器)
            //Server settings
            self::$mail->SMTPDebug  = SMTP::DEBUG_OFF;                   // Enable verbose debug output
            self::$mail->isSMTP();                                       // Send using SMTP
            self::$mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
            self::$mail->SMTPAuth   = true;                              // Enable SMTP authentication
            self::$mail->Username   = 'Alben_zyb@163.com';               // SMTP username
            self::$mail->Password   = 'QUMTMGKAQJVFRJHI';                // SMTP password
            self::$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            self::$mail->Port       = 465;                               // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            self::$mail->setFrom('Alben_zyb@163.com', '盟大集团');

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: ".self::$mail->ErrorInfo;
        }
    }

    static public function sendEmail($recipients,$subject,$body){

        new self(); //实例化本身
        try {
            //收件人
            foreach ($recipients as $recipient){
                self::$mail->addAddress($recipient);     // Add a recipient
            }

            //self::$mail->addAddress('ellen@example.com');               // Name is optional
            //self::$mail->addReplyTo('info@example.com', 'Information');
            //self::$mail->addCC('cc@example.com');
            //self::$mail->addBCC('bcc@example.com');

            // Attachments
            //self::$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //self::$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            self::$mail->isHTML(flase);                                  // Set email format to HTML
            self::$mail->Subject = $subject;
            self::$mail->Body    = $body;
            //self::$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            self::$mail->send();
            return ['code'=>'0','msg'=>'邮件发送成功'];
        } catch (Exception $e) {
            return ['code'=>'1','msg'=>'邮件发送失败: {self::$mail->ErrorInfo}'];
        }
    }
}
