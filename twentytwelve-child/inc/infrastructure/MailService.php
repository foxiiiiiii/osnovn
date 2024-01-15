<?php


namespace AmbExpress\infrastructure;


class MailService
{
    public static function SendMail($to, $subject, $msg)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: Форма c сайта Аб-Экспресс<oleg_ivanov@ab-express.ru>\r\n" .
            'Reply-To: admin@ab-express.ru' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        return wp_mail($to, $subject, $msg, $headers);
    }
}