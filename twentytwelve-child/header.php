<?php

/**

 * The Header template for our theme

 *

 * Displays all of the <head> section and everything up till <div id="main">

 *

 * @package WordPress

 * @subpackage Twenty_Twelve

 * @since Twenty Twelve 1.0

 */

use AmbExpress\ViewModels\CurrencyUpdateViewModel;use AmbExpress\ViewModels\MagazinesViewModel;use Timber\PostQuery;

?><!DOCTYPE html>

<!--[if IE 7]>

<html class="ie ie7" <?php language_attributes(); ?>>

<![endif]-->

<!--[if IE 8]>

<html class="ie ie8" <?php language_attributes(); ?>>

<![endif]-->

<!--[if !(IE 7) & !(IE 8)]><!-->

<html <?php language_attributes(); ?>>

<!--<![endif]-->

<head>
<meta name="mailru-verification" content="16b92f97f46e5339" />

<meta charset="<?php bloginfo( 'charset' ); ?>" />

<meta name="viewport" content="width=device-width" />

<meta name="yandex-verification" content="40b1333f8b51b687" />

<title><?php wp_title( '|', true, 'right' ); ?></title>

<link rel="profile" href="http://gmpg.org/xfn/11" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>

<!--[if lt IE 9]>

<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>

<![endif]-->

<?php wp_head(); ?>

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">

<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	

	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MWH9P6Q');</script>
<!-- End Google Tag Manager -->

<script src="//code-ya.jivosite.com/widget/PMptEdtqOc" async></script>

