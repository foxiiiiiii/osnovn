<?php



use Timber\Timber;



include WP_PLUGIN_DIR . '/timber-library/timber.php';



class AmbCore

{



	/**

	 * AmbCore constructor.

	 */

	public function __construct()

	{

		$this->InitData();

		//$this->LoadScripts();

		$this->InitSubscriberUserRole();

	}



	private function InitData()

	{

		$labels = [

			'name'               => 'Страницы', // Основное название типа записи

			'singular_name'      => 'Страница', // отдельное название записи типа

			'add_new'            => 'Добавить новую',

			'add_new_item'       => 'Добавить новую страницу',

			'edit_item'          => 'Редактировать страницу',

			'new_item'           => 'Новая страница',

			'view_item'          => 'Посмотреть страницу',

			'search_items'       => 'Найти страницу',

			'not_found'          => 'Страниц не найдено',

			'not_found_in_trash' => 'В корзине страниц не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Страницы'

		];



		tr_post_type( 'page' )->setEditorForm( static function() {

			$form = tr_form();

			echo $form->checkbox( 'page_regonly' )->setLabel( 'Толко для зарегистрированных' );

		} )->setArgument( 'labels', $labels );



		$labels = [

			'name'               => 'Подписки', // Основное название типа записи

			'singular_name'      => 'Подписка', // отдельное название записи типа

			'add_new'            => 'Добавить новую',

			'add_new_item'       => 'Добавить новую подписку',

			'edit_item'          => 'Редактировать подписку',

			'new_item'           => 'Новая подписка',

			'view_item'          => 'Посмотреть подписку',

			'search_items'       => 'Найти подписку',

			'not_found'          => 'Подписок не найдено',

			'not_found_in_trash' => 'В корзине подписок не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Подписки'

		];

		//        $subscription = tr_post_type('Subscription', 'Subscriptions', ['labels' => $labels])

		//            ->setIcon('ticket')

		//            ->setArgument('supports', ['title', 'editor', 'thumbnail']);



		// magazine

		$meta = tr_meta_box( 'magazine_details' )->setLabel( 'Информация о журнале' )->setCallback( function() {

			$form = tr_form();

			echo $form->row( $form->column( $form->text( 'common_number' )->setLabel( '№ общий' ) ),

				$form->column( $form->text( 'current_number' )->setLabel( '№ текущий' ) ),

				$form->column( $form->date( 'release_date_from' )->setLabel( 'Дата выпуска' )->setFormat( 'dd.mm.yy' ) ),

				$form->column( $form->date( 'release_date_to' )->setLabel( '-' )->setFormat( 'dd.mm.yy' ) )



			);

		} );



		$labels   = [

			'name'               => 'Журналы', // Основное название типа записи

			'singular_name'      => 'Журнал', // отдельное название записи типа

			'add_new'            => 'Добавить новый',

			'add_new_item'       => 'Добавить новый журнал',

			'edit_item'          => 'Редактировать журнал',

			'new_item'           => 'Новый журнал',

			'view_item'          => 'Посмотреть журнал',

			'search_items'       => 'Найти журнал',

			'not_found'          => 'Журналов не найдено',

			'not_found_in_trash' => 'В корзине журналов не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Журналы'

		];

		$magazine = tr_post_type( 'Magazine',

			'Magazines',

			[ 'labels' => $labels ] )->setIcon( 'newspaper' )->setTitleForm( static function() {

			$form = tr_form();

			echo $form->text( 'tr_mag_prefix' )->setLabel( 'Префикс названия журнала для архива журналов' )->setAttribute( 'width',

				'200 px' );

		} )->setArgument( 'supports', [ 'title', 'magazine_details', 'editor', 'thumbnail' ] );

		$magazine->addColumn( 'common_number', true, '№ общий' );



        // Bulletins

        $metaBull = tr_meta_box( 'bulletins_details' )->setLabel( 'Информация о бюллетене' )->setCallback( function() {

            $form = tr_form();

            echo $form->row( $form->column( $form->text( 'bull_common_number' )->setLabel( '№ общий' ) ),

                $form->column( $form->text( 'bull_current_number' )->setLabel( '№ текущий' ) ),

                $form->column( $form->date( 'bull_release_date_from' )->setLabel( 'Дата выпуска' )->setFormat( 'dd.mm.yy' ) ),

                $form->column( $form->date( 'bull_release_date_to' )->setLabel( '-' )->setFormat( 'dd.mm.yy' ) )

            );

        } );

        $labels = [

            'name'               => 'Бюллетени', // Основное название типа записи

            'singular_name'      => 'Бюллетень', // отдельное название записи типа

            'add_new'            => 'Добавить бюллетень',

            'add_new_item'       => 'Добавить бюллетень',

            'edit_item'          => 'Редактировать бюллетень',

            'new_item'           => 'Бюллетень',

            'view_item'          => 'Посмотреть бюллетень',

            'search_items'       => 'Найти бюллетень',

            'not_found'          => 'Бюллетеней не найдено',

            'not_found_in_trash' => 'В корзине бюллетеней не найдено',

            'parent_item_colon'  => '',

            'menu_name'          => 'Бюллетени'

        ];

        $bulletins  = tr_post_type( 'bulletin', 'bulletins', [ 'labels' => $labels ] )->setIcon( 'quill' )->setTitleForm( static function() {

            $form = tr_form();

            echo $form->text( 'tr_bull_prefix' )->setLabel( 'Префикс названия бюллетеня для архива' )->setAttribute( 'width',

                '200 px' );

        } )->setArgument( 'supports',

            [ 'title', 'editor', 'thumbnail', 'bulletins_details' ] );

        $bulletins->setSlug('bulletins');



        // bulletins articles

        $labelsBull  = [

            'name'               => 'Статьи бюллетеня', // Основное название типа записи

            'singular_name'      => 'Статья бюллетеня', // отдельное название записи типа

            'add_new'            => 'Добавить новую',

            'add_new_item'       => 'Добавить новую статью бюллетеня',

            'edit_item'          => 'Редактировать статью бюллетеня',

            'new_item'           => 'Новая статья бюллетеня',

            'view_item'          => 'Посмотреть статью бюллетеня',

            'search_items'       => 'Найти статью бюллетеня',

            'not_found'          => 'Статей бюллетеня не найдено',

            'not_found_in_trash' => 'В корзине статей бюллетеня не найдено',

            'parent_item_colon'  => '',

            'menu_name'          => 'Статьи бюллетеня'

        ];

        $articleBull = tr_post_type( 'BulletinsArticle', 'BulletinsArticles', [ 'labels' => $labelsBull ] )->setIcon( 'pencil' )->setArgument( 'supports',

            [ 'title', 'editor', 'thumbnail', 'comments' ] );

        $articleBull->setSlug( 'barticles' );



        //$this->InitBulletinsSections($articleBull);



        $meta_article_bull = tr_meta_box( 'barticle_details' )->setLabel( 'Информация статье бюллетеня' )->setCallback( static function() {

            $form = tr_form();

            $options = [

                'Главная' => 'm',

                'Важная' => 'i',

                'Обычная' => 's'

            ];



            echo $form->row(

                $form->column( $form->checkbox( 'bart_only_for_registered' )->setLabel( 'Видна только зарегестрированным пользователям' ) ),



                $radio = $form->column( $form->radio('bart_importante')->setOptions($options)->setLabel( 'Тип статьи' )->setSetting('default', 's') ),



                $form->column( $form->date( 'bart_date' )->setLabel( 'Дата' )->setFormat( 'dd.mm.yy' ) ) );





            echo $form->textarea( 'bart_short_description' )->setLabel( 'Краткое описание' );

            echo $form->row( $form->column( $form->links( 'bart_links',

                [ 'placeholder' => 'Введите первые буквы для поиска...' ],

                [ 'post_type' => 'bulletin' ] )->setLabel( 'Привязать к бюллетеню' ) ) );

            echo $form->text( 'bart_old_link' )->setLabel( 'Ссылка для редиректа' );

            echo $form->text( 'bart_priority' )->setLabel( 'Приоритет' );

            echo $form->text( 'bart_searchtext' )->setLabel( 'Текст для поиска' )->setAttribute('placeholder' , 'Текст для показа в поиске');

        } );



        $meta_article_bull->apply( $articleBull );



        ///////////////////////////////////////////////////////////////////////////////////////



		// article

		$labels  = [

			'name'               => 'Статьи', // Основное название типа записи

			'singular_name'      => 'Статья', // отдельное название записи типа

			'add_new'            => 'Добавить новую',

			'add_new_item'       => 'Добавить новую статью',

			'edit_item'          => 'Редактировать статью',

			'new_item'           => 'Новая статья',

			'view_item'          => 'Посмотреть статью',

			'search_items'       => 'Найти статью',

			'not_found'          => 'Статей не найдено',

			'not_found_in_trash' => 'В корзине статей не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Статьи'

		];

		$article = tr_post_type( 'Article', 'Articles', [ 'labels' => $labels ] )->setIcon( 'cubes' )->setArgument( 'supports',

			[ 'title', 'editor', 'thumbnail', 'comments' ] );

		$article->setSlug( 'articles' );



		$meta_article = tr_meta_box( 'article_details' )->setLabel( 'Информация статье' )->setCallback( static function() {

			$form = tr_form();

			$options = [

				'Главная' => 'm',

				'Важная' => 'i',

				'Обычная' => 's'

			];



			echo $form->row( //$form->column($form->text('art_magazine_number')->setLabel('№ журнала')),

				$form->column( $form->checkbox( 'art_only_for_registered' )->setLabel( 'Видна только зарегестрированным пользователям' ) ),



			    $radio = $form->column( $form->radio('art_importante')->setOptions($options)->setLabel( 'Тип статьи' )->setSetting('default', 's') ),



//				$form->column( $form->checkbox( 'art_main' )->setLabel( 'Главная статья номера' ) ),

//				$form->column( $form->checkbox( 'art_importante' )->setLabel( 'Важная статья' ) ),

				$form->column( $form->date( 'art_date' )->setLabel( 'Дата' )->setFormat( 'dd.mm.yy' ) ) );





			echo $form->textarea( 'art_short_description' )->setLabel( 'Краткое описание' );

			echo $form->row( $form->column( $form->links( 'art_links',

				[ 'placeholder' => 'Введите первые буквы для поиска' ],

				[ 'post_type' => 'magazine' ] )->setLabel( 'Привязать к журналу' ) ) );

			echo $form->text( 'old_link' )->setLabel( 'Ссылка для редиректа' );

			echo $form->text( 'art_priority' )->setLabel( 'Приоритет' );

            echo $form->text( 'art_searchtext' )->setLabel( 'Текст для поиска' )->setAttribute('placeholder' , 'Текст для показа в поиске');

		} );



		$meta_article->apply( $article );



		function section_meta_callback( $post, $box )

		{



			$defaults = array( 'taxonomy' => 'section' );



			if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) )

