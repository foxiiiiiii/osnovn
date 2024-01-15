<?php

require_once(plugin_dir_path( __FILE__ ) . '../vendor/autoload.php');

use Firebase\JWT\JWT;

use Firebase\JWT\Key;

use Timber\Post;

use Timber\Timber;

Predis\Autoloader::register();



use PHPMailer\PHPMailer\PHPMailer;

Twig_Autoloader::register();





function domain($str) {

  // $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";



  // if(strpos($accept_link, 'beta')) {

  //   return 'https://beta.ab-express.ru/' . $str;

  // } else if(strpos($accept_link, 'http')) {

  //   return 'http://ab-express/' . $str;

  // } else {

    // return 'http://ab-express/' . $str;

  // }

  return 'https://ab-express.ru/' . $str;

}



function Twig() {

  $loader = new \Twig\Loader\FilesystemLoader(plugin_dir_path( __FILE__ ) . '../email');

  $twig = new \Twig\Environment($loader, [

    'cache' => plugin_dir_path( __FILE__ ) . '../cache'

  ]);



  return $twig;

}



function Redis() {

  $redis = new Predis\Client([

    'host'   => '127.0.0.1',

    'port'   => 6379,

  ]);



  return $redis;

}



function SendEmail($template, $email, $subject) {

  $mail = new PHPMailer;

  $mail->isSMTP();

  $mail->SMTPDebug = 2;

  $mail->CharSet = "UTF-8"; 

  $mail->Host = 'mail.netangels.ru';

  $mail->Port = 587;

  $mail->SMTPAuth = true;

  $mail->Username = 'noreply@beta.ab-express.ru';

  $mail->Password = 'bWoTTQPxEItiSvXq';

  $mail->SMTPSecure = false;

  $mail->SMTPAutoTLS = true;

  $mail->setFrom('noreply@beta.ab-express.ru', 'Журнал АБ-Экспресс');

  $mail->addAddress($email);

  $mail->Subject = $subject;

  $mail->msgHTML($template);



  if (!$mail->send()) {

    return false;

  } else {

    return true;

  }

}



function SendTelegram($first_name, $last_name, $email, $phone, $edited) {

  $token = '6681054754:AAGR39TmyWi1PukoSCpCSB2CNaf2wfRMqz4';



  $chat_id = -1001848501306;



  $text = "";



  if(!empty($edited)) {

    $text .= "<b>Новые данные</b> \n";

    $text .= "Имя: $first_name \n";

    $text .= "Логин: " . $edited['login'] . " \n";

    $text .= "Старый: " . $edited['old'] . " \n";

    $text .= "Новый: " . $edited['new'] . " \n";

  } else {

    $text .= "<b>Новый пользователь</b> \n";

    $text .= "Имя: $first_name \n";

    $text .= "Фамилия: $last_name \n";

    $text .= "Email:  $email \n";

    $text .= "Телефон: <code>+$phone</code> \n";

  }



  $params = array(

    'chat_id' => $chat_id,

    'text' => $text,

    'parse_mode' => 'HTML',

  );

    

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot'.$token.'/sendMessage'); 

  curl_setopt($curl, CURLOPT_POST, true);

  curl_setopt($curl, CURLOPT_TIMEOUT, 10); 

  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  curl_setopt($curl, CURLOPT_FOLLOWLOCATION , true);

  curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

  $result = curl_exec($curl);

  curl_close($curl);

}



function SignToken($userId) {

  $key = "EJ4iw772k471q1tvSoA2nvo7!ReT423*&";

  

  $payload = [

    'user_id'   => $userId,

  ];

  $token = JWT::encode($payload, $key, 'HS256');



  return $token;

}



function VerifyToken($token) {

  $key = "EJ4iw772k471q1tvSoA2nvo7!ReT423*&";

  

  $decoded = JWT::decode($token, new Key($key, 'HS256'));

  if(isset($decoded->user_id)) {

    return $decoded->user_id;

  } else {

    return $decoded;

  }

}



function CreateToken($payload) {

  $key = "EJ4iw772k471q1tvSoA2nvo7!ReT423*&";

  $token = JWT::encode($payload, $key, 'HS256');

  return $token;

}



function randomPassword() {

  $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

  $pass = array(); //remember to declare $pass as an array

  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

  for ($i = 0; $i < 8; $i++) {

    $n = rand(0, $alphaLength);

    $pass[] = $alphabet[$n];

  }

  return implode($pass); //turn the array into a string

}



function date_compare($element1, $element2) {

  $datetime1 = strtotime($element1['datetime']);

  $datetime2 = strtotime($element2['datetime']);

  return $datetime2 - $datetime1;

} 



