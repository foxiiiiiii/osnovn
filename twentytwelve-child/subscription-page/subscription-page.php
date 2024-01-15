<?php
/*
Template Name: Subscription Page
*/

$html_file_path = get_stylesheet_directory() . '/subscription-page/subscription-page.html';

if (file_exists($html_file_path)) {
    header('Content-Type: text/html; charset=utf-8');

    function modify_styles($src) {
        return false;
    }

    add_filter('style_loader_src', 'modify_styles');

    function modify_scripts($src) {
        return false;
    }

    echo '<!DOCTYPE html><html lang="ru"><head>';
    
    wp_head();

    echo '<link rel="stylesheet" type="text/css" href="' . includes_url('/css/admin-bar.min.css') . '" />';

    echo '</head><body>';

    $html_content = file_get_contents($html_file_path);

    function replace_paths($matches) {
        $url = $matches[2];
        
        if (strpos($url, '#') === 0 || preg_match('/tel:/', $url)) {
            return $matches[0];
        }

        return $matches[1] . '="/wp-content/themes/twentytwelve-child/subscription-page/' . $url . '"';
    }

    $html_content = preg_replace_callback('/(src|href)="(?!http|https|ftp|data)([^"]+)"/', 'replace_paths', $html_content);

    echo $html_content;

    wp_footer();

    echo '</body></html>';

    remove_filter('style_loader_src', 'modify_styles');
    remove_filter('script_loader_src', 'modify_scripts');
} else {
    echo '<p>HTML-файл не найден</p>';
}
?>