			{

				$args = array();

			}

			else

			{

				$args = $box['args'];

			}



			extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

			$taxonomy = get_taxonomy( $args['taxonomy'] );

			?>



            <div id="taxonomy-<?php echo $args['taxonomy']; ?>" class="categorydiv">

				<?php

				$name = ( $args['taxonomy'] == 'category' ) ? 'post_category' : 'tax_input[' . $args['taxonomy'] . ']';

				echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.



				$term_obj = wp_get_object_terms( $post->ID, $args['taxonomy'] ); //_log($term_obj[0]->term_id)



				echo '<div id="sections_list">';

				if ( ! empty( $term_obj ) )

				{

					foreach ( $term_obj as $i => $iValue )

					{

						echo "<div id='sect_list_{$i}'><table><tr><td><button type='button' data-id='{$i}' title='Удалить рубрику' class='btn btn-info btn-sm sect_del'>-</button></td><td>";

						wp_dropdown_categories( array(

							'taxonomy'         => $args['taxonomy'],

							'hide_empty'       => 0,

							'name'             => "{$name}[]",

							'selected'         => $iValue->term_id,

							'orderby'          => 'name',

							'hierarchical'     => 1,

							'show_option_none' => '&mdash;',

							'class'            => 'widefat'

						) );

						echo '</tr></table></div>';

					}

				}

