<?php /*
Flat Theme Child */

/* Importing styles from the parent theme */
include_once 'inc/dto/DTO_Magazine.php';
require_once 'vendor/autoload.php';

use AmbExpress\infrastructure\SharedConst;
use AmbExpress\infrastructure\SystemCore;
use Timber\PostQuery;
use Timber\Timber;

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles()
{
	if ( ! is_admin() )
	{
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
		wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css' );
		wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
		wp_enqueue_style( 'bootstrap-style', get_stylesheet_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css', array(), '3.3.7' );
		wp_enqueue_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array(), '3.3.7' );
		wp_enqueue_script( 'bootstrap-js',
			get_stylesheet_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js',
			'jquery',
			'3.3.7',
			true );
		if ( is_page( 'glavnaya' ) )
		{
			wp_enqueue_script( 'login-js',
				get_stylesheet_directory_uri() . '/inc/js/login.js',
				'jquery',
				'0.0.1',
				true );
		}else if(is_page('favorites'))
		{
			wp_enqueue_script( 'fav',
				get_stylesheet_directory_uri() . '/inc/js/fav.js',
				'jquery',
				'0.0.1',
				true );

			add_filter('script_loader_tag', static function($tag, $handle, $src){
				// if not your script, do nothing and return original $tag
				if ( 'fav' !== $handle ) {
					return $tag;
				}
				// change the script tag by adding type="module" and return it.
				$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
				return $tag;
			}, 10, 3);
		}else if ( is_page( 'arch' | 'arhiv-nomerov' ) )
		{
			wp_enqueue_script( 'read',
				get_stylesheet_directory_uri() . '/inc/js/arch.js',
				'arch-js',
				'0.0.1',
				true );
		} /*else if (is_page(['our-subscribe', 'probnaya-podpiska']))
        {
            wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
        } */

		wp_enqueue_script('jquery.min', get_template_directory_uri() . '/jquery.min.js', array(), '', true );
		wp_enqueue_script('slick.min', get_template_directory_uri() . '/slick.min.js', array(), '', true );
		wp_enqueue_script('script', get_template_directory_uri() . '/script.js', array(), '', true );


		if ( is_singular( 'article' ) && is_user_logged_in() )
		{
			wp_enqueue_script( 'article-js',
				get_stylesheet_directory_uri() . '/inc/js/article.js',
				'jquery',
				'1.0.0',
				true );

			wp_localize_script( 'article-js', 'ajax_core', array(
				'url'        => admin_url( 'admin-ajax.php' )
			) );

			add_filter('script_loader_tag', static function($tag, $handle, $src){
				// if not your script, do nothing and return original $tag
				if ( 'article-js' !== $handle ) {
					return $tag;
				}
				// change the script tag by adding type="module" and return it.
				$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
				return $tag;
			}, 10, 3);
		}
        if ( is_singular( 'bulletinsarticle' ) && is_user_logged_in() )
        {
            wp_enqueue_script( 'article-js',
                get_stylesheet_directory_uri() . '/inc/js/article.js',
                'jquery',
                '1.0.0',
                true );

            wp_localize_script( 'article-js', 'ajax_core', array(
                'url'        => admin_url( 'admin-ajax.php' )
            ) );

            add_filter('script_loader_tag', static function($tag, $handle, $src){
                // if not your script, do nothing and return original $tag
                if ( 'article-js' !== $handle ) {
                    return $tag;
                }
                // change the script tag by adding type="module" and return it.
                $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
                return $tag;
            }, 10, 3);
        }

	}

    if ( is_page( ['search', 'read'] ))
    {
        wp_enqueue_script( 'read',
            get_stylesheet_directory_uri() . '/inc/js/read.js',
            'vue-js',
            '0.0.1',
            true );
        wp_localize_script( 'read',
            'ambajax',
            array(
                'url' => admin_url( 'admin-ajax.php' )
            ) );
    }

    wp_enqueue_script('external-links', get_stylesheet_directory_uri() . '/inc/js/external_links.js','jquery', '1.0.0', true );
}





function get_magazines()
{
	if ( ! empty( $_POST['year'] ) )
	{
		$year = $_POST['year'];

		$args      = [
			'post_type'   => 'magazine',
			'numberposts' => 20,
			'meta_query'  => [
				[
					"key"     => "release_date_from",
					"value"   => $year,
					"compare" => "LIKE",
				]
			]

		];
		$magazines = Timber::get_posts( $args );
		$result    = [];
		foreach ( $magazines as $magazine )
		{
			$result[] = new DTO_Magazine( $magazine->ID, $magazine->Title );
		}
		echo json_encode( $result, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_UNESCAPED_SLASHES );
	}
	else
	{
		echo json_encode( null );
	}
	wp_die();
}

