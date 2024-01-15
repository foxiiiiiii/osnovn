<?php


namespace AmbExpress\ViewModels;


use AmbExpress\infrastructure\PostsResult;
use Timber\Post;
use Timber\PostQuery;

class ArticleViewModel
{
    /**
     * @param $sectionSlug
     *
     * @return PostsResult
     */
    public function GetArticlesOfSection($sectionSlug): PostsResult
    {
        global $paged;
        $args = [
            'post_type' => 'article',
            'post_status' => 'publish',
            'numberposts' => -1,
            'posts_per_page' => 30,
            'paged' => $paged,
            'tax_query' => [
                [
                    'taxonomy' => 'section',
                    'field' => 'id',
                    'terms' => [$sectionSlug],
                    'operator' => 'IN'
                ]
            ],
        ];
        $postQuery = new PostQuery($args);
        $articles = $postQuery->get_posts();
        foreach ($articles as $article) {
            $mag_id = $article->custom['art_links'][0];
            $magazine = new Post($mag_id);
            $article->custom['magazine'] = $magazine;
        }

        $postsResult = new PostsResult();
        $postsResult->Posts = $articles;
        $postsResult->Total = $postQuery->found_posts;
        $postsResult->Pagination = $postQuery->pagination();

        wp_reset_postdata();

        return $postsResult;
    }

    public static function GetArticleDistinctYears(): array
    {
        global $wpdb;
        $years_query = "SELECT DISTINCT YEAR(post_date)"
            . " FROM wp_posts AS p"
            . " WHERE p.post_status = 'publish'"
            . " AND p.post_type = 'article'"
            . " ORDER BY p.post_date DESC";
        return $wpdb->get_col($years_query);
    }

    public static function GetBullArticleDistinctYears(): array
    {
        global $wpdb;
        $years_query = "SELECT DISTINCT YEAR(post_date)"
            . " FROM wp_posts AS p"
            . " WHERE p.post_status = 'publish'"
            . " AND p.post_type = 'bulletinsarticle'"
            . " ORDER BY p.post_date DESC";
        return $wpdb->get_col($years_query);
    }
}