function get_posts_by_ids() {

  if(!empty(Redis()->get('posts'))) {

    $ids = json_decode(Redis()->get('posts'));

    $posts = array();



    foreach ($ids as $value) {

      $post = get_post( $value );

      $data = array(

        'link'    => get_permalink( $post->ID ),

        'name'    => $post->post_title,

        'date'    => date('Y-m-d', strtotime($post->post_date)),

        'datetime'=> date('Y-m-d H:i:s', strtotime($post->post_date)),

        'phpdate' => date('d.m.Y', strtotime($post->post_date))

      );



      array_push($posts, $data);

    }

    usort($posts, "date_compare");

    return $posts;

  } else {

    return array();

  }

}



function get_fav_body($ids) {

  $articles = array();

  foreach ($ids as $post) {

      

    $current = Timber::get_post($post);



    $mag_id                      = $current->custom['art_links'][0];

    $magazine                    = new Post( $mag_id );

    $current->custom['magazine'] = $magazine;

    

    $data = array(

      'id'        => $current->ID,

      'title'     => $current->post_title,

      'link'      => get_permalink( $current->ID ),

      'parent'    => $current->custom['magazine']->post_title,

      'loading'   => false

    );





    array_push($articles, $data);

  }



  return $articles;

}



add_action( 'rest_api_init', function () {

  register_rest_route( 'tch', '/registration', array(

    'methods' => 'POST',

    'callback' => 'create_new_user',

    'permission_callback' => '__return_true'

  ) );



  register_rest_route( 'tch', '/login', array(

    'methods' => 'POST',

    'callback' => 'tch_login_user',

    'permission_callback' => '__return_true'

  ) );



  register_rest_route( 'tch', '/restore', array(

    'methods' => 'POST',

    'callback' => 'tch_restore_user',

    'permission_callback' => '__return_true'

  ) );



  register_rest_route( 'tch', '/registration/verified', array(

    'methods' => 'POST',

    'callback' => 'verify_new_user',

    'permission_callback' => '__return_true'

  ) );



  register_rest_route( 'tch', '/user/parse', array(

    'methods' => 'GET',

    'callback' => 'get_user_data',

    'permission_callback' => 'is_user_authed'

  ) );



  register_rest_route( 'tch', '/user/password', array(

    'methods' => 'POST',

    'callback' => 'set_user_password',

    'permission_callback' => 'is_user_authed'

  ) );

  register_rest_route( 'tch', '/user/edit', array(

    'methods' => 'POST',

    'callback' => 'edit_user_profile',

    'permission_callback' => 'is_user_authed'

  ) );

  register_rest_route( 'tch', '/verify', array(

    'methods' => 'GET',

    'callback' => 'confirm_user_profile_new_data',

    'permission_callback' => '__return_true'

  ) );



  register_rest_route( 'tch', '/notify', array(

    'methods' => 'GET',

    'callback' => 'get_user_unread_messages',

    'permission_callback' => 'is_user_authed'

  ) );

  register_rest_route( 'tch', '/notify/clear', array(

    'methods' => 'POST',

    'callback' => 'clear_user_unread_messages',

    'permission_callback' => 'is_user_authed'

  ) );



  register_rest_route( 'tch', '/favorites/add', array(

    'methods' => 'POST',

    'callback' => 'set_user_favorites',

    'permission_callback' => 'is_user_authed'

  ) );

  register_rest_route( 'tch', '/favorites/delete', array(

    'methods' => 'POST',

    'callback' => 'remove_user_favorites',

    'permission_callback' => 'is_user_authed'

  ) );

} );



function is_user_authed($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  if($user_id) return true;

  return false;

}



