<?php use Timber\Timber;

get_header(); ?>
<?php require_once 'inc/left_sidebar.php'; ?>

<?php
$context = [];
$context['searchform'] = get_search_form();
$context['results'] = Timber::get_posts();
Timber::render('pages/site-search.twig', $context);
?>

<?php get_footer(); ?>

