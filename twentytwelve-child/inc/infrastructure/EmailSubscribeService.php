<?php

namespace AmbExpress\infrastructure;

use Timber\Timber;

class EmailSubscribeService
{
	public static function AddEmailSubscriber( $email, $fio, $phone )
	{
		$context = [];
		$context['fio'] = $fio;
		$context['phone'] = $phone;
		$context['email'] = $email;
		$context['message'] = 'Заявка на рассылку от - ' . $email;

		$msg = Timber::compile('part/mail/test_subscribe.twig', $context);
        
        return MailService::SendMail('alexander.ab-express@yandex.ru', 'Подписка на рассылку', $msg);
	}

    public static function TestSubscribeRequest( $email, $fio, $phone )
    {
	    $context = [];
	    $context['fio'] = $fio;
	    $context['phone'] = $phone;
	    $context['email'] = $email;
	    $context['message'] = 'Заявка на пробную версию журнала "АБ-Экспресс"';

	    $msg = Timber::compile('part/mail/test_subscribe.twig', $context);
        return MailService::SendMail('alexander.ab-express@yandex.ru', 'Заявка на пробную версию журнала "АБ-Экспресс"', $msg);
    }

	public static function GenerateString( $charsCount = 20 )
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle($permitted_chars), 0, $charsCount);
	}
}