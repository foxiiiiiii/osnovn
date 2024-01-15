<?php
// Проверка авторизации пользователя и получение имени пользователя из токена
$is_user_logged_in = false;
$user_name = 'Гость';

if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    // Используйте функцию VerifyToken для проверки токена и получения ID пользователя
    $user_id = VerifyToken($_COOKIE['token']);
    if ($user_id) {
        // Получение данных пользователя
        $user_info = get_userdata($user_id);
        // Используйте нужное поле для имени пользователя, например first_name
        $user_name = $user_info->first_name;
        $is_user_logged_in = true;
    }
}

// Если запись защищена паролем, не загружаем комментарии
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php
    // Отображение формы комментариев только для авторизованных пользователей
    if ($is_user_logged_in) {
        $commenter = wp_get_current_commenter();
        comment_form([
            'comment_field'        => '<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="Текст комментария"></textarea>',
            'logged_in_as'         => '',
            'comment_notes_before' => '',
            'title_reply'          => __('Leave a Comment'),
            'title_reply_to'       => __('Leave a Reply to %s'),
            'cancel_reply_link'    => __('Отмена'),
            'label_submit'         => __('Post Comment'),
            'class_submit'         => 'btn-comment',
            'fields'               => [
                'author' => '',
                'email'  => '',
                'url'    => '',
            ],
        ]);
    } else {
        echo '<p><a href="https://ab-express.ru/auth/">Для добавления комментариев необходимо авторизоваться.</a></p>';
    }

    if (have_comments()) : ?>
        <ol class="commentlist">
            <?php wp_list_comments(['callback' => 'twentytwelve_comment', 'style' => 'ol']); ?>
        </ol><!-- .commentlist -->

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav id="comment-nav-below" class="navigation" role="navigation">
                <h1 class="assistive-text section-heading"><?php _e('Comment navigation', 'twentytwelve'); ?></h1>
                <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'twentytwelve')); ?></div>
                <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'twentytwelve')); ?></div>
            </nav>
        <?php endif; ?>
    <?php else : ?>
        <p class="nocomments"><?php _e('У записи нет комментариев', 'twentytwelve'); ?></p>
    <?php endif; ?>
</div><!-- #comments .comments-area -->