function create_new_user($req) {



  $first_name = $req['first_name'];

  $last_name = $req['last_name'];

  $email = $req['email'];

  $password = $req['password'];

  $phone = str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $req['phone']));



  $find_user = get_user_by('email', $email);

  $users = get_users(array(

    'meta_key' => 'billing_phone',

    'meta_value' => $phone

  ));



  if($find_user) {

    return new WP_REST_Response(array(

      'error' => 'Пользователь с такой почтой уже зарегистрирован',

      'duplicated_by' => 'email'

    ), 405);

  }



  if($users) {

    return new WP_REST_Response(array(

      'error' => 'Пользователь с таким номером уже зарегистрирован',

      'duplicated_by' => 'phone'

    ), 405);

  }



  $current_date = date('d.m.Y');

  $userdata = [

    'user_login'           => str_replace('@', '', $email),      // (string) Имя пользователя для входа в систему.

    'user_nicename'        => str_replace('@', '', $email),      // (string) Имя пользователя, удобное для URL.

    'user_email'           => $email,      // (string) Адрес электронной почты пользователя.

    'first_name'           => $first_name,      // (string) Имя пользователя.

    'last_name'            => $last_name,      // (string) Фамилия пользователя.

    'show_admin_bar_front' => false,  // (string) Отображать ли панель администратора для пользователя на лицевой стороне сайта.

    'role'                 => 'ab_subscriber',      // (string) Роль пользователя.

    'meta_input'           => [

      'email_verified'     => 'false',

      'subscribe_start'    => $current_date,

      'subscribe_end'      => date('d.m.Y', strtotime($current_date. ' + 1 day')),

      'favorites_list'     => array()

    ], 

  ];



  $user_id = wp_insert_user( $userdata );

  wp_set_password($password, $user_id);

  update_user_meta( $user_id, 'billing_phone', $phone );



  $token = SignToken($user_id);

  $link = domain("auth?verification=$token");

  $template = Twig()->render('welcome.html', [

    'first_name'      => $first_name,

    'action_url'      => $link,

    'email'           => $email,

    'password'        => $password,

    'subscribe_start' => $current_date,

    'subscribe_end'   => date('d.m.Y', strtotime($current_date. ' + 1 day'))

  ]);

  SendEmail($template, $email, 'Подтверждение email адреса'); 

  SendTelegram($first_name, $last_name, $email, $phone, array());



  return $user_id;

}



function tch_login_user($req) {

  $type_of_login = $req['type'] ? 'login' : 'email';

  $userdata = get_user_by($type_of_login, $req['username']);



  if($userdata) {

    $password = wp_check_password($req['password'], $userdata->user_pass, $userdata->ID);

    if($password) {

      $user_meta = get_user_meta($userdata->ID, 'email_verified', true);

      if($user_meta) {

        $verified = filter_var($user_meta, FILTER_VALIDATE_BOOLEAN);

        if($verified) {

          $token = SignToken($userdata->ID);

          return $token;

        } else {

          return new WP_REST_Response(array(

            'error' => 'Мы отправили сообщение на вашу почту, подтвердите аккаунт',

            'duplicated_by' => 'verification'

          ), 400);

        }

      } else {

        $token = SignToken($userdata->ID);

        return $token;

      }

    } else {

      return new WP_REST_Response(array(

        'error' => 'Неверный пароль, попробуйте еще раз',

        'duplicated_by' => 'password'

      ), 401);

    }

  } else {

    return new WP_REST_Response(array(

      'error' => 'Указанный пользователь не зарегистрирован',

      'duplicated_by' => 'email'

    ), 401);

  }

} 



function tch_restore_user($req) {

  $new_password = randomPassword();

  $action_url = domain("auth");



  $type_of_login = $req['type'] ? 'login' : 'email';

  $userdata = get_user_by($type_of_login, $req['username']);



  if(!$userdata) return new WP_REST_Response(array(

    'error' => 'Указанной пользователь не зарегистрирован',

    'duplicated_by' => 'email'

  ), 401);



  wp_set_password($new_password, $userdata->ID);

  $template = Twig()->render('password.html', [

    'first_name'      => $userdata->first_name,

    'action_url'      => $action_url,

    'email'           => $userdata->user_email,

    'password'        => $new_password,

  ]);

  SendEmail($template, $userdata->user_email, 'Новый пароль'); 

  return 'message sent';

}



function verify_new_user($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  update_user_meta($user_id, 'email_verified', 'true');

  return 'updated';

}



function get_user_data($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  $userdata = get_userdata($user_id);



  $subscribe_start = get_user_meta($userdata->ID, 'subscribe_start', true);

  $subscribe_end = get_user_meta($userdata->ID, 'subscribe_end', true);



  $subscribe_end_compare = new DateTime(get_user_meta($userdata->ID, 'subscribe_end', true));



  $current_date = date('d.m.Y');

  $enable_access = false;

  if(new DateTime() < $subscribe_end_compare) {

    $enable_access = true;

  }



  $favorites = get_user_meta( $user_id, 'favorites_list', true );

  $articles = get_fav_body($favorites);



  $response = [

    'username'        => $userdata->user_login,

    'first_name'      => $userdata->first_name,

    'last_name'       => $userdata->last_name,

    'email'           => $userdata->user_email,

    'phone'           => get_user_meta( $userdata->ID, 'billing_phone', true ),

    'subscribe_start' => $subscribe_start,

    'subscribe_end'   => $subscribe_end,

    'access'          => $enable_access,

    'registered'      => date('Y-m-d', strtotime($userdata->user_registered)),

    'sub_start'       => date('Y-m-d', strtotime($subscribe_start)),

    'notifications'   => count(get_posts_by_ids()),

    'favorites'       => $favorites,

    'favArticles'     => $articles

  ];



  return $response;

}