function read_search_handler()
{
    if($_POST['entitytype'] != $_SESSION['read_search']['type'])
    {
        unset($_SESSION['read_search']);
        $_SESSION['read_search']['type'] = $_POST['entitytype'];

        unset($_POST['year']);
        unset($_POST['mag_num']);
        unset($_POST['onlyFree']);
        unset($_POST['section']);
        //unset($_POST['search_text']);

        header( 'Location: ' . $_POST['back_addr'] );
    }

    $_SESSION['read_search']['type'] = $_POST['entitytype'];

    if ( ! empty( $_POST['year'] ) )
    {
        $_SESSION['read_search']['year'] = $_POST['year'];
    }
    if ( ! empty( $_POST['mag_num'] ) )
    {
        $_SESSION['read_search']['mag_num'] = $_POST['mag_num'];
    }
    else
    {
        unset( $_SESSION['read_search']['mag_num'] );
    }

    if ( isset( $_POST['onlyFree'] ) )
    {
        $_SESSION['read_search']['onlyFree'] = '1';
    }
    else
    {
        unset( $_SESSION['read_search']['onlyFree'] );
    }

    if ( ! empty( $_POST['section'] ) )
    {
        $_SESSION['read_search']['section'] = $_POST['section'];
    }
    if ( isset( $_POST['search_text'] ) )
    {
        $_SESSION['read_search']['search_text'] = $_POST['search_text'];
    }

    header( 'Location: ' . $_POST['back_addr'] );
}

add_action( 'admin_post_nopriv_read_search', 'read_search_handler' );
add_action( 'admin_post_read_search', 'read_search_handler' );

add_action( 'init', 'si_register_author' );

function si_register_author(){
	register_post_type( 'authors', [
	'labels' => [
			'name'               => 'Авторы', // основное название для типа записи
			'singular_name'      => 'Автор', // название для одной записи этого типа
			'add_new'            => 'Добавить нового автора', // для добавления новой записи
			'add_new_item'       => 'Добавить нового автора', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать автора', // для редактирования типа записи
			'new_item'           => 'Новый автор', // текст новой записи
			'view_item'          => 'Смотреть автора', // для просмотра записи этого типа.
			'search_items'       => 'Искать автора', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Авторы', // название меню
		],
		'public'                 => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-businessman',
		'hierarchical'        => false,
		'supports'            => [ 'title' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
		'rewrite'             => false,
	]);
}
//---------------------------------------------------------------------------------

// Добавление поля выбора автора по умолчанию к меню "Авторы"
function add_default_author_dropdown_to_menu() {
    add_submenu_page(
        'edit.php?post_type=authors',
        'Настройки авторов',
        'Настройки авторов',
        'manage_options',
        'author_settings',
        'author_settings_page'
    );
}

function author_settings_page() {
    ?>
    <div class="wrap">
        <h2>Настройки авторов</h2>
        <div id="author-settings-form">
            <?php
            settings_fields('author_settings_group');
            do_settings_sections('author_settings');
            ?>
            <button id="save-author-settings" class="button button-primary">Сохранить настройки</button>
            <div id="notification" class="notification"></div>
        </div>
    </div>

    <style>
        #notification {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: opacity 0.5s ease;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById('save-author-settings').addEventListener('click', saveAuthorSettings);
        });

        function saveAuthorSettings() {
            var data = {
                action: 'save_author_settings',
                security: '<?php echo wp_create_nonce("save_author_settings"); ?>',
                default_author: jQuery(".default-author-select").val(),
                column_count: 1
            };

            jQuery.post(ajaxurl, data, function (response) {
                var notification = document.getElementById('notification');
                showNotification(notification, response.success ? 'Настройки сохранены!' : 'Ошибка при сохранении настроек: ' + response.data.message, response.success ? 'success' : 'error');
            });
        }

        function showNotification(notification, message, type) {
            notification.innerHTML = message;
            notification.style.backgroundColor = (type === 'success') ? '#4CAF50' : '#f44336';
            notification.style.display = 'block';
            setTimeout(function () {
                notification.style.opacity = 0;
            }, 2000);
            setTimeout(function () {
                notification.style.display = 'none';
                notification.style.opacity = 1;
            }, 2500);
        }
    </script>
    <?php
}

