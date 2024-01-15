<?php 
function Render() {
  $loader = new \Twig\Loader\FilesystemLoader(plugin_dir_path( __FILE__ ) . '../twig');
  $twig = new \Twig\Environment($loader, [
    'cache' => plugin_dir_path( __FILE__ ) . '../cache'
  ]);

  return $twig;
}

$token = isset($_COOKIE["token"]) ? $_COOKIE["token"] : false;
$current_date = date('d.m.Y');

$user_id = '';
$authed = false;
$access = false;
$subscribe_ended = '';
if($token) {
  $user_id = VerifyToken($token);
  $authed = true;
  $subscribe_ended = new DateTime(get_user_meta($user_id, 'subscribe_end', true));

  if(new DateTime < $subscribe_ended) {
    $access = true;
  }
}

?>


<div class="center-block site-content-right">
	<div class="breadcrumbs">
		<?php echo $context['breadcrumbs']; ?>
    
	</div>
	<main role="main"> 

		<h1><?php echo get_the_title(); ?></h1>
    <hr style="border-bottom: 1px solid #006ab4; margin:5px 0 15px 0;">
    <div class="static-content">
      <?php if(!$authed) : ?>
       <?php echo Render()->render('require_auth.twig'); ?>
       <?php echo do_shortcode( '[authentication]' ); ?>
       <?php echo Render()->render('alert_auth.twig'); ?>
      <?php endif; ?>

      <?php if($authed && $access) : ?>
        <?php the_content(); ?>
      <?php endif; ?>

      <?php 
      
      if($authed && !$access) {
        // include_once plugin_dir_path( __FILE__ ) . '../twig/alert_exp.php';
        echo Render()->render('alert_exp.twig', [
          'exp'  => $subscribe_ended
        ]);
      }
      
      ?>
    </div>

		

	</main>
</div>