<script async type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src="https://vk.com/js/api/openapi.js?168",t.onload=function(){VK.Retargeting.Init("VK-RTRG-522647-9aU0m"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-522647-9aU0m" style="position:fixed; left:-999px;" alt=""/></noscript>	

	
</head>



<?php

$vm = new MagazinesViewModel();

$lastMagazine = $vm->GetLastMagazine();



$userName = '';

$subscribeStart = '';

$subscribeEnd = '';

$is_logged = false;

if(is_user_logged_in())

{

    $is_logged = true;

    $user = wp_get_current_user();

    $userName = $user->display_name;

	$context['is_subscriber'] = current_user_can('ab_subscriber');

	if($context['is_subscriber'])

	{

		$context['subscribe_start'] = esc_attr( get_the_author_meta( 'subscribe_start', $user->ID ) );

		$context['subscribe_end'] = esc_attr( get_the_author_meta( 'subscribe_end', $user->ID ) );

        $subscribeStart = $context['subscribe_start'];

        $subscribeEnd = $context['subscribe_end'];

	}

}

?>
<body <?php body_class(); ?>>




	<div class="journal">
    <header id="masthead" class="site-header" role="banner">    

		<div class="container-fluid">


			<div class="row">





	<?php if(get_field('heders', 11)): ?>
	<?php while(has_sub_field('heders', 11)): ?>
		<div class="up2">
			<?php if( get_sub_field("link", 11) ): ?>
			<a href="<?php the_sub_field('link', 11); ?>">
			<?php endif; ?>
			<?php the_sub_field('text', 11); ?>
			<?php if( get_sub_field("link", 11) ): ?>
			</a>
			<?php endif; ?>
		</div>
	<?php endwhile; ?>
	<?php endif; ?>




		
        <div class="head">
			<?php if(wp_is_mobile()) { ?>
				<?php } else { ?>
			<div class="up1">

				<?php

				$arr = [

					'января',

					'февраля',

					'марта',

					'апреля',

					'мая',

					'июня',

					'июля',

					'августа',

					'сентября',

					'октября',

					'ноября',

					'декабря'

				];

				$month = date('n') - 1;

				$day = date('d');

				$year = date('Y');



				date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу

				$time = time(); // Вот это значение отправляем в базу

				$offset = 5; // Допустим, у пользователя смещение относительно Гринвича составляет +3 часа

				$time += $offset * 3600; // Добавляем 3 часа к времени по Гринвичу

                $usd = CurrencyUpdateViewModel::GetCurrentValue();

                $eur = CurrencyUpdateViewModel::GetCurrentValue('EUR');
				$banner2 = get_option('banner2', false);
				$banner3 = get_option('banner3', false);
				?>

				Сегодня <span><?php echo $day, ' ', $arr[$month], ' ', $year; ?></span> г., <span><?php echo date("H:i", $time);?></span> GMT+5, ЦБ РФ USD — <span><?php echo $usd; ?></span> руб., ЦБ РФ EUR — <span><?php echo $eur; ?></span> руб., МРОТ: <span><?php echo tr_options_field('my_theme_options.mrot'); ?></span> руб., ключевая ставка Банка России: <span><?php echo tr_options_field('my_theme_options.cbrrf'); ?></span> %

			</div>
			<?php } ?>

			<div class="logobanners">
				<div class="logo-item">
					<div class="logo-block1">
						<div class="logo-block1-1">	
							<a href="/" rel="home"><img src="/wp-content/themes/twentytwelve-child/images/logo-krugloe.png"/></a>
						</div>
						<div class="logo-block1-2">
							<span class="logo-block1-title">Свежий номер:&nbsp;</span>	
							<span class="logo-block1-link"><a href="<?php echo $lastMagazine->link; ?>" title="Открыть АБ-Экспресс № <?php echo $lastMagazine->current_number; ?> (<?php echo $lastMagazine->common_number; ?>)"><?php echo $lastMagazine->title ?></a></span>	
						</div>
					</div>
					<div class="logo-block2">
						<div class="logo-block2-1">
	
                            <?php
                            $socials[] = get_field("vk_group", 818);
                            $socials[] = get_field("telegram_group", 818);
                            $socials[] = get_field("whatsapp_group", 818);
                            $socials[] = get_field("youtube_group", 818);
                            $socials[] = get_field("dzen_group", 818);
                            $socials[] = get_field("google_group", 818);

                            foreach ($socials as $social) {
                                $view = $social['view'];
                                $link = $social['link'];
                                $img = $social['img'];
                                if ($view): ?>
                                    <a href="<?= $link ?>" class="soc"
                                       target="_blank"
                                    style="background-image: url(<?= $img ?>);"
                                    ></a>
                                <?php endif;
                            }
                            ?>
</div>
						<div class="logo-block2-2">	
							<?php //if($is_logged): ?>
								<!-- <div class="autorized-name"><i class="fa fa-user" aria-hidden="true"></i> <?php //echo $userName; ?>							
								</div> -->
								<!-- <div><a class="mobile-favorites" href="/favorites">Избранное</a></div>	 -->
							<?php //else: ?>	
							<!-- <a class="logo-block2-login" href="/vhod-v-lichnyj-kabinet/">Личный кабинет</a> -->
							<?php //endif; ?>

                <?php echo do_shortcode( '[header_account]' ); ?>

						</div>
<div class="logo-block2-3 header-search">
                            <div class="header-search__container">
                                <a href="#search-panel" class="header-search__btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"/></svg>
                                    <span class="header-search__btn-text">Поиск</span>
                                </a>
                                <form action="/wp-admin/admin-post.php" method="post" id="search-panel" class="header-search__form">
                                    <input type="hidden" name="action" value="read_search">
                                    <input type="hidden" name="section" value="Все">
                                    <input type="hidden" name="mag_num" value="0">
                                    <input type="hidden" name="year" value="Все">
                                    <input type="hidden" name="back_addr" value="https://ab-express.ru/search/">
                                    <div class="header-search__form-container">
                                        <input type="text" name="search_text" value="">
                                        <button value="send">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"/></svg>
                                        </button>
                                    </div>
                                    <a href="#" class="header-search__form-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z"/></svg>
                                    </a>
                                </form>
                            </div>
                        </div>
					</div>

<?php if(wp_is_mobile()) { ?>
	<div class="up3">
		<marquee direction="right">

				<?php

				$arr = [

					'января',

					'февраля',

					'марта',

					'апреля',

					'мая',

					'июня',

					'июля',

					'августа',

					'сентября',

					'октября',

					'ноября',

					'декабря'

				];

				$month = date('n') - 1;

				$day = date('d');

				$year = date('Y');



				date_default_timezone_set("UTC"); // Устанавливаем часовой пояс по Гринвичу

				$time = time(); // Вот это значение отправляем в базу

				$offset = 5; // Допустим, у пользователя смещение относительно Гринвича составляет +3 часа

				$time += $offset * 3600; // Добавляем 3 часа к времени по Гринвичу

                $usd = CurrencyUpdateViewModel::GetCurrentValue();

                $eur = CurrencyUpdateViewModel::GetCurrentValue('EUR');
				$banner2 = get_option('banner2', false);
				$banner3 = get_option('banner3', false);
				?>

				Сегодня <span><?php echo $day, ' ', $arr[$month], ' ', $year; ?></span> г., <span><?php echo date("H:i", $time);?></span> GMT+5, ЦБ РФ USD — <span><?php echo $usd; ?></span> руб., ЦБ РФ EUR — <span><?php echo $eur; ?></span> руб., МРОТ: <span><?php echo tr_options_field('my_theme_options.mrot'); ?></span> руб., ключевая ставка Банка России: <span><?php echo tr_options_field('my_theme_options.cbrrf'); ?></span> %
</marquee>
			</div>
<?php } ?>


				</div>



				<div class="block-last-journal">



					<!--
						<div class="last-journal">
							<div class="fresh">Поиск по сайту</div>	
                            <form action="/wp-admin/admin-post.php" method="post">
                                <input type="text" name="search_text" value="" placeholder="Искать на сайте" style="outline: none;">
                                <input type="hidden" name="action" value="read_search">
                                <input type="hidden" name="back_addr" value="/search/">
                                <button type="submit" style="width: 40px; height: 28px; background: #458dbc; border: 0px; margin-left: -5px">
                                    <i class="fas fa-search" style="color: white"></i>
                                </button>
                            </form>
						</div>	
			


						
						<div class="last-journal">
							<div class="fresh">Авторизация</div>
							<div class="tema"></div>
							<?php if($is_logged): ?>
							
							<div class="autorized-name"><i class="fa fa-user" aria-hidden="true"></i> <?php echo $userName; ?>							
							</div>
							<div><a class="mobile-favorites" href="/favorites">Избранное</a></div>	
							<?php else: ?>

							<div class="autorization">
                
								<form class="form-inline" action="https://ab-express.ru/wp-admin/admin-post.php" method="post">
									<div class="form-group">
										<label for="user_login"></label>
										<input type="text" class="form-control" id="user_login" name="user_login" placeholder="Логин">
									</div>
									<div class="form-group">
										<label for="user_pass"></label>
										<input type="password" class="form-control" id="user_pass" name="user_pass" placeholder="Пароль">
									</div>
									<input type="hidden" name="action" value="userlogin">
									<button type="submit">Войти</button>
								</form>
								
							</div>
								
							<?php endif; ?>
							
						</div>						
					-->
				</div>



				

			</div>

				</div>

		</div>		

		

        

		</div>

	</header><!-- #masthead -->	


<nav id="site-navigation" class="main-navigation" role="navigation">

		

			<a class="assistive-text" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a>

			<?php wp_nav_menu( array( 
				'theme_location' => 'primary', 
				'menu_class' => 'nav-menu',  
				) ); ?>

		</nav><!-- #site-navigation -->



    <?php \Timber\Timber::render('part/auth/login.twig', []); ?>




<?php if ((is_front_page()) and (!is_paged())) { ?>
	<?php if(get_field('sliders')): ?>
	<div class="main-slider">
	<?php while(has_sub_field('sliders')): ?>
	<div class="main-slider-item">
	<div class="main-slider-block1">
		<div class="main-slider-title"><?php the_sub_field('title'); ?></div>
		<div class="main-slider-desc"><?php the_sub_field('desc'); ?></div>
		<div class="main-slider-link"><a href="<?php the_sub_field('link'); ?>">Подробнее</a></div>
	</div>
	<div class="main-slider-block2">
	<?php 
	$image = get_sub_field('image');
	if( !empty($image) ): ?>
	<a href="<?php the_sub_field('link'); ?>">
		<img src="<?php echo $image['url']; ?>" alt="<?php the_sub_field('title'); ?>" />
	</a>
	<?php endif; ?>
	</div>
	</div>
	<?php endwhile; ?>
	</div>
	<?php endif; ?>
<?php } ?>








<div id="page" class="hfeed site">



	

		<div id="main" class="wrapper">



			



