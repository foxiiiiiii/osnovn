<div class="left-block">

<div class="news-posts-block">
	<h3>В новом номере журнала:</h3>
	<?php		
	global $post;
	$query = new WP_Query( [
		'post_type' => 'magazine',
		'post_status' => 'publish',
		'numberposts' => 1,
	] );
	$magazineId = -1;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$magazineId = get_the_ID();
			?>
			<div class="news-posts-nomber">Журнал №<?php the_field('current_number'); ?> <span>(<?php  the_title() ?>)</span></div>
			<?php 
		}
	}
	wp_reset_postdata(); 
	?>
	<?php		
	global $post;

	$query = new WP_Query( [
		'post_type' => 'article',
		'post_status' => 'publish',
		'numberposts' => 4,
'meta_query' => [
                [
                    'relation' => 'AND',
                    [
                        "key"     => "art_links",
                        "value"   => $magazineId,
                        "compare" => "LIKE"
                    ],
                ]
        ]
	] );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<div class="news-posts-link">
			<a href="<?php echo get_permalink(); ?>">
				<?php  the_title() ?>
			</a>
			</div>
			<?php 
		}
	}
	wp_reset_postdata(); 
	?>
	<?php		
	global $post;
	$query = new WP_Query( [
		'post_type' => 'magazine',
		'post_status' => 'publish',
		'numberposts' => 1,
	] );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			?>
			<div class="news-posts-readmore"><a href="<?php echo get_permalink(); ?>">Подробнее</a></div>
			<?php 
		}
	}
	wp_reset_postdata(); 
	?>
	<div class="news-posts-subscribe">
		<a href="/podpiska-na-zhurnal/">Оформить подписку</a>
	</div>
</div>


<?php wp_nav_menu( array( 'container_class' => 'left-menu', 'menu' => 'Left men' ) ); ?>




	<?php if(get_field('reklam_left', 11)): ?>
	<?php while(has_sub_field('reklam_left', 11)): ?>
		<div class="reklam_left">
			<?php if( get_sub_field("link", 11) ): ?>
			<a href="<?php the_sub_field('link', 11); ?>">
			<?php endif; ?>
				<?php 
				$image = get_sub_field('image', 11);
				if( !empty($image) ): ?>
				<img src="<?php echo $image['url']; ?>" />
				<?php endif; ?>
			<?php if( get_sub_field("link", 11) ): ?>
			</a>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
	<?php endif; ?>




<?php

use Timber\Timber;

$context = [];
$is_logged = is_user_logged_in();
if ( $is_logged )
{
	$context['logged'] = true;
	$context['user']   = wp_get_current_user();
} else
{
	$context['logged'] = false;
}

$args = [
	'menu'            => 'Left menu',
	'container_class' => 'left-menu',
	'echo'            => 0,
	'menu_class'      => ''
];

$menu            = wp_nav_menu( $args );
$context['menu'] = $menu;
$context['redirect_url'] = $_SERVER['REQUEST_URI'];
if(isset($_SESSION['userinfo']['error']))
{
	$context['error'] = $_SESSION['userinfo']['error'];
//	unset($_SESSION['userinfo']['error']);
}
$context['action_addr']       = esc_url( admin_url( 'admin-post.php' ) );
if($is_logged)
{
    $context['is_subscriber'] = current_user_can('ab_subscriber');
    if($context['is_subscriber'])
    {
        $context['subscribe_start'] = esc_attr( get_the_author_meta( 'subscribe_start', $context['user']->ID ) );
        $context['subscribe_end'] = esc_attr( get_the_author_meta( 'subscribe_end', $context['user']->ID ) );
    }
}

$context['banner1'] = get_option('banner1', false);


// Timber::render( 'sidebars/left.twig', $context );
echo do_shortcode( '[sidebar_login]' );
?>

</div>