function author_default_settings() {
    add_settings_section(
        'author_default_section',
        'Настройки авторов',
        'author_default_section_callback',
        'author_settings'
    );

    add_settings_field(
        'default_author',
        'Автор по умолчанию',
        'default_author_callback',
        'author_settings',
        'author_default_section'
    );

    register_setting(
        'author_settings_group',
        'default_author'
    );
}

function default_author_callback() {
    $default_author = get_option('default_author');
    $args = array(
        'post_type' => 'authors',
        'posts_per_page' => -1,
    );

    $authors = get_posts($args);
    ?>
    <div class="default-author-container">
        <style>
            .default-author-select {
                width: 300px;
            }
        </style>

        <div class="default-author-list">
            <div class="author-row">
                <select name="default_author" class="default-author-select">
                    <option value="0">Не выбран</option>
                    <?php foreach ($authors as $author) : ?>
                        <option value="<?php echo esc_attr($author->ID); ?>" <?php selected($default_author, $author->ID, true); ?>>
                            <?php echo esc_html($author->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <?php
}

add_action('admin_menu', 'add_default_author_dropdown_to_menu');
add_action('admin_init', 'author_default_settings');
add_action('wp_ajax_save_author_settings', 'save_author_settings_callback');

function save_author_settings_callback() {
    check_ajax_referer('save_author_settings', 'security');

    try {
        $default_author = isset($_POST['default_author']) ? intval($_POST['default_author']) : 0;
        update_option('default_author', $default_author);
        wp_send_json_success();
    } catch (Exception $e) {
        wp_send_json_error(array('message' => $e->getMessage()));
    }
}

//----------------------------------------
function clear_read_handler()
{
	if ( isset( $_SESSION['read_search'] ) )
	{
		unset( $_SESSION['read_search'] );
	}
	header( 'Location: ' . $_POST['back_addr'] );
	//header('Location: ' . 'http://urals.su/read/');
}
add_action( 'admin_post_nopriv_clear_read', 'clear_read_handler' );
add_action( 'admin_post_clear_read', 'clear_read_handler' );


function logout_current_user()
{
	wp_logout();

//    if(isset($_SESSION['current_page']))
//        wp_redirect($_SESSION['current_page']);
//    else
        wp_redirect(home_url());
}
add_action( 'admin_post_nopriv_userlogout', 'logout_current_user' );
add_action( 'admin_post_userlogout', 'logout_current_user' );

function login_user()
{
	if((!empty($_POST['user_login'])) && (!empty($_POST['user_pass'])))
	{
		$credos = [];
		$credos['user_login'] = $_POST['user_login'];
		$credos['user_password'] = $_POST['user_pass'];
		$credos['remember'] = false;

		$user = wp_signon( $credos, false );

		if ( is_wp_error($user) )
		{

			$_SESSION['userinfo']['error'] = $user->get_error_message();
		}
	}

//    if(isset($_SESSION['current_page']))
//        wp_redirect($_SESSION['current_page']);
//    else
    	wp_redirect(home_url());
	//header('Location: ' . 'http://urals.su/read/');
}
add_action( 'admin_post_nopriv_userlogin', 'login_user' );
add_action( 'admin_post_userlogin', 'login_user' );

add_action( 'init',
	static function() {
		if ( session_status() === PHP_SESSION_NONE )
		{
			session_start();
		}
	} );


$sysCore = new SystemCore();
add_action( 'wp_ajax_add_favorite', [ $sysCore, 'AddFavoriteAjax' ] );

//add_action( 'admin_post_nopriv_ask_us', [ $sysCore, 'AskUsPost' ] );
add_action( 'admin_post_ask_us', [ $sysCore, 'AskUsPost' ] );
add_action( 'admin_post_hotline_audit', [ $sysCore, 'HotlineAuditPost' ] );
add_action( 'admin_post_yurist_question', [ $sysCore, 'YuristQuestionPost' ] );
add_action( 'admin_post_nopriv_add_subscriber', [ $sysCore, 'AddSubscriber' ] );
add_action( 'admin_post_add_subscriber', [ $sysCore, 'AddSubscriber' ] );
add_action( 'admin_post_nopriv_test_subscribe', [ $sysCore, 'TestSubscribe' ] );
add_action( 'admin_post_nopriv_get_magazines', [ $sysCore, 'GetMagazines' ] );
add_action( 'admin_post_get_magazines', [ $sysCore, 'GetMagazines' ] );
//////////////////////////////////////////////////////////////////////////////
add_filter( 'tr_theme_options_name',
	static function() {
		return 'my_theme_options';
	} );
add_filter( 'tr_theme_options_page',
	static function() {
		return __DIR__ . "/my_theme_options.php";
	} );

///////////////////////////////////////////////////////////////////////////////
add_action( 'init', 'rewrite_rule_my' );
function rewrite_rule_my()
{
	add_rewrite_tag( '%archyear%', '([^&]+)' );
	add_rewrite_tag( '%mode%', '([^&]+)' );

	add_rewrite_rule( '^(arch)/([^/]*)/?', 'index.php?pagename=$matches[1]&archyear=$matches[2]', 'top' );
	add_rewrite_rule( '^(arhiv-nomerov)/([^/]*)/?', 'index.php?pagename=$matches[1]&archyear=$matches[2]', 'top' );
	add_rewrite_rule( '^(read)/free/?$', 'index.php?pagename=$matches[1]&mode=free', 'top' );
	add_rewrite_rule( '^(read)/free/page/([0-9]{1,})/?$', 'index.php?pagename=$matches[1]&mode=free&paged=$matches[2]', 'top' );

    //add_rewrite_rule( '^(search)/?$', 'index.php?search=&paged=$matches[1]', 'top' );
    add_rewrite_rule( '^search/page/([0-9]{1,})/?$', 'index.php?pagename=search&paged=$matches[1]', 'top' );
    add_rewrite_rule( 'search/(.+)/page/?([0-9]{1,})/?$', 'index.php?search=$matches[1]&paged=$matches[2]', 'top' );
    add_rewrite_rule( 'search/(.+)/?$', 'index.php?search=$matches[1]', 'top' );

	add_rewrite_rule( '^(section)/([^/]*)/([^/]*)/?$', 'index.php?section=$matches[3]', 'top' );
	add_rewrite_rule( '^(section)/([^/]*)/([^/]*)/page/([0-9]{1,})/?$', 'index.php?section=$matches[3]&paged=$matches[4]', 'top' );

	// news-fns
    //add_rewrite_rule( '^(inspection)/([^/]*)/([^/]*)/page/([0-9]{1,})/?$', 'index.php?inspection=$matches[3]&paged=$matches[4]', 'top' );
	add_rewrite_rule( '^news-fns/([^/]*)/?$', 'index.php?inspection=$matches[1]', 'top' );
	add_rewrite_rule( '^news-fns/([^/]*)/page/([0-9]{1,})/?$', 'index.php?inspection=$matches[1]&paged=$matches[2]', 'top' );
    add_rewrite_rule( '^news-fns/([^/]*)/([^/]*)/?$', 'index.php?news_fns=$matches[2]&inspection=$matches[1]', 'top' );
}

//////////////////////////////////////////////////////////////////////////////////////////////////
add_action( 'admin_init', 'disable_dashboard' );
function disable_dashboard()
{
	if ( ! is_user_logged_in() )
	{
		return null;
	}

	$allowed_actions = [
	    'userlogout',
        'read_search',
        'get_magazines',
        'clear_read',
        'ask_us',
        'hotline_audit',
	    'add_favorite'
    ];

	//if( ($user == null) || (in_array( 'ab_subscriber', $user->roles, true )) )
	if ( current_user_can( SharedConst::SUBSCRIBER_ROLE ) )
	{
		if(empty($_POST['action']) || (!in_array($_POST['action'], $allowed_actions, true) ) )
		{
			wp_redirect( home_url() );
			exit;
		}
	}
}
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (current_user_can( SharedConst::SUBSCRIBER_ROLE))
	{
		show_admin_bar(false);
	}
}


