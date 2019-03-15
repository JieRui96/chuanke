<?php
namespace app\api\controller;
header("Content-Type: text/html;charset=utf-8");
header('Access-Control-Allow-Origin:*');
use think\Controller;
use think\Db;


use phpmailer\Phpmailer;

class Mail extends Controller
{

    //发送邮箱验证码
    public function email()
    {
        
        $toEmailUser =input('toemail');//定义收件人的邮箱
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "839433277@qq.com";// 发送方的QQ邮箱用户名，就是自己的邮箱名
        $mail->Password = "jwqtbnwaaxclbcgf";// 发送方的邮箱密码，不是登录密码,是qq的第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式,
        $mail->Port = 465;// QQ邮箱的ssl协议方式端口号是465/587
        $mail->isHTML(true);
        $mail->setFrom("839433277@qq.com","传客管理员");// 设置发件人信息，如邮件格式说明中的发件人,
        $mail->addAddress($toEmailUser,'xxxxx');// 设置收件人信息，如邮件格式说明中的收件人
        $mail->addReplyTo("839433277@qq.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        $mail->Subject = "传客账号重置密码";// 邮件标题
        $mail->Body = "<a href='http://localhost/chuanke/public/index.php/Superadmin/repasswd'> 重置密码请点击此链接</a>";// 邮件正文

        if(!$mail->send()){// 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
        }else{
            echo '发送成功';
        }
    }
}   
?>