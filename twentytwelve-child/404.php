<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header();
require_once 'inc/left_sidebar.php';

$context = [];
$context['title'] = __( 'This is somewhat embarrassing, isn&rsquo;t it?', 'twentytwelve' );
$context['text'] = __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'twentytwelve' );
$context['form'] = get_search_form(['echo' => false]);

\Timber\Timber::render('pages/404.twig', $context);

require 'inc/right_sidebar.php';
get_footer();