// привязываем последний журнал к новой статье
function set_default_meta_for_article($post_ID, $post, $update)
{
	if($post->post_type === 'article')
	{
		$links = get_post_meta($post_ID, 'art_links');
		if(empty($links))
		{
			$args = [
				'post_type' => 'magazine',
				'numberposts' => 1,
				'orderby' => 'ID',
				'order' => 'DESC',
				'post_status' => 'publish',
				'suppress_filters' => true
			];

			$mag = (new PostQuery($args))->get_posts()[0];
			if(!empty($mag))
			{
				add_post_meta( $post_ID, 'art_links', [ 0 => $mag->ID ] );
				add_post_meta( $post_ID, 'art_date', date('d.m.Y') );
			}
		}
	}
    elseif($post->post_type === 'bulletinsarticle')
    {
        $links = get_post_meta($post_ID, 'bart_links');
        if(empty($links))
        {
            $args = [
                'post_type' => 'bulletin',
                'numberposts' => 1,
                'orderby' => 'ID',
                'order' => 'DESC',
                'post_status' => 'publish',
                'suppress_filters' => true
            ];

            $mag = (new PostQuery($args))->get_posts()[0];
            if(!empty($mag))
            {
                add_post_meta( $post_ID, 'bart_links', [ 0 => $mag->ID ] );
                add_post_meta( $post_ID, 'bart_date', date('d.m.Y') );
            }
        }
    }
	return $post_ID;
}
add_action('wp_insert_post','set_default_meta_for_article', 10, 3);

