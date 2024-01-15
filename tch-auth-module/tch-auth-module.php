<?php
/**
* Plugin Name: Authentication and account module
* Description: Технический плагин, не в коем случае не деактивируйте этот плагин
* Version: 0.1
* Author: Газиз
* Author URI: https://kwork.ru/user/nextorder
**/

require_once "vendor/autoload.php";
use ReallySimpleJWT\Token;

include_once 'plugins/change-username/change-username.php';
include_once 'api/index.php';
include_once 'snippets.php';

add_action( 'wp_head', function() {
  $url = $_SERVER['REQUEST_URI'];

  wp_enqueue_style( 'font', 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap' );
  wp_enqueue_style( 'main_css', plugin_dir_url( __FILE__ ) . 'assets/css/main.css' );
  wp_enqueue_style( 'mobile_css', plugin_dir_url( __FILE__ ) . 'assets/css/mobile.css', array(), false, 'all and (max-width: 768px)' );
  wp_enqueue_script( 'icons', plugin_dir_url( __FILE__ ) . 'includes/phosphor-icons.js' );
  wp_enqueue_style( 'toastify', 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css' );
}, 1);

add_action('wp_footer', function() {
  $url = $_SERVER['REQUEST_URI'];
  
  // wp_enqueue_script( 'store', plugin_dir_url( __FILE__ ) . 'assets/js/store.js' );
  wp_enqueue_script( 'lottie', plugin_dir_url( __FILE__ ) . 'includes/lottie.min.js' );

  wp_enqueue_script( 'cookies', plugin_dir_url( __FILE__ ) . 'includes/js-cookie.min.js' );
  wp_enqueue_script( 'axios', plugin_dir_url( __FILE__ ) . 'includes/axios.min.js' );
  wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . 'includes/vue.global.js' );
  wp_enqueue_script( 'vuex', plugin_dir_url( __FILE__ ) . 'includes/vuex.global.js' );
  wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ) . 'assets/js/scripts.js' );
  wp_enqueue_script( 'naive', plugin_dir_url( __FILE__ ) . 'includes/naive-ui.js' );
  
  wp_enqueue_script( 'global', plugin_dir_url( __FILE__ ) . 'assets/js/global.js' );

  wp_enqueue_script( 'tosatify', plugin_dir_url( __FILE__ ) . 'includes/toastify.min.js' );
  wp_enqueue_script( 'dayjs', plugin_dir_url( __FILE__ ) . 'includes/dayjs.min.js' );
  wp_enqueue_script( 'maska', plugin_dir_url( __FILE__ ) . 'includes/phone-mask.js' );
  wp_enqueue_script( 'localization', plugin_dir_url( __FILE__ ) . 'includes/dayjs.ru.min.js' );

});

function check_is_user_logged_in($token) {
  return VerifyToken($token);
}

add_shortcode( 'authentication', function() {
  include_once 'templates/login.html';

  // $userdata = get_user_by('email', 'buh@3-gen.ru');
  // echo '<pre>';
  // if(get_user_meta(1354, 'email_verified', true)) {
  //   echo 'have meta';
  // } else {
  //   echo 'no meta';
  // }
  // // print_r(get_user_meta(1354, 'email_verified', true));
  // echo '</pre>';

  // $template = Twig()->render('main.html', ['name' => 'Fabien']);

  // SendEmail($template, 'gizbew@gmail.com');
  // $userId = 12;
  // $secret = 'sec!ReT423*&';
  // $expiration = time() + 3600;
  // $issuer = 'localhost';

  // echo $token = Token::create($userId, $secret);

  // echo $twig->render('main.html', ['text' => 'Fabien']);
  // get user verified : get_user_meta(1470, 'email_verified')[0];

  // 1470
  // $user = get_user_by('email', 'gizbew@gmail.com');
  // $users = get_users(array(
  //   'meta_key' => 'billing_phone',
  //   'meta_value' => '78978979878'
  // ));
  // echo '<pre>';
  // print_r(get_user_meta(1354, 'subscribe_start'));
  // echo '</pre>';

  // if($users) {
  //   echo 'have';
  // } else {
  //   echo 'empty';
  // }
} );

add_shortcode( 'account', function() {
  include_once 'templates/account.html';
} );

add_shortcode( 'header_account', function() {
  include_once 'templates/header_account.html';
} );

add_shortcode( 'sidebar_login', function() {
  include_once 'templates/sidebar_login.html';
} );

