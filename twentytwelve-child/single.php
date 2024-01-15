<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 */

use AmbExpress\infrastructure\UserService;
use AmbExpress\ViewModels\BulletinViewModel;
use AmbExpress\ViewModels\FavoriteViewModel;
use AmbExpress\ViewModels\MagazinesViewModel;
use Timber\Post;
use Timber\Timber;

get_header(); ?>


<?php require_once 'inc/left_sidebar.php'; ?>


<?php
$context = [];
$user_id = '';

if (isset($_COOKIE['token'])) {
    $user_id = VerifyToken($_COOKIE['token']);
    $context['current_user_authed'] = true;
} else {
    $context['current_user_authed'] = false;
}

if (function_exists('bcn_display')) {
    $context['breadcrumbs'] = bcn_display(true);
}

if (is_singular('bulletinsarticle')) {
    $context['barticle'] = Timber::get_post();

    if (isset($context['barticle']->custom['bart_links'])) {
            $context['bulletin'] = Timber::get_post($context['barticle']->custom['bart_links'][0]);
        }

        $date = date_create($context['barticle']->release_date_from);
        $context['date_text'] = date_format($date, 'F Y');

        $args = [
            'taxonomy' => 'sectionbull',
            'numberposts' => -1,
        ];
        $context['terms'] = (new Post($context['barticle']->ID))->terms($args);

        $newPost = get_previous_post();
        if (!empty($newPost)) {
            $context['prevNew'] = get_post_permalink($newPost->ID);
        }

        $newPost = get_next_post();
        if (!empty($newPost)) {
            $context['nextNew'] = get_post_permalink($newPost->ID);
        }

        $context['can_see'] = UserService::HasRightToViewHiddenDocuments($context['barticle']);
        $vm = new FavoriteViewModel();
        $is_fav = $vm->IsArticleAdded($context['barticle']->ID);

        $context['fav'] = get_stylesheet_directory_uri() . ($is_fav ? '/images/fav.png' : '/images/fav_empty.png');

        // // Ссылки в хлебных крошках для статей биллютеня
        define('HOME_URL', 'https://ab-express.ru/');
        define('ARCHIVE_URL', 'https://ab-express.ru/arhiv-nomerov/');
        $context['breadcrumbs'] = '';

        $context['breadcrumbs'] .= '<span property="itemListElement" typeof="ListItem">
            <a property="item" typeof="WebPage" title="Журнал АБ-Экспресс" href="' . HOME_URL . '" class="home">
                <span property="name">Журнал АБ-Экспресс</span>
            </a>
            <meta property="position" content="1">
        </span>
        <i class="fa fa-angle-right" aria-hidden="true"></i>';

        $context['breadcrumbs'] .= '<span property="itemListElement" typeof="ListItem">
            <a property="item" typeof="WebPage" title="Бюллетени" href="' . ARCHIVE_URL . '" class="home">
                <span property="name">Бюллетени</span>
            </a>
            <meta property="position" content="2">
        </span>
        <i class="fa fa-angle-right" aria-hidden="true"></i>';

        if (isset($context['bulletin'])) {
            $context['breadcrumbs'] .= '<span property="itemListElement" typeof="ListItem">
                <a property="item" typeof="WebPage" title="' . $context['bulletin']->post_title . '" href="' . $context['bulletin']->link . '" class="home">
                    <span property="name">' . $context['bulletin']->post_title . '</span>
                </a>
                <meta property="position" content="3">
            </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>';
        }

        $context['breadcrumbs'] .= '<span property="itemListElement" typeof="ListItem">
            <span property="item" typeof="WebPage" title="' . $context['barticle']->post_title . '" class="home">
                <span property="name">' . $context['barticle']->post_title . '</span>
            </span>
            <meta property="position" content="4">
        </span>';

        Timber::render('articles/barticle.twig', $context);
        if (comments_open() || get_comments_number()) {
            comments_template();
        }

} else if (is_singular('article')) {
    $context['article'] = Timber::get_post();

    if (isset($context['article']->custom['art_links'])) {
            $context['magazine'] = Timber::get_post($context['article']->custom['art_links'][0]);
        }

        $date = date_create($context['article']->release_date_from);
        $context['date_text'] = date_format($date, 'F Y');

        $args = [
            'taxonomy' => 'section',
            'numberposts' => -1,
        ];
        $context['terms'] = (new Post($context['article']->ID))->terms($args);

        $newPost = get_previous_post();
        if (!empty($newPost)) {
            $context['prevNew'] = get_post_permalink($newPost->ID);
        }

        $newPost = get_next_post();
        if (!empty($newPost)) {
            $context['nextNew'] = get_post_permalink($newPost->ID);
        }

        $context['can_see'] = UserService::HasRightToViewHiddenDocuments($context['article']);
        $vm = new FavoriteViewModel();
        $is_fav = $vm->IsArticleAdded($context['article']->ID);

        $context['fav'] = get_stylesheet_directory_uri() . ($is_fav ? '/images/fav.png' : '/images/fav_empty.png');

        // Ссылки в хлебных крошках для статей журнала
        define('HOME_URL', 'https://ab-express.ru/');
        define('ARCHIVE_URL', 'https://ab-express.ru/arch/');

        if (isset($context['breadcrumbs']) && isset($context['magazine']) && $context['article']->post_title !== $context['magazine']->post_title) {
            $newBreadcrumbsItem = '<span property="itemListElement" typeof="ListItem">
                <a property="item" typeof="WebPage" title="Перейти к ' . $context['magazine']->post_title . '." href="/magazine/' . $context['magazine']->slug . '" class="home">
                    <span property="name">' . $context['magazine']->post_title . '</span>
                </a>
                <meta property="position" content="1">
            </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>';
            $context['breadcrumbs'] = str_replace('</i>', '</i>' . $newBreadcrumbsItem, $context['breadcrumbs']);
        }

        if (isset($context['breadcrumbs']) && isset($context['magazine']) && isset($context['article'])) {
            if (is_singular('magazine') && $context['article']->post_type === 'magazine') {
                $context['breadcrumbs'] = '';
            } else {
                $archiveBreadcrumbs = '<span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" title="Журнал АБ-Экспресс" href="' . HOME_URL . '" class="home">
                        <span property="name">Журнал АБ-Экспресс</span>
                    </a>
                    <meta property="position" content="1">
                </span>
                <i class="fa fa-angle-right" aria-hidden="true"></i>
                <span property="itemListElement" typeof="ListItem">
                    <a property="item" typeof="WebPage" title="Архив номеров" href="' . ARCHIVE_URL . '" class="home">
                        <span property="name">Архив номеров</span>
                    </a>
                    <meta property="position" content="2">
                </span>
                <i class="fa fa-angle-right" aria-hidden="true"></i>';

            $newBreadcrumbsItem = '<span property="itemListElement" typeof="ListItem">
                <a property="item" typeof="WebPage" title="Перейти к ' . $context['magazine']->post_title . '." href="' . get_permalink($context['magazine']->ID) . '" class="home">
                    <span property="name">' . $context['magazine']->post_title . '</span>
                </a>
                <meta property="position" content="3">
            </span>
            <i class="fa fa-angle-right" aria-hidden="true"></i>
            <span property="itemListElement" typeof="ListItem">
                <span property="item" typeof="WebPage" title="' . $context['article']->post_title . '" class="home">
                    <span property="name">' . $context['article']->post_title . '</span>
                </span>
                <meta property="position" content="4">
            </span>';

            $context['breadcrumbs'] = $archiveBreadcrumbs . $newBreadcrumbsItem;
        }
    }

    $context['action_addr'] = esc_url(admin_url('admin-post.php'));

    $isBot = preg_match('/[b|B]ot|crawl|slurp|spider|[Y|y]andex/i', $_SERVER['HTTP_USER_AGENT']);
    $free_count_characters = get_field('free_count_characters', $context['article']->ID);
    $context['abonent'] = [
        'free_count_characters' => (!empty($free_count_characters)) ? $free_count_characters : 80,
        'isBot' => $isBot,
        'instruction_title' => Timber::get_post(44704)->post_title,
        'instruction' => Timber::get_post(44704)->content,
    ];
    if(isset($_SESSION['userinfo']['error']))
    {
        $context['abonent']['error'] = $_SESSION['userinfo']['error'];
        unset($_SESSION['userinfo']['error']);
    }

    Timber::render('articles/article.twig', $context);
    if (comments_open() || get_comments_number()) {
        comments_template();
    }

} elseif (is_singular('news'))
{
    $args = [
        'taxonomy' => 'section',
        'numberposts' => -1,
    ];
    $context['is_logged'] = is_user_logged_in();
    $context['sections'] = (new Post($context['article']->ID))->terms($args);
    $context['news'] = Timber::get_post();
    $context['prev'] = get_previous_post_link('%link', 'предыдущая');
    $context['next'] = get_next_post_link('%link', 'следующая');
    Timber::render('news/single_news.twig', $context);
    if (comments_open() || get_comments_number()) {
        comments_template();
    }

}
elseif(is_singular('news_fns'))
{
    $context['news'] = Timber::get_post();
    $context['prev'] = get_previous_post_link('%link', 'предыдущая');

    $needle = '?news_fns=';
    $pos = strpos($context['prev'], $needle);
    if($pos !== false)
    {
        $context['prev'] = '<a href="/news-fns/'.(get_query_var('inspection')).'/'.substr($context['prev'], $pos + strlen($needle));
    }

    $context['next'] = get_next_post_link('%link', 'следующая');
    $pos = strpos($context['next'], $needle);
    if($pos !== false)
    {
        $context['next'] = '<a href="/news-fns/'.(get_query_var('inspection')).'/'.substr($context['next'], $pos + strlen($needle));
    }

    Timber::render('news/single_news.twig', $context);
} elseif (is_singular('magazine')) {
    $context['magazine'] = Timber::get_post();
    $context['posts'] = (new MagazinesViewModel())->GetArticles($context['magazine']->ID);
    $context['image_journal_id'] = get_field('image_journal');
    $context['image_journal'] = new TimberImage($context['image_journal_id']);

    foreach ($context['posts'] as &$post) {
        // Извлечение первых двух предложений из контента статьи
        $content = strip_tags($post->post_content);
        $content_without_shortcodes = strip_shortcodes($content); // Удаление шорткодов

        $sentences = preg_split('/(?<=[.!?])\s+/', $content_without_shortcodes, -1, PREG_SPLIT_NO_EMPTY);
        
        if (count($sentences) > 0) {
            $post->first_sentence = $sentences[0] . (count($sentences) > 1 ? ' ' . $sentences[1] : '') . '...';
        } else {
            $post->first_sentence = '';
        }
    }

    Timber::render('magazine/magazine.twig', $context);
    
} elseif (is_singular('bulletin')) {
    $context['bulletin'] = Timber::get_post();
    $context['posts'] = (new BulletinViewModel())->GetArticles($context['bulletin']->ID);
    $context['image_bulletins_id'] = get_field('image_bulletins');
    $context['image_bulletins'] = new TimberImage($context['image_bulletins_id']);

    foreach ($context['posts'] as &$post) {
        // Извлечение первых двух предложений из контента биллютени
        $content = strip_tags($post->post_content);
        $sentences = preg_split('/(?<=[.!?])\s+/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        if (count($sentences) > 0) {
            $post->first_sentence = $sentences[0] . (count($sentences) > 1 ? ' ' . $sentences[1] : '') . '...';
        } else {
            $post->first_sentence = '';
        }
    }

    Timber::render('bulletin/bulletin.twig', $context);
}
?>
<?php get_footer(); ?>

