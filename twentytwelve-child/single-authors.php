<?php
/**
 * The template for displaying single authors.
 */

get_header();
require_once 'inc/left_sidebar.php';
?>

<div class="author-container">
    <?php while (have_posts()) : the_post(); ?>
        
            <?php
            $author_photo_id = get_post_meta(get_the_ID(), 'photo', true);
            $full_name = get_post_meta(get_the_ID(), 'full_name', true);
            $specialization = get_post_meta(get_the_ID(), 'Specialization', true);
            $author_description = get_post_meta(get_the_ID(), 'author_description', true);

            echo '<div class="author-info-details">'; 
            if ($author_photo_id) {
                $author_photo_url = wp_get_attachment_image_src($author_photo_id, 'full')[0];
                if ($author_photo_url) {
                    echo '<div class="author-photo-container">';
                    echo '<div class="author-photo-circle"><img class="author-photo" src="' . esc_url($author_photo_url) . '" alt="' . esc_attr($full_name) . '"></div>';
                    echo '</div>';
                }
            }

            if ($full_name) {
                echo '<h2>' . esc_html($full_name) . '</h2>';
            }
            if ($specialization) {
                echo '<p class="author-specialization">' . esc_html($specialization) . '</p>';
            }
            echo '</div>'; 
            ?>
        

        
            <div class="author-slider">
            <?php
            $slider_items = get_field('slider_for_sertifications');
            if ($slider_items) {
                foreach ($slider_items as $slider_item) {
                    echo '<div class="author-slider-item">';
                    echo '<div class="author-slider-content">';
                    
                   
                    if ($slider_item['certificate_name']) {
                        echo '<p class="certificate-name">' . esc_html($slider_item['certificate_name']) . '</p>';
                    }
                    
                    $image_id = $slider_item['image'];
                    $image_url = wp_get_attachment_image_src($image_id, 'full')[0];
                    if ($image_url) {
                        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($slider_item['collider_header']) . '">';
                    }
                    
                    echo '</div></div>';
                }
            }
            ?>
        </div>
        

    <?php endwhile; ?>
</div>

<?php if ($author_description) : ?>
<div class="author-description-container">
        <?php echo wpautop(esc_html($author_description)); ?>
</div>
<?php endif; ?>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sliderImages = document.querySelectorAll(".author-slider-item img");

    sliderImages.forEach((image) => {
        let isImageClicked = false;

        image.addEventListener("click", (event) => {
            if (event.button !== 2) { // Проверяем, что это не правая кнопка мыши
                if (!isImageClicked && !document.fullscreenElement) {
                    image.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }

                isImageClicked = false;
            }
        });
    });
});

</script>

<?php
get_footer();
?>
