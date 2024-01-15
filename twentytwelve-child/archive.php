<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Twelve already
 * has tag.php for Tag archives, category.php for Category archives, and
 * author.php for Author archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

use Timber\PostQuery;
use Timber\Timber;

get_header(); ?>
<?php require_once 'inc/left_sidebar.php'; ?>

<?php
$context             = [];
if(function_exists('bcn_display'))
	$context['breadcrumbs'] = bcn_display(true);

if ( is_post_type_archive( 'article' ) )
{
	$args                = [
		'post_type'   => 'article',
		'post_status' => 'publish',
		'numberposts' => - 1
	];
	$context['articles'] = Timber::get_posts( $args );

	Timber::render( 'articles/articles.twig', $context );
} elseif ( is_post_type_archive( 'news' ) )
{
	$args            = [
		'post_type'   => 'news',
		'post_status' => 'publish',
		'posts_per_page' => 10,
		'paged' => get_query_var( 'paged' ),
	];
	$context['news'] = Timber::get_posts($args);
	$newCollectionQuery = new PostQuery( $args );
	$context['paginat'] = $newCollectionQuery->pagination();
	wp_reset_postdata();

	Timber::render( 'news/news.twig', $context );
}elseif (is_post_type_archive('news_fns'))
{
//	$args            = [
//		'post_type'   => 'news_fns',
//		'post_status' => 'publish',
//		'posts_per_page' => 10,
//		'paged' => get_query_var( 'paged' ),
//	];
//	$context['news'] = Timber::get_posts($args);
//	$newCollectionQuery = new PostQuery( $args );
//	$context['paginat'] = $newCollectionQuery->pagination();
//	wp_reset_postdata();

	$context['inspections'] = get_terms( 'inspection', [ 'hide_empty' => false ] );
	//wp_reset_postdata();

	Timber::render( 'news/news_fns.twig', $context );
}
elseif ( is_post_type_archive( 'magazine' ) )
{
	$args                 = [
		'post_type'   => 'magazine',
		'post_status' => 'publish',
		'numberposts' => - 1
	];
	$context['magazines'] = Timber::get_posts( $args );

	Timber::render( 'magazine/magazines.twig', $context );
}

?>

<?php get_footer(); ?>