				else

				{

					echo "<div id='sect_list_0'><table><tr><td><button type='button' title='Удалить рубрику' data-id='0' class='btn btn-info btn-sm sect_del'>-</button></td><td>";

					wp_dropdown_categories( array(

						'taxonomy'         => $args['taxonomy'],

						'hide_empty'       => 0,

						'name'             => "{$name}[]",

						'orderby'          => 'name',

						'hierarchical'     => 1,

						'show_option_none' => '&mdash;',

						'class'            => 'widefat'

					) );

					echo '</tr></table></div>';

				}

				echo '</div>';



				echo '<div id="sect_manage"><table><tr><td><button id="add_sect" title="Добавить рубрику" type="button">+</button></td><td>Добавить рубрику</td>';

				echo '</tr></table></div>';



                $context = [];

                $context['new_section'] = '<div id="sect_template" style="display: none"><table><tr><td><button type="button" title="Удалить рубрику" data-id="xxx" class="btn btn-info btn-sm sect_del">-</button></td><td>' . wp_dropdown_categories([

                    'taxonomy' => $args['taxonomy'],

                    'hide_empty' => 0,

                    'name' => "{$name}[]",

                    'orderby' => 'name',

                    'hierarchical' => 1,

                    'show_option_none' => '&mdash;',

                    'class' => 'widefat',

                    'echo' => false

                ]) . '</tr></table></div>';

