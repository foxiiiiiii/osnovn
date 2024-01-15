<?php


namespace AmbExpress\ViewModels\bulletin;


use AmbExpress\infrastructure\PostsResult;
use Timber\Post;
use Timber\PostQuery;

class BullArticleViewModel
{
    public static function GetBothDistinctYears()
    {
        global $wpdb;
        $sql = "(SELECT distinct YEAR(STR_TO_DATE(pm.meta_value,'%d.%m.%Y')) AS byear
                FROM wp_postmeta pm INNER JOIN wp_posts p ON pm.post_id = p.ID WHERE p.post_status = 'publish' AND p.post_type = 'bulletin'
                AND pm.meta_key = 'bull_release_date_from' ORDER  BY p.ID)
                
                UNION
                
                (SELECT distinct YEAR(STR_TO_DATE(pm.meta_value,'%d.%m.%Y')) AS byear
                FROM wp_postmeta pm INNER JOIN wp_posts p ON pm.post_id = p.ID WHERE p.post_status = 'publish' AND p.post_type = 'magazine'
                AND pm.meta_key = 'release_date_from' ORDER  BY p.ID)
                
                ORDER BY byear";

        return $wpdb->get_col($sql);
    }

    /**
     * @param $sectionSlug
     *
     * @return PostsResult
     */
    public function GetArticlesOfSection($sectionSlug): PostsResult
    {
        global $paged;
        $args = [
            'post_type' => 'bulletinsarticle',
            'post_status' => 'publish',
            'numberposts' => -1,
            'posts_per_page' => 30,
            'paged' => $paged,
            'tax_query' => [
                [
                    'taxonomy' => 'sectionbull',
                    'field' => 'id',
                    'terms' => [$sectionSlug],
                    'operator' => 'IN'
                ]
            ],
        ];
        $postQuery = new PostQuery($args);
        $articles = $postQuery->get_posts();
        foreach ($articles as $article) {
            $mag_id = $article->custom['bart_links'][0];
            $magazine = new Post($mag_id);
            $article->custom['bulletin'] = $magazine;
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

    public static function GetBulletinsDistinctYears(): array
    {
        global $wpdb;
        $years_query = "SELECT distinct YEAR(STR_TO_DATE(pm.meta_value,'%d.%m.%Y')) AS byear FROM wp_postmeta pm INNER JOIN wp_posts p ON pm.post_id = p.ID WHERE p.post_status = 'publish' AND p.post_type = 'bulletin'"
            . " AND pm.meta_key = 'bull_release_date_from' ORDER  BY p.ID DESC";
        return $wpdb->get_col($years_query);
    }
}