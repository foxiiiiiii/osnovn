<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

use AmbExpress\Controllers\CommonController;
use AmbExpress\dto\DTO_Search;
use AmbExpress\ViewModels\ArticleViewModel;
use AmbExpress\ViewModels\bulletin\BullArticleViewModel;
use AmbExpress\ViewModels\MagazinesViewModel;
use AmbExpress\ViewModels\NewsViewModel;
use AmbExpress\ViewModels\SearchViewModel;
use AmbExpress\ViewModels\SectionsViewModel;
use AmbExpress\ViewModels\SubpagesViewModel;
use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

$url = $_SERVER['REQUEST_URI'];

get_header(); ?>
<?php require_once 'inc/left_sidebar.php'; ?>

<?php
$context = [];
$context['only_reg'] = get_post_meta(get_the_id(), 'page_regonly', true);
if(isset($_COOKIE["token"])) {
  $user_id = VerifyToken($_COOKIE["token"]);
  $userdata = get_userdata($user_id);

  if($userdata) {
    $subscribe_end_compare = new DateTime(get_user_meta($userdata->ID, 'subscribe_end', true));

    $current_date = date('d.m.Y');
    $context['access'] = 'denied';
    if(new DateTime() < $subscribe_end_compare) {
      $context['access'] = 'accepted';
    }
  } else {
    $context['access'] = 'unauthed';
  }
} else {
  $context['access'] = 'unauthed';
}


