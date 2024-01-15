<?php

use AmbExpress\infrastructure\PostsResult;
use AmbExpress\ViewModels\ArticleViewModel;
use AmbExpress\ViewModels\bulletin\BullArticleViewModel;
use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

get_header(); ?>
<?php require_once 'inc/left_sidebar.php'; ?>

<?php
$context = [];
if(function_exists('bcn_display'))
	$context['breadcrumbs'] = bcn_display(true);

if(taxonomy_exists('section') && is_tax('section'))
{
	$context['section'] = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

	if($context['section']->parent !== 0)
		// articles
	{
		$artVM = new ArticleViewModel();
		$context['posts_result'] = $artVM->GetArticlesOfSection($context['section']->term_id);
	}
	else
	{
		$sectionsVM = new \AmbExpress\ViewModels\SectionsViewModel();
		$sectionsVM->LoadSubSections($context['section']->term_id);
		$context['subsections'] = $sectionsVM->Sections;
	}

	Timber::render('sections/section.twig', $context);
}
else if(taxonomy_exists('sectionbull') && is_tax('sectionbull'))
{
	$context['section'] = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

	if($context['section']->parent !== 0)
		// articles
	{
		$artVM = new BullArticleViewModel();
		$context['posts_result'] = $artVM->GetArticlesOfSection($context['section']->term_id);
	}
	else
	{
		$sectionsVM = new \AmbExpress\ViewModels\SectionsViewModel('sectionbull');
		$sectionsVM->LoadSubSections($context['section']->term_id);
		$context['subsections'] = $sectionsVM->Sections;
	}

	Timber::render('sections/sectionbull.twig', $context);
}
else if(taxonomy_exists('inspection') && is_tax('inspection'))
{
	$context['inspection'] = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$args = [
		'post_type'   => 'news_fns',
		'post_status' => 'publish',
		'numberposts' => 10,
		'tax_query'  => [
			[
				'taxonomy' => 'inspection',
				'field'    => 'id',
				'terms'    => $context['inspection']->term_id,
				'operator' => 'IN'
			]
		],
		'paged' => get_query_var( 'paged' )
	];
	$postQuery = new PostQuery( $args );
	$news = $postQuery->get_posts();

	$postsResult = new PostsResult();
	$postsResult->Posts = $news;
	$postsResult->Total = $postQuery->found_posts;
	$postsResult->Pagination = $postQuery->pagination();

	$context['posts_result'] = $postsResult;
	$context['paginat'] = $postQuery->pagination();

	wp_reset_postdata();

	Timber::render('inspection.twig', $context);
}
?>


<?php require 'inc/right_sidebar.php'; ?>
<?php get_footer(); ?>