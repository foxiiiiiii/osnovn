<?php


namespace AmbExpress\infrastructure;


use AmbExpress\ViewModels\FavoriteViewModel;
use AmbExpress\ViewModels\MagazinesViewModel;
use Timber\Timber;

class SystemCore
{
    public function AddFavoriteAjax()
    {
        if(!empty($_POST['id']))
        {
        	$vm = new FavoriteViewModel();
        	if($vm->IsArticleAdded($_POST['id']))
	        {
	        	$vm->RemoveArticle($_POST['id']);
		        wp_send_json_success(['text' => 'Статья удалена']);
	        }
        	else
	        {
		        $vm->AddArticle($_POST['id']);
		        wp_send_json_success(['text' => 'Статья добавдена']);
	        }

        }
        else
        {
        	wp_send_json_error(['text' => 'Нет ID статьи']);
        }


        wp_die();

    }

    public function AskUsPost()
    {
        $context = [];
        $context['name'] = $_POST['name'];
        $context['phone'] = $_POST['phone'];
        $context['email'] = $_POST['email'];
        $context['message'] = $_POST['message'];

        $msg = Timber::compile('part/mail/ask_us_mail.twig', $context);
        MailService::SendMail('info@ab-express.ru', 'Вопрос редакции', $msg);
        $_SESSION['ask_us']['message'] = 'Ваше сообщение успешно отправлено';
        wp_redirect($_SERVER['SERVER_NAME'].'/ask-us/');
    }

    public function YuristQuestionPost()
    {
        $context = [];
        $context['name'] = $_POST['name'];
        $context['phone'] = $_POST['phone'];
        $context['email'] = $_POST['email'];
        $context['message'] = $_POST['message'];

        $msg = Timber::compile('part/mail/ask_us_mail.twig', $context);
        MailService::SendMail('info@ab-express.ru', 'Вопрос юристу', $msg);

        $_SESSION['yurist_question']['message'] = 'Ваше сообщение успешно отправлено';
        wp_redirect($_SERVER['SERVER_NAME'] . '/zadat-vopros-yuristam/');
    }
    public function HotlineAuditPost()
    {
        $context = [];
        $context['name'] = $_POST['name'];
        $context['phone'] = $_POST['phone'];
        $context['email'] = $_POST['email'];
        $context['message'] = $_POST['message'];

        $msg = Timber::compile('part/mail/ask_us_mail.twig', $context);
        $result = MailService::SendMail('info@ab-express.ru', 'Горячие линии с аудиторами', $msg);

        if($result)
            $_SESSION['hotline_audit_us']['message'] = 'Ваше сообщение успешно отправлено!';
        else
            $_SESSION['hotline_audit_us']['message'] = 'Возникли ошибки при отправке сообщения. Попробуйте позже';
        wp_redirect($_SERVER['SERVER_NAME'] . '/hotlines-with-auditors/');
    }

    private function Is_valid_captcha($captcha)
    {
        $captcha_postdata = http_build_query(array(
            'secret' => '6LdKY64ZAAAAANUv2pXgYNRqs5p8LTfwHmCW_FUl',
            'response' => $captcha,
            'remoteip' => $_SERVER['REMOTE_ADDR']));
        $captcha_opts = array('http' => array(
            'method'  => 'POST',
            'header'  => 'Content-type: application/x-www-form-urlencoded',
            'content' => $captcha_postdata));
        $captcha_context  = stream_context_create($captcha_opts);
        $captcha_response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify" , false , $captcha_context), true);
        if ($captcha_response['success'])
            return true;
        else
            return false;
    }

	public function AddSubscriber()
	{
        $recaptcha = $_POST['g-recaptcha-response'];
        if (empty($recaptcha))
        {
            $_SESSION['emailsubscribe']['message'] = 'Пожалуйста поставьте отметку - Я не робот';
            wp_redirect($_SERVER['SERVER_NAME'] . '/our-subscribe/');
        }else if (!$this->Is_valid_captcha($recaptcha))
        {
            $_SESSION['emailsubscribe']['message'] = 'Неправильное значение Captcha';
            wp_redirect($_SERVER['SERVER_NAME'] . '/our-subscribe/');
        }else
        {
            if(!empty($_POST['email']) && !empty($_POST['fio']) && !empty($_POST['phone']))
            {
                if(EmailSubscribeService::AddEmailSubscriber($_POST['email'], $_POST['fio'], $_POST['phone']))
                {
                    $_SESSION['emailsubscribe']['message'] = 'Ваш адрес добавлен в список рассылки новостей';
                }

                wp_redirect($_SERVER['SERVER_NAME'] . '/our-subscribe/');
            }
            else
            {
	            $_SESSION['emailsubscribe']['message'] = 'Заполнены не все поля';
	            wp_redirect($_SERVER['SERVER_NAME'] . '/our-subscribe/');
            }
        }
    }

    public function TestSubscribe()
    {
        $recaptcha = $_POST['g-recaptcha-response'];
        if (empty($recaptcha))
        {
            $_SESSION['testsubscribe']['message'] = 'Пожалуйста поставьте отметку - Я не робот';
            wp_redirect($_SERVER['SERVER_NAME'] . '/probnaya-podpiska/');
        }else if (!$this->Is_valid_captcha($recaptcha))
        {
            $_SESSION['testsubscribe']['message'] = 'Неправильное значение Captcha';
            wp_redirect($_SERVER['SERVER_NAME'] . '/probnaya-podpiska/');
        }else
        {
            if(!empty($_POST['email']) && !empty($_POST['fio']) && !empty($_POST['phone']))
            {
                if(EmailSubscribeService::TestSubscribeRequest($_POST['email'], $_POST['fio'], $_POST['phone']))
                {
                    $_SESSION['testsubscribe']['message'] = 'Ваша заявка на пробную версию журнала отправлена';
                }

                wp_redirect($_SERVER['SERVER_NAME'] . '/probnaya-podpiska/');
            }
            else
            {
	            $_SESSION['testsubscribe']['message'] = 'Заполнены не все поля';
	            wp_redirect($_SERVER['SERVER_NAME'] . '/probnaya-podpiska/');
            }

        }
    }

    public  function GetMagazines()
    {
	    if(!empty($_POST['year']))
	    {
			$magazines = (new MagazinesViewModel())->GetMagazinesByYear($_POST['year']);
		    wp_send_json_success(['magazines' => $magazines]);
	    }
	    else
	    {
		    wp_send_json_error(['magazines' => null]);
	    }

	    wp_die();
    }
}