				Timber::render('view/section_metabox.twig', $context);

				?>

            </div>

			<?php

		}



		$labelsSect = [

			'name'              => 'Рубрика',

			'singular_name'     => 'Рубрика',

			'search_items'      => 'Искать рубрику',

			'all_items'         => 'Все рубрики',

			'view_item '        => 'Посмотреть рубрику',

			'parent_item'       => 'Родительская рубрика',

			'parent_item_colon' => 'Родительская рубрика:',

			'edit_item'         => 'Редактировать рубрику',

			'update_item'       => 'Обновить рубрику',

			'add_new_item'      => 'Добавить новую рубрику',

			'new_item_name'     => 'Имя новой рубрики',

			'menu_name'         => 'Рубрики',

		];

        $sections   = tr_taxonomy( 'section', 'sections', [ 'labels' => $labelsSect, 'meta_box_cb' => 'section_meta_callback' ] );

		//$sections = tr_taxonomy('section', 'sections', ['labels' => $labelsSect]);

		$sections->apply( array($article, $articleBull) );

		$sections->setHierarchical();



		$sections->setMainForm( static function() {

			$form = tr_form();

			echo $form->text( 'old_term_id' )->setLabel( 'ID рубрики в предыдущем сайте' );

		} );



		// news

		$labels = [

			'name'               => 'Новости', // Основное название типа записи

			'singular_name'      => 'Новость', // отдельное название записи типа

			'add_new'            => 'Добавить новость',

			'add_new_item'       => 'Добавить новость',

			'edit_item'          => 'Редактировать новость',

			'new_item'           => 'Новость',

			'view_item'          => 'Посмотреть новость',

			'search_items'       => 'Найти новость',

			'not_found'          => 'Новостей не найдено',

			'not_found_in_trash' => 'В корзине новостей не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Новости'

		];

		$news   = tr_post_type( 'news', 'news', [ 'labels' => $labels ] )->setIcon( 'newspaper-o' )->setArgument( 'supports',

			[ 'title', 'editor', 'thumbnail', 'comments' ] );

		$news->addColumn( 'news_details_fnc',

			false,

			'Новость ИФНС',

			function( $fns ) {

				if ( $fns == '1' )

				{

					echo 'да';

				}

				else

				{

					echo 'нет';

				}

			} );



        $sections->apply( $news );



		// news_detail

		$meta_news = tr_meta_box( 'news_details' )->setLabel( 'дополнительная информация по новости' )->setCallback( function() {

			$form = tr_form();

			echo $form->editor( 'news_details_annotation' )->setLabel( 'краткое описание' );

			echo $form->checkbox( 'news_details_fnc' )->setLabel( 'ФНС' );

		} );

		$meta_news->apply( $news );



		// Новости ФНС

		// news

		$labels   = [

			'name'               => 'Новости ИФНС', // Основное название типа записи

			'singular_name'      => 'Новость ИФНС', // отдельное название записи типа

			'add_new'            => 'Добавить новость ИФНС',

			'add_new_item'       => 'Добавить новость ИФНС',

			'edit_item'          => 'Редактировать новость ИФНС',

			'new_item'           => 'Новость ИФНС',

			'view_item'          => 'Посмотреть новость ИФНС',

			'search_items'       => 'Найти новость ИФНС',

			'not_found'          => 'Новостей ИФНС не найдено',

			'not_found_in_trash' => 'В корзине новостей ИФНС не найдено',

			'parent_item_colon'  => '',

			'menu_name'          => 'Новости ИФНС'

		];

		$news_fns = tr_post_type( 'news_fns', 'news_fns', [ 'labels' => $labels, 'rewrite' => false ] )->setIcon( 'newspaper-o' )->setArgument( 'supports',

			[ 'title', 'editor' ] );



        $labelsFnsNewInspection = [

            'name'              => 'Инспекция ФНС',

            'singular_name'     => 'Инспекция ФНС',

            'search_items'      => 'Искать инспекцию ФНС',

            'all_items'         => 'Все инспекции ФНС',

            'view_item '        => 'Посмотреть инспекция ФНС',

            //'parent_item'       => 'Родительская рубрика',

            //'parent_item_colon' => 'Родительская рубрика:',

            'edit_item'         => 'Редактировать инспекцию ФНС',

            'update_item'       => 'Обновить инспекцию ФНС',

            'add_new_item'      => 'Добавить новую инспекцию ФНС',

            'new_item_name'     => 'Имя новой инспекции ФНС',

            'menu_name'         => 'Инспекции ФНС',

        ];

        $inspections   = tr_taxonomy( 'inspection', 'inspections', [ 'labels' => $labelsFnsNewInspection] );

        $inspections->apply( $news_fns );

        $inspections->setHierarchical();



		// news_detail

		$meta_news_fns = tr_meta_box( 'news_fns_details' )->setLabel( 'дополнительная информация по новости ФНС' )->setCallback( function() {

			$form = tr_form();

			echo $form->editor( 'news_fns_details_annotation' )->setLabel( 'краткое описание' );

			//		echo $form->checkbox('news_details_fnc')->setLabel('ФНС');

		} );

		$meta_news_fns->apply( $news_fns );



        // news_detail

        $meta_news = tr_meta_box( 'news_details' )->setLabel( 'дополнительная информация по новости' )->setCallback( function() {

            $form = tr_form();

            echo $form->editor( 'news_details_annotation' )->setLabel( 'краткое описание' );

            echo $form->checkbox( 'news_details_fnc' )->setLabel( 'ФНС' );

        } );

        $meta_news->apply( $news );





		// users and roles



		////////////////////////////////////////////////////////////////

		function manage_sections( $out, $column_name, $id )

		{

			switch ( $column_name )

			{

				case 'old_term_id':

					$meta = get_term_meta( $id );

					if ( isset( $meta['old_term_id'] ) )

					{

						$out .= $meta['old_term_id'][0];

					}

					break;

				default:

					break;

			}



			return $out;

		}



		function manage_section_head( $columns )

		{

			$columns['old_term_id'] = 'ID старой рубрики';

			unset( $columns['description'] );



			return $columns;

		}



		add_action( "manage_section_custom_column", 'manage_sections', 10, 3 );

		add_filter( 'manage_edit-section_columns', 'manage_section_head' );

	}



	private function InitSubscriberUserRole()

	{

		if ( get_role( 'subscriber' ) !== null )

		{

			remove_role( 'subscriber' );

		}



		$result = add_role( 'ab_subscriber',

			'Подписчик журнала',

			array(

				// core wordpress caps

				'read'                   => true, // true allows this capability

				'edit_posts'             => false, // Allows user to edit their own posts

				'edit_post'              => false, //

				'edit_pages'             => false, // Allows user to edit pages

				'edit_others_pages'      => false,

				'edit_private_pages'     => false,

				'edit_published_pages'   => false,

				'read_private_pages'     => false,

				'edit_others_posts'      => false, // Allows user to edit others posts not just their own

				'edit_private_posts'     => false,

				'edit_published_posts'   => false,

				'create_posts'           => false, // Allows user to create new posts

				'manage_categories'      => false, // Allows user to manage post categories

				'publish_posts'          => false, // Allows the user to publish, otherwise posts stays in draft mode

				'delete_posts'           => false,

				'delete_private_posts'   => false,

				'delete_published_posts' => false,

				'delete_others_posts'    => false,

				'unfiltered_html'        => false,

				'edit_themes'            => false, // false denies this capability. User can’t edit your theme

				'install_plugins'        => false, // User cant add new plugins

				'update_plugin'          => false, // User can’t update any plugins

				'update_core'            => false // user cant perform core updates

				// new caps

			) );



		$role = get_role( 'ab_subscriber' );

		if ( $role !== null )

		{

			$role->add_cap( 'edit_published_posts' );

			$role->add_cap( 'upload_files' );

			$role->add_cap( 'delete_posts' );

			$role->add_cap( 'edit_pages' );

			$role->add_cap( 'delete_pages' );

			$role->add_cap( 'delete_published_posts' );

			$role->add_cap( 'delete_published_pages' );

			$role->add_cap( 'edit_published_pages' );

		}

	}



	public function InitCustomUserProfileFields()

	{

		function RenderUserInfo( $user )

		{

			wp_enqueue_style( 'bootstrap-style',

				get_stylesheet_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css',

				array(),

				'3.3.7' );

			wp_enqueue_style( 'bootstrap-datepicker',

				get_stylesheet_directory_uri() . '/vendor/bootstrap-datepicker/bootstrap-datetimepicker.min.css',

				array( 'bootstrap-style' ),

				'4.17.47' );

			wp_enqueue_script( 'bootstrap-js',

				get_stylesheet_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js',

				'jquery',

				'3.3.7',

				true );

			wp_enqueue_script( 'moment-js',

				get_stylesheet_directory_uri() . '/vendor/bootstrap-datepicker/moment-with-locales.min.js',

				'bootstrap-js',

				'3.3.7',

				true );

			wp_enqueue_script( 'bootstrap-datepicker-js',

				get_stylesheet_directory_uri() . '/vendor/bootstrap-datepicker/bootstrap-datetimepicker.min.js',

				[ 'bootstrap-js', 'moment-js' ],

				'3.3.7',

				true );

			wp_enqueue_script( 'newue-js', plugin_dir_url( __FILE__ ) . 'inc/newuser.js', 'jquery', '0.0.1', true );



			$context = [];

			if ( is_object( $user ) )

			{

				$context['subscribe_start'] = esc_attr( get_the_author_meta( 'subscribe_start', $user->ID ) );

				$context['subscribe_end']   = esc_attr( get_the_author_meta( 'subscribe_end', $user->ID ) );

				$context['is_suspended']    = esc_attr( get_the_author_meta( 'is_suspended', $user->ID ) );

			}

			echo Timber::compile( '/view/user_custom_fields.twig', $context );

		}



		add_action( "user_new_form", "RenderUserInfo" );

		add_action( 'show_user_profile', 'RenderUserInfo' );

		add_action( 'edit_user_profile', 'RenderUserInfo' );



		function save_subscribe( $user_id )

		{

			//            if(!current_user_can('ab_subscriber'))

			//                return false;

			//

			//            print_r($_POST);

			# save my custom field

			if ( ( isset( $_POST['subscribe_start'] ) && ! empty( $_POST['subscribe_start'] ) ) && ( isset( $_POST['subscribe_end'] ) && ! empty( $_POST['subscribe_end'] ) ) )

			{

				update_user_meta( $user_id, 'subscribe_start', $_POST['subscribe_start'] );

				update_user_meta( $user_id, 'subscribe_end', $_POST['subscribe_end'] );

				if ( ! empty( $_POST['is_suspended'] ) )

				{

					update_user_meta( $user_id, 'is_suspended', 1 );

				}

				else

				{

					update_user_meta( $user_id, 'is_suspended', 0 );

				}



				return true;

			}



			return false;

		}



		add_action( 'user_register', 'save_subscribe' );

		add_action( 'profile_update', 'save_subscribe' );

	}



	private function LoadScripts()

	{

		function my_enqueue( $hook )

		{

			if ( 'user-new.php' !== $hook )

			{

				return;

			}

			wp_enqueue_script( 'newuser-js', plugin_dir_path( __FILE__ ) . 'inc/newuser.js' );

		}



		add_action( 'admin_enqueue_scripts', 'my_enqueue' );

	}



	public function SetLoginHooks()

	{

		add_action( 'wp_login',

			static function( $user_login, $user ) {



				if ( ( $user !== null ) && ( in_array( 'ab_subscriber', $user->roles, true ) ) )

				{

					$current_date = \DateTime::createFromFormat( 'd.m.Y', date( 'd.m.Y' ) );

					$endDate      = get_user_meta( $user->ID, 'subscribe_end', true );

					$subscribeEnd = \DateTime::createFromFormat( 'd.m.Y', $endDate );



					if ( $current_date > $subscribeEnd )

					{

						update_user_meta( $user->ID, 'is_suspended', 1 );

					}

				}

			},

			10,

			2 );

	}





	/**

	 *  Перевод всех css и javascript ссылок из http в https

	 */

	public function HttpsToHttpInSourcesLoadersHooks()

	{

		add_filter( 'script_loader_src',

			static function( $src, $handle ) {

				return preg_replace( '/^(http|https):/', '', $src );

			},

			20,

			2 );



		add_filter( 'style_loader_src',

			static function( $src, $handle ) {

				return preg_replace( '/^(http|https):/', '', $src );

			},

			20,

			2 );

	}



	public function CustomRoutes()

	{

		//        add_action('tr_load_routes', static function () {

		//            tr_route()->match('read/(.*)\.html/', ['old_id'])->get()->do(function ($old_id) {

		//                return '/read/';

		//            });

		//        });





		//        add_filter( 'request', static function($query){

		//            $url_zapros = urldecode($_SERVER['REQUEST_URI']);

		//

		//            /* для рубрик */

		//            if( $url_zapros == '/category/Без_рубрики/' )

		//                $query['category_name'] = 'без_рубрики';

		//

		//            /* для страниц */

		//            if( $url_zapros == '/Контакты/' ){

		//                $query['pagename'] = urlencode('контакты');

		//                unset($query['name']);

		//            }

		//

		//            /* для записей */

		//            if( $url_zapros == '/хеллоу-мир/' )

		//                $query['name'] = 'привет-мир';

		//

		//            /* для меток */

		//            if( $url_zapros == '/tag/Метка/' )

		//                $query['tag'] = 'метка';

		//

		//            return $query;

		//        }, 9999, 1 );



		add_action( 'template_redirect',

			static function() {

				$url = urldecode( $_SERVER['REQUEST_URI'] );

				if ( preg_match( '/\/read\/([0-9]{1,})\.html/', $url ) )

				{

					$args = [

						'post_type'   => 'article',

						'post_status' => 'publish',

						'numberposts' => - 1,

						'meta_query'  => [

							[

								"key"     => "old_link",

								"value"   => urldecode( $_SERVER['REQUEST_URI'] ),

								"compare" => "LIKE",

							]

						],

					];

					$post = Timber::get_post( $args );

					if ( ! empty( $post ) )

					{

						wp_redirect( $post->link(), 301 );

					}

				}

			} );

	}



    private function InitBulletinsSections($entity)

    {

        function section_bulletin_meta_callback( $post, $box )

        {



            $defaults = array( 'taxonomy' => 'sectionbull' );



            if ( ! isset( $box['args'] ) || ! is_array( $box['args'] ) )

            {

                $args = array();

            }

            else

            {

                $args = $box['args'];

            }



            extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

            $taxonomy = get_taxonomy( $args['taxonomy'] );

            ?>



            <div id="taxonomy-<?php echo $args['taxonomy']; ?>" class="categorydiv">

                <?php

                $name = ( $args['taxonomy'] == 'category' ) ? 'post_category' : 'tax_input[' . $args['taxonomy'] . ']';

                echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.



                $term_obj = wp_get_object_terms( $post->ID, $args['taxonomy'] ); //_log($term_obj[0]->term_id)



                echo '<div id="sections_list">';

                if ( ! empty( $term_obj ) )

                {

                    foreach ( $term_obj as $i => $iValue )

                    {

                        echo "<div id='sect_list_{$i}'><table><tr><td><button type='button' data-id='{$i}' title='Удалить рубрику бюллетеня' class='btn btn-info btn-sm sect_del'>-</button></td><td>";

                        wp_dropdown_categories( array(

                            'taxonomy'         => $args['taxonomy'],

                            'hide_empty'       => 0,

                            'name'             => "{$name}[]",

                            'selected'         => $iValue->term_id,

                            'orderby'          => 'name',

                            'hierarchical'     => 1,

                            'show_option_none' => '&mdash;',

                            'class'            => 'widefat'

                        ) );

                        echo '</tr></table></div>';

                    }

                }

                else

                {

                    echo "<div id='sect_list_0'><table><tr><td><button type='button' title='Удалить рубрику бюллетеня' data-id='0' class='btn btn-info btn-sm sect_del'>-</button></td><td>";

                    wp_dropdown_categories( array(

                        'taxonomy'         => $args['taxonomy'],

                        'hide_empty'       => 0,

                        'name'             => "{$name}[]",

                        'orderby'          => 'name',

                        'hierarchical'     => 1,

                        'show_option_none' => '&mdash;',

                        'class'            => 'widefat'

                    ) );

                    echo '</tr></table></div>';

                }

                echo '</div>';



                echo '<div id="sect_manage"><table><tr><td><button id="add_sect" title="Добавить рубрику бюллетеня" type="button">+</button></td><td>Добавить рубрику бюллетеня</td>';

                echo '</tr></table></div>';



                $context = [];

                $context['new_section'] = '<div id="sect_template" style="display: none"><table><tr><td><button type="button" title="Удалить рубрику бюллетеня" data-id="xxx" class="btn btn-info btn-sm sect_del">-</button></td><td>' . wp_dropdown_categories([

                        'taxonomy' => $args['taxonomy'],

                        'hide_empty' => 0,

                        'name' => "{$name}[]",

                        'orderby' => 'name',

                        'hierarchical' => 1,

                        'show_option_none' => '&mdash;',

                        'class' => 'widefat',

                        'echo' => false

                    ]) . '</tr></table></div>';

                Timber::render('view/section_metabox.twig', $context);

                ?>

            </div>

            <?php

        }



        $labelsSect = [

            'name'              => 'Рубрика бюллетеня',

            'singular_name'     => 'Рубрика бюллетеня',

            'search_items'      => 'Искать рубрику бюллетеня',

            'all_items'         => 'Все рубрики бюллетеня',

            'view_item '        => 'Посмотреть рубрику бюллетеня',

            'parent_item'       => 'Родительская рубрика бюллетеня',

            'parent_item_colon' => 'Родительская рубрика бюллетеня:',

            'edit_item'         => 'Редактировать рубрику бюллетеня',

            'update_item'       => 'Обновить рубрику бюллетеня',

            'add_new_item'      => 'Добавить новую рубрику бюллетеня',

            'new_item_name'     => 'Имя новой рубрики бюллетеня',

            'menu_name'         => 'Рубрики бюллетеня',

        ];

        $sections   = tr_taxonomy( 'sectionbull', 'sectionbulls', [ 'labels' => $labelsSect, 'meta_box_cb' => 'section_bulletin_meta_callback' ] );

        //$sections = tr_taxonomy('section', 'sections', ['labels' => $labelsSect]);

        $sections->apply( $entity );

        $sections->setHierarchical();



//        $sections->setMainForm( static function() {

//            $form = tr_form();

//            echo $form->text( 'old_term_id_b' )->setLabel( 'ID рубрики в предыдущем сайте' );

//        } );

    }



}