function set_user_password($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  $userdata = get_userdata($user_id);



  $password = wp_check_password($req['old_password'], $userdata->user_pass, $userdata->ID);

  if($password) {

    wp_set_password($req['new_password'], $userdata->ID);

    return 'updated';

  }



  return new WP_REST_Response(array(

    'error' => 'Вы ввели недействующий текущий пароль',

    'duplicated_by' => 'password'

  ), 401);

}



function edit_user_profile($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  $userdata = get_userdata($user_id);



  $field = $req['field'];

  $verification = $req['verification'];

  $user_edit_data = $req['value'];



  if(!$verification) {

    wp_update_user([

      'ID'    => $user_id,

      $field  => $user_edit_data

    ]);



    return 'updated';

  } else {

    $label;

    if($field == 'email') {

      $label = 'Email';

    }

    if($field == 'phone') {

      $label = 'Телефон';

    }

    $payload = array(

      'ID' => $user_id,

      'field' => $field,

      'value' => $user_edit_data,

      'label' => $label

    );

    $accept_link = CreateToken($payload);



    $template = Twig()->render('change.html', [

      'first_name'      => $userdata->first_name,

      'label'           => $label,

      'value'           => $user_edit_data,

      'action_url'      => domain("wp-json/tch/verify?token=$accept_link")

    ]);

    SendEmail($template, $userdata->user_email, "Подтвердите новые данные"); 



    return 'verification';

  }



  // return array($field, $verification, $user_edit_data);

}



function confirm_user_profile_new_data($req) {

  $token = $req->get_param( 'token' );

  $payload = VerifyToken($token);

  $userdata = get_userdata($payload->ID);



  if($payload->field == 'phone') {

    $phone = str_replace(' ', '', preg_replace('/[^\p{L}\p{N}\s]/u', '', $payload->value));

    

    $edited = array(

      'login' => $userdata->user_login,

      'old'   => get_user_meta($payload->ID, 'billing_phone', true),

      'new'   => $phone

    );



    SendTelegram(

      $userdata->first_name, 

      $userdata->last_name, 

      $userdata->user_email, 

      $phone, 

      $edited

    );

    

    update_user_meta( $payload->ID, 'billing_phone', $phone );

    wp_redirect(domain('account?updated=true'));

    exit;

  }



  if($payload->field == 'email') {



    $edited = array(

      'login' => $userdata->user_login,

      'old'   => $userdata->user_email,

      'new'   => $phone

    );



    SendTelegram(

      $userdata->first_name, 

      $userdata->last_name, 

      $userdata->user_email, 

      $phone, 

      $edited

    );



    wp_update_user([

      'ID'    => $payload->ID,

      'user_email'  => $payload->value

    ]);

    wp_redirect(domain('account?updated=true'));

    exit;

  }

}



function get_user_unread_messages($req) {

  return get_posts_by_ids();

}



function clear_user_unread_messages($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);

  update_user_meta( $user_id, 'unread_count', '0' );



  return 'cleared';

}



function set_user_favorites($req) {
  $user_id = VerifyToken($req->get_headers()['token'][0]);

  $articles = get_user_meta($user_id, 'favorites_list', true);

  if (!is_array($articles)) {
      if (empty($articles)) {
          $articles = [];
      } else {
          $articles = explode(',', $articles);
      }
  }

  if (in_array($req['articleId'], $articles)) {
      if (($key = array_search($req['articleId'], $articles)) !== false) {
          unset($articles[$key]);
      }
  } else {
      array_push($articles, $req['articleId']);
  }

  update_user_meta($user_id, 'favorites_list', $articles);

  return array(
      'userId' => $user_id,
      'articles' => get_user_meta($user_id, 'favorites_list', true)
  );
}




function remove_user_favorites($req) {

  $user_id = VerifyToken($req->get_headers()['token'][0]);



  $id = $req['id'];

  $articles = get_user_meta( $user_id, 'favorites_list', true );



  if (($key = array_search($id, $articles)) !== false) {

    unset($articles[$key]);

  }



  update_user_meta( $user_id, 'favorites_list', $articles );



  $articles_data = get_fav_body($articles);





  return $articles_data;

}