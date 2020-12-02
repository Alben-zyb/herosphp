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

class Mailer2 {

    //属性
    protected $mail;
    /**
     * 构造方法
     * Mailer constructor.
     */
    public function __construct() {
        //初始化本地服务器信息
        $this->mail = new PHPMailer(true);
        try {
            //发件人(服务器)
            //Server settings
            $this->mail->SMTPDebug  = SMTP::DEBUG_OFF;                   // Enable verbose debug output
            $this->mail->isSMTP();                                       // Send using SMTP
            $this->mail->Host       = 'smtp.163.com';                    // Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                              // Enable SMTP authentication
            $this->mail->Username   = 'Alben_zyb@163.com';               // SMTP username
            $this->mail->Password   = 'QUMTMGKAQJVFRJHI';                // SMTP password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;       // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $this->mail->Port       = 465;                               // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $this->mail->setFrom('Alben_zyb@163.com', '盟大集团');

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function sendEmail($recipients,$subject,$body){

        try {
            //收件人
            foreach ($recipients as $recipient){
                $this->mail->addAddress($recipient);     // Add a recipient
            }

            //$this->mail->addAddress('ellen@example.com');               // Name is optional
            //$this->mail->addReplyTo('info@example.com', 'Information');
            //$this->mail->addCC('cc@example.com');
            //$this->mail->addBCC('bcc@example.com');

            // Attachments
            //$this->mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $this->mail->isHTML(flase);                                  // Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            //$this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $this->mail->send();
            return ['code'=>'0','msg'=>'邮件发送成功'];
        } catch (Exception $e) {
            return ['code'=>'1','msg'=>'邮件发送失败: {$this->mail->ErrorInfo}'];
        }
    }
}