if (function_exists('bcn_display')) {
    $context['breadcrumbs'] = bcn_display(true);
}
if (is_page('glavnaya')) {
    $context['page'] = Timber::get_post();

    $magazine = new MagazinesViewModel();

    $context['last'] = $magazine->GetLastMagazine();

    // news
    $vmNews = new NewsViewModel();
    $context['news'] = $vmNews->LoadNews(5);

    // articles
    $args = [
        'post_type' => 'article',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => [
            [
                "key" => "art_links",
                "value" => $context['last']->ID,
                "compare" => "LIKE",
            ]
        ],
    ];
    //$context['articles'] = ( new PostQuery( $args ) )->get_posts();
    $context['articles'] = $magazine->GetArticles($context['last']->ID, false);
    $context['imparticles'] = $magazine->GetImportanceArticles($context['last']->ID);
    $context['main'] = $magazine->GetMainArticle($context['last']->ID);

    //Timber::render( 'glavnaya_twig.twig', $context );

    ?>
    <div class="center-block site-content-right">
        <?php


        Timber::render('glavnaya.twig', $context);


        ?>

        <?php if (get_field('actual')): ?>
            <div id="actuals">
                <h2>Актуальные статьи</h2>
                <div class="actuals">
                    <?php while (has_sub_field('actual')): ?>
                        <div class="actual-item">
                            <div class="actual-block1">
                                <a href="<?php the_sub_field('link'); ?>">
                                    <?php
                                    $image = get_sub_field('image');
                                    if (!empty($image)): ?>
                                        <img src="<?php echo $image['url']; ?>" alt="<?php the_sub_field('name'); ?>"/>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="actual-block2">
                                <div class="actual-title"><a
                                            href="<?php the_sub_field('link'); ?>"><?php the_sub_field('name'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>


    </div>
    <?php


} elseif (is_page('glavnaya-test')) {
    $context['page'] = Timber::get_post();

    $magazine = new MagazinesViewModel();

    $context['last'] = $magazine->GetLastMagazine();

    // news
    $vmNews = new NewsViewModel();
    $context['news'] = $vmNews->LoadNews(5);
    //$context['fnsnews'] = $vmNews->LoadNews( 5, true );

    // articles
    $args = [
        'post_type' => 'article',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => [
            [
                "key" => "art_links",
                "value" => $context['last']->ID,
                "compare" => "LIKE",
            ]
        ],
    ];
    //$context['articles'] = ( new PostQuery( $args ) )->get_posts();
    $context['articles'] = $magazine->GetArticles($context['last']->ID, false);
    $context['imparticles'] = $magazine->GetImportanceArticles($context['last']->ID);
    $context['main'] = $magazine->GetMainArticle($context['last']->ID);

    Timber::render('glavnaya_twig.twig', $context);
} else if (is_page('search'/*read*/)) {
    $dto = new DTO_Search();

    if (isset($_SESSION['read_search']['type'])) {
        $dto->Type = $_SESSION['read_search']['type'];
    }

    $searchVM = new SearchViewModel();
    $context['vm'] = $searchVM;

    reset($searchVM->Selected);
    if(!empty($_SESSION['read_search']['section']))
        $searchVM->Selected['section'] = $_SESSION['read_search']['section'];
    if(!empty($_SESSION['read_search']['onlyFree']))
        $searchVM->Selected['onlyFree'] = $_SESSION['read_search']['onlyFree'];
    if(!empty($_SESSION['read_search']['mag_num']))
        $searchVM->Selected['mag_num'] = $_SESSION['read_search']['mag_num'];
    if(!empty($_SESSION['read_search']['year']))
        $searchVM->Selected['year'] = $_SESSION['read_search']['year'];
    if(!empty($_SESSION['read_search']['search_text']))
        $searchVM->Selected['search_text'] = $_SESSION['read_search']['search_text'];

    $searchVM->InSearch = (!empty($_SESSION['read_search']['section'])
        || (!empty($_SESSION['read_search']['onlyFree']))
        || (!empty($_SESSION['read_search']['mag_num']))
        || (!empty($_SESSION['read_search']['year']))
        || (!empty($_SESSION['read_search']['search_text'])));

    $searchVM->LoadData($dto);

    $context['action'] = esc_url(admin_url('admin-post.php'));
    $context['clear_action'] = esc_url(admin_url('admin-post.php'));
    $context['back_addr'] = get_permalink();

    Timber::render('chitalka/chitalka.twig', $context);

    wp_reset_postdata();
} else if (is_page('byulleten')) {

    $context['page'] = Timber::get_post();
    Timber::render('pages/byulleten.twig', $context);
} else if (is_page('arch')) {
    $meta_year = [];

    $context['page'] = Timber::get_post();

    $searchText = '';
    $currentYear = date("Y");

    global $wp_query;
    if (isset($wp_query->query_vars['archyear'])) {
        $context['arch']['year'] = get_query_var('archyear');

        if (!is_numeric($context['arch']['year'])) {
            $context['arch']['year'] = $currentYear;
        }
    } else {
        $context['arch']['year'] = $currentYear;
    }


    $args = [
        'post_type' => 'magazine',
        'numberposts' => -1,
        'meta_query' => [
            [
                "key" => "release_date_from",
                "value" => $context['arch']['year'],
                "compare" => "LIKE",
            ]
        ],
        //'orderby'     => [ 'post_date' => 'DESC' , 'common_number' => 'DESC']
        'meta_key' => 'common_number',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ];
    $context['magazines'] = Timber::get_posts($args);

    foreach ($context['magazines'] as $magazine) {
        $args = [
            'post_type' => 'article',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => [
                [
                    "key" => "art_links",
                    "value" => $magazine->ID,
                    "compare" => "LIKE",
                ]
            ],
            //			'posts_per_page' => 30
        ];
        $query = new PostQuery($args);
        $magazine->custom['articles'] = $query->get_posts();
        wp_reset_postdata();
    }

    $context['action'] = esc_url(admin_url('admin-post.php'));
    $context['back_addr'] = get_permalink();
    $context['years'] = ArticleViewModel::GetArticleDistinctYears();


    Timber::render('arch/arch.twig', $context);

} else if (is_page('arhiv-nomerov')) {

    $meta_year = [];

    $context['page'] = Timber::get_post();

    $searchText = '';
    $currentYear = date("Y");

    global $wp_query;
    if (isset($wp_query->query_vars['archyear'])) {
        $context['arch']['year'] = get_query_var('archyear');

        if (!is_numeric($context['arch']['year'])) {
            $context['arch']['year'] = $currentYear;
        }
    } else {
        $context['arch']['year'] = $currentYear;
    }

    $args = [
        'post_type' => 'bulletin',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_query' => [
            [
                "key" => "bull_release_date_from",
                "value" => $context['arch']['year'],
                "compare" => "LIKE",
            ]
        ],
//        's' => $searchBlock,
        'meta_key' => 'bull_common_number',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ];

    $context['bulletins'] = Timber::get_posts($args);
//    echo "<pre>";
//    print_r($context['bulletins']);
//    echo "</pre>";


    foreach ($context['bulletins'] as $magazine) {
        $args = [
            'post_type' => 'bulletinsarticle',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => [
                [
                    "key" => "bart_links",
                    "value" => $magazine->ID,
                    "compare" => "LIKE",
                ]
            ],
            //			'posts_per_page' => 30
        ];
        $query = new PostQuery($args);
        $magazine->custom['barticles'] = $query->get_posts();
        wp_reset_postdata();
    }

    $context['action'] = esc_url(admin_url('admin-post.php'));
    $context['back_addr'] = "/search";
    $yearsArch = BullArticleViewModel::GetBulletinsDistinctYears();

    if(!in_array($currentYear, $yearsArch))
        array_unshift($yearsArch, 2023);
    $context['years'] = $yearsArch;

    Timber::render('arch/arch_bull.twig', $context);
} else if (is_page('favorites')) {
    $args = [
        'post_status' => 'publish',
        'numberposts' => -1,
    ];
    $context['post'] = Timber::get_post();
    $context['subpages'] = SubpagesViewModel::GetSubPages($context['post']->ID);
    $context['logged'] = (is_user_logged_in());
    $context['base_url'] = get_stylesheet_directory_uri();

    $regOnly = get_post_meta($context['post']->ID, 'page_regonly', true);

    $context['ronly'] = $regOnly;

    if (is_user_logged_in()) {
        $user = wp_get_current_user();

        $vm = new \AmbExpress\ViewModels\FavoriteViewModel();
        $context['favorites'] = $vm->GetUserFavArticles($user->ID);
    }

    Timber::render('subscribe/favorites.twig', $context);
} else if (is_page('ask-us')) {
    $controller = new CommonController('ask_us', 'fio_form', 'pages/common_content.twig');
    if (isset($_SESSION['ask_us'])) {
        $controller->SetMessage($_SESSION['ask_us']['message']);
        unset($_SESSION['ask_us']);
    }

    $controller->RenderView();
} else if (is_page('news-fns')) {
    $context['inspections'] = get_terms('inspection', ['hide_empty' => false]);
    //wp_reset_postdata();

    Timber::render('news/news_fns.twig', $context);
} else if (is_page('recovery')) {
    $context['action'] = esc_url(admin_url('admin-post.php'));
    $context['post'] = Timber::get_post();

    Timber::render('pages/recovery.twig', $context);
} else if (is_page('our-subscribe')) {
    $context['post'] = Timber::get_post();

    $context['action_addr'] = esc_url(admin_url('admin-post.php'));
    if (isset($_SESSION['emailsubscribe'])) {
        $context['flash_message'] = $_SESSION['emailsubscribe']['message'];
        unset($_SESSION['emailsubscribe']);
    }

    Timber::render('pages/subscribe.twig', $context);
} else if (is_page('probnaya-podpiska')) {
    $context['post'] = Timber::get_post();

    $context['action_addr'] = esc_url(admin_url('admin-post.php'));
    if (isset($_SESSION['testsubscribe'])) {
        $context['flash_message'] = $_SESSION['testsubscribe']['message'];
        unset($_SESSION['testsubscribe']);
    }

    Timber::render('pages/test-subscribe.twig', $context);
} else if (is_page('hotlines-with-auditors')) {
    $controller = new CommonController('hotline_audit', 'fio_form', 'pages/common_content.twig');

    if (isset($_SESSION['hotline_audit_us'])) {
        $controller->SetMessage($_SESSION['hotline_audit_us']['message']);
        unset($_SESSION['hotline_audit_us']);
    }

    $controller->RenderView();
} else if (is_page('zadat-vopros-yuristam')) {
    $controller = new CommonController('yurist_question', 'fio_form', 'pages/common_content.twig');

    if (isset($_SESSION['yurist_question'])) {
        $controller->SetMessage($_SESSION['yurist_question']['message']);
        unset($_SESSION['yurist_question']);
    }

    $controller->RenderView();
} else if (is_page('site-search')) {
    $context = [];
    $context['searchform'] = get_search_form();

    Timber::render('pages/site-search.twig', $context);
} else if (strpos($url, 'auth')) {
  ?>
  <div class="center-block site-content-right">
    <main role="main">
      <div class="breadcrumbs">
        <span class="post post-page current-item">Авторизация</span>
      </div>
      <h1>Вход в личный кабинет</h1>
      <hr style="border-bottom: 1px solid #006ab4; margin:5px 0 15px 0;">
      
      <?php echo do_shortcode( '[authentication]' ); ?>
    
    </main>
  </div>
  <?php
} else if (strpos($url, 'account')) {
  ?>
  <div class="center-block site-content-right">
    <main role="main">
      <div class="breadcrumbs">
        <span class="post post-page current-item">Личный кабинет</span>
      </div>
      <h1>Мой аккаунт</h1>
      <hr style="border-bottom: 1px solid #006ab4; margin:5px 0 15px 0;">
      
      <?php echo do_shortcode( '[account]' ); ?>
    
    </main>
  </div>
  <?php
} else if (strpos($url, 'kalkulyatory')) {
  $controller = new CommonController('', '', 'pages/common_content.twig');
  $controller->RenderView();
} else {
  $only_reg = boolval(get_post_meta(get_the_id(), 'page_regonly', true));
  $childs = get_children(array( 'post_parent' => get_the_ID() ));
  // echo '<pre>';
  // print_r($context);
  // echo '</pre>';
  if(count($childs) != 0) {
    $controller = new CommonController('', '', 'pages/common_content.twig');
    $controller->RenderView();
  } else {
    if(!$only_reg) {
      $controller = new CommonController('', '', 'pages/common_content.twig');
      $controller->RenderView();
    } else {
      _get_the_content($context);
    }
  }
  
}
?>

    <div class="clear"></div>
<?php if ((is_front_page()) and (!is_paged())) { ?>


    <?php if (get_field('mobyes', 11)): ?>
        <?php while (has_sub_field('mobyes', 11)): ?>
            <div class="reklam_mobyes">
                <?php if (get_sub_field("link", 11)): ?>
                <a href="<?php the_sub_field('link', 11); ?>">
                    <?php endif; ?>
                    <?php
                    $image = get_sub_field('image', 11);
                    if (!empty($image)): ?>
                        <img src="<?php echo $image['url']; ?>"/>
                    <?php endif; ?>
                    <?php if (get_sub_field("link", 11)): ?>
                </a>
            <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>


<?php } ?>

    <div class="clear"></div>

<?php get_footer(); ?>