add_shortcode( 'favorites_add', function() {
  include_once 'templates/favorites.html';
} );

add_shortcode( 'single_login', function() {
  include_once 'templates/single_login.html';
} );

function _get_the_content($context) {
  echo '<div class="static-content">';
    include_once 'secure/content.php';
  echo '</div>';
}

add_action( 'save_post', function($post_id) {
  // echo '<h1>1231231</h1>';
  // $all_users = get_users();
  // $type = get_post_type($post_id);
  $field = boolval(get_field('notification_add', $post_id));
  if($field == true || $field == false) {
    $allow_display_in_account = get_field('notification_add', $post_id);

    // if(empty($allow_display_in_account)) return;

    $posts = array();
    if(!empty(Redis()->get('posts'))) {
      $posts = json_decode(Redis()->get('posts'));

      if($allow_display_in_account === 'true') {
        array_push($posts, $post_id);
      } else {
        if (($key = array_search($post_id, $posts)) !== false) {
          unset($posts[$key]);
        }
      }
      Redis()->set('posts', json_encode(array_unique($posts)));
    } else {
      array_push($posts, $post_id);
      Redis()->set('posts', json_encode(array_unique($posts)));
    }
  }
  

  // foreach ($all_users as $user) {
  //   $user_id = $user->ID;
  //   $user_unread_count = get_user_meta($user_id, 'unread_count', true);
  //   if(!in_array($post_id, $before_posts)) {
  //     if(empty($user_unread_count)) {
  //       add_user_meta( $user_id, 'unread_count', 1 );
  //     } else {
  //       $count = intval($user_unread_count) + 1;
  //       update_user_meta( $user_id, 'unread_count', $count );
  //     }
  //   }
  // }
} );

add_action('wp_footer', function() {

  $page_id = get_the_ID();
  ?>
    <input type="hidden" id="showSingleLoginModalInArticle" value="<?php echo get_post_meta( $page_id, 'art_only_for_registered', true ) ?>">
  <?php

  // $page = get_post_meta( $page_id, 'art_only_for_registered', true );
  // echo '<pre>';
  // print_r($page);
  // echo '</pre>';

  // if($page) {
  //   echo 'Page open';
  // } else {
  //   echo 'Page close';
  // }
  // $user_info = get_user_meta(962);
  // echo '<pre>';
  // print_r($user_info);
  // echo '</pre>';


  // $favorites = get_user_meta( 1497, 'favorites', true );
  // $articles = array();

  // $userdata = get_userdata(1497);
  
  // $subscribe_end = new DateTime(get_user_meta($userdata->ID, 'subscribe_end', true));
  
  // echo '<pre>';
  
  // if(new DateTime() < $subscribe_end) {
  //   echo 'true';
  // } else {
  //   echo 'false';
  // }

  // echo '</pre>';

  // if(!empty($favorites)) {
  //   foreach ($favorites as $post) {
      
  //     $current = Timber::get_post($post);

  //     $mag_id                      = $current->custom['art_links'][0];
  //     $magazine                    = new Post( $mag_id );
  //     $current->custom['magazine'] = $magazine;


  //     echo '<pre>';
  //     print_r($current);
  //     echo '</pre>';
      
  //     // $data = array(
  //     //   'id'        => $current->ID,
  //     //   'title'     => $current->post_title,
  //     //   'link'      => get_permalink( $current->ID ),
  //     //   'parent'    => $current->post_parent,
  //     // );


  //     // array_push($articles, $data);
  //   }
  // }

  // $vm = new \AmbExpress\ViewModels\FavoriteViewModel();
  // $favorites = $vm->GetUserFavArticles(get_current_user_id());

  // echo '<pre>';
  // print_r($favorites);
  // echo '</pre>';


  // echo boolval(get_field('notification_add', 42504));
  // $posts = get_pages(array('post_type' => 'page'));
  // echo count($posts);

  // echo get_user_meta( 118, 'unread_count');
  // echo get_user_meta( 1497, 'unread_count', true);


  // $all_users = get_users();

  // foreach ($all_users as $user) {
  //   $user_id = $user->ID;
  //   delete_user_meta($user_id, 'unread_count');
  // }
  
  // $field = get_field('notification_add');

  // $args = array(
  //   'meta_key'      => 'notification_add',
  //   'meta_value'    => $field
  // );
  // $posts = get_posts($args);

  // echo '<pre>';
  // print_r($posts);
  // echo '</pre>';
});