//
//add_theme_support( 'title-tag' );
//
//add_filter( 'document_title_parts', static function( $parts ){
//	if( isset($parts['site']) )
//		unset($parts['site']);
//
//	return $parts;
//});


function react2wp_update_publishing_date() {
 $nonce = wp_nonce_field( '_cj_update_publishing_date', '_cj_update_publishing_date_nonce', TRUE, FALSE);
 ?>
  <div class="misc-pub-section cj-update-publishing-date">
    <label for="cj-update-publishing-date">
      <input type="checkbox" id="cj-update-publishing-date" name="cj_update_publishing_date" value="1" />
      <strong>Убрать дату публикации</strong>
       <?php echo $nonce ?>
    </label>
  </div>
<?
 }
add_action( 'post_submitbox_misc_actions', 'react2wp_update_publishing_date' );
function react2wp_update_publishing_date__save( $post_id ){
   if ( wp_is_post_revision( $post_id ) ) {
      return;
   }
   if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return;
   }
   if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
   }
   // ЕСЛИ ФЛАЖОК НЕ УСТАНОВЛЕН
   if ( ! isset ( $_POST[ 'cj_update_publishing_date' ] ) ) {
      return;
   }
   if ( ! wp_verify_nonce( $_POST[ '_cj_update_publishing_date_nonce' ], '_cj_update_publishing_date' )){
      return;
   }
   $time = current_time('mysql');
   remove_action('save_post', 'react2wp_update_publishing_date__save');
   wp_update_post(
      array (
         'ID'            => $post_id, // ID записи для обновления
         'post_date'     => $time,
         'post_date_gmt' => get_gmt_from_date( $time )
      )
   );
   add_action( 'save_post', 'react2wp_update_publishing_date__save' );
} 
add_action( 'save_post', 'react2wp_update_publishing_date__save', 10, 2 );



// function change_breadcrumbs($data) {
// 	unset($data->breadcrumbs[1]);

// 	return $data;
// }
// add_action('bcn_after_fill', 'change_breadcrumbs');


//--------------------------------------------->

// Добавление шорткода для авторов статей

function post_author_shortcode($atts) {
    $atts = shortcode_atts(array(
        'ids' => '',
    ), $atts);

    $post_ids = explode(',', $atts['ids']);
    $output = '';

    foreach ($post_ids as $post_id) {
        $post_id = intval($post_id);
        if ($post_id > 0) {
            $post = get_post($post_id);

            if ($post && $post->post_type === 'authors') {
                $full_name = get_post_meta($post_id, 'full_name', true);
                $author_photo_id = get_post_meta($post_id, 'photo', true);

                $author_photo_url = '';
                if ($author_photo_id) {
                    $author_photo_url = wp_get_attachment_image_src($author_photo_id, 'full')[0];
                }

                $author_page_url = get_permalink($post_id);

                $caption_text = ($output === '') ? 'Автор' : 'При участии';

                $output .= '<div class="shortcode-author-info">';
                if ($author_photo_url) {
                    $output .= '<div class="author-photo-container">';
                    $output .= '<div class="author-photo-circle">';
                    $output .= '<a href="' . esc_url($author_page_url) . '"><img class="author-photo shortcode-author-image" src="' . esc_url($author_photo_url) . '" alt="' . esc_attr($full_name) . '" style="width: 85px; height: 85px;"></a>';
                    $output .= '</div>';
                    $output .= '</div>';
                }
                $output .= '<div class="author-details">';
                $output .= '<div class="author-role">' . esc_html($caption_text) . '</div>';
                if ($full_name) {
                    $output .= '<div class="author-name"><a href="' . esc_url($author_page_url) . '" class="custom-author-link">' . esc_html($full_name) . '</a></div>';
                }
                $output .= '</div>';
                $output .= '</div>';
            }
        }
    }

    if ($output === '') {
        $output = 'Авторы не найдены для указанных номеров постов';
    }

    $output = '<div class="authors-container">' . $output . '</div>';

    return $output;
}

add_shortcode('post_author', 'post_author_shortcode');


//--------------------------------------------->
//array('news', 'BulletinsArticle', 'article'),
//if (is_single() && (strpos($_SERVER['REQUEST_URI'], '/barticles/') !== false || get_post_type() === 'news' || get_post_type() === 'article')) {
// Добавление метабокса с авторами в панель новостей
function add_author_metabox() {
    add_meta_box(
        'author_metabox',
        'Выбор авторов',
        'author_metabox_content',
        array('BulletinsArticle', 'article'),
        'side',
        'default'
    );
}

function author_metabox_content($post) {
    $all_authors = get_posts(array('post_type' => 'authors', 'posts_per_page' => -1));

    $news_authors = get_post_meta($post->ID, 'news_authors', true);

    $news_authors = !empty($news_authors) ? $news_authors : array();

    $default_author = get_option('default_author');

    $checkboxes = array();

    foreach ($all_authors as $author) {
        $checked = (in_array($author->ID, $news_authors) || $author->ID == $default_author) ? 'checked' : '';
        $checkboxes[$author->ID] = "<label><input type='checkbox' name='news_authors[]' value='{$author->ID}' {$checked}> {$author->post_title}</label><br>";
    }

    if (isset($checkboxes[$default_author])) {
        $default_checkbox = $checkboxes[$default_author];
        unset($checkboxes[$default_author]);
        array_unshift($checkboxes, $default_checkbox);
    }

    echo implode('', $checkboxes);
}

function save_news_authors($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $news_authors = isset($_POST['news_authors']) ? array_map('intval', $_POST['news_authors']) : array();

    update_post_meta($post_id, 'news_authors', $news_authors);
}

function setup_author_metabox() {
    add_action('add_meta_boxes', 'add_author_metabox');
    add_action('save_post', 'save_news_authors');
}

setup_author_metabox();

//----------------->

// Фильтр для добавления информации об авторах в конец статьи, статьи бюллетеня и //новости
function append_authors_to_content($content) {
    global $post;

    if (is_single() && (strpos($_SERVER['REQUEST_URI'], '/barticles/') !== false || get_post_type() === 'article')) {
        $authors = get_post_meta($post->ID, 'news_authors', true);

        if (!empty($authors)) {
            $authors_ids_string = implode(',', $authors);
            $shortcode_output = do_shortcode("[post_author ids='{$authors_ids_string}']");
            $content .= $shortcode_output;
        }
    }

    return $content;
}

add_filter('the_content', 'append_authors_to_content');

//--------------------------------------------->
// Отправка формы подписной страницы чатботу

function send_telegram_message($name, $phone) {
    $botToken = '6564796674:AAHbCOliyXqxTI9yZ2hw_0nrgL15gYkZHxI'; // Токен
    $chatIds = array(5054031403, 1303622729, 2137061528, 1385319083); // ID

    $message = "Новая заявка!\nИмя: $name\nТелефон: $phone";

    foreach ($chatIds as $chatId) {
        $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message);
        $response = file_get_contents($url);
    }

    return true;
}

function handle_telegram_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['phone'])) {
        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);

        send_telegram_message($name, $phone);

        wp_send_json_success('Форма успешно отправлена!');
    } else {
        wp_send_json_error('Ошибка обработки формы!');
    }
}

add_action('wp_ajax_nopriv_telegram_form_submission', 'handle_telegram_form_submission');
add_action('wp_ajax_telegram_form_submission', 'handle_telegram_form_submission');

//--------------------------------------------->
// Настройки коментариев

function my_update_comment_data($commentdata) {
    if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
        $user_id = VerifyToken($_COOKIE['token']);
        if ($user_id) {
            $user_info = get_userdata($user_id);
            // Замените поля комментария на данные из плагина авторизации
            $commentdata['comment_author'] = $user_info->first_name . ' ' . $user_info->last_name;
            $commentdata['comment_author_email'] = $user_info->user_email;
        }
    }
    return $commentdata;
}
add_filter('preprocess_comment', 'my_update_comment_data');

