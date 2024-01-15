<?php

namespace AmbExpress\ViewModels;

use Timber\PostQuery;

class MagazinesViewModel extends ViewModelBase
{
	public function GetLastMagazine()
	{
		$args = [
			'post_type'        => 'magazine',
			'numberposts'      => 1,
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'meta_query'       => array(
				array(
					'key'     => 'release_date_from',
					'value'   => '01.01.2020',
					'compare' => '>='
				)

			),
			//            'meta_key' => 'common_number',
			//            'orderby'  => 'meta_value_num',
			//            'order'    => 'DESC'
		];

		return ( new PostQuery( $args ) )->get_posts()[0];
	}

	/**
	 * @param $magazineId
	 *
	 * @param bool $allArticles
	 *
	 * @return array
	 */
	public function GetArticles( $magazineId, $allArticles = true ): array
	{
		$dopMeta = [];
		if ( ! $allArticles )
		{
			$dopMeta = [
				'relation' => 'OR',
				[
					"key"     => "art_importante",
					"compare" => "NOT EXISTS"
				],
				[
					"key"     => "art_importante",
					"value"   => 's',
					"compare" => "="
				]
			];
		}
		$args = [
			'post_type'   => 'article',
			'post_status' => 'publish',
			'numberposts' => - 1,
			'meta_query'  => [
				[
					'relation' => 'AND',
					[
						"key"     => "art_links",
						"value"   => $magazineId,
						"compare" => "LIKE"
					],
					$dopMeta
				]
			],
		];

		return ( new PostQuery( $args ) )->get_posts();
	}

	public function GetImportanceArticles( $magazineId )
	{
		$args = [
			'post_type'   => 'article',
			'post_status' => 'publish',
			'numberposts' => - 1,
			'meta_query'  => [
				[
					'relation' => 'AND',
					[
						"key"     => "art_links",
						"value"   => $magazineId,
						"compare" => "LIKE"
					],
					[
						"key"     => "art_importante",
						"value"   => 'i',
						"compare" => "LIKE"
					]
				]
			],
		];

		return ( new PostQuery( $args ) )->get_posts();
	}

	public function GetMainArticle( $magazineId )
	{
		$args = [
			'post_type'   => 'article',
			'post_status' => 'publish',
			'numberposts' => 1,
			'meta_query'  => [
				[
					'relation' => 'AND',
					[
						"key"     => "art_links",
						"value"   => $magazineId,
						"compare" => "LIKE"
					],
					[
						"key"     => "art_importante",
						"value"   => 'm',
						"compare" => "LIKE"
					]
				]
			],
		];

        $posts = ( new PostQuery( $args ) )->get_posts();

        if(!is_array($posts) || empty($posts))
            return  null;

		return $posts[0];
	}

	public function GetMagazinesByYear( $year )
	{
		$args = [
			'post_type'   => 'magazine',
			'numberposts' => 30,
			'post_status' => 'publish',
			//			'suppress_filters' => true,
			'meta_query'  => array(
				array(
					'key'     => 'release_date_from',
					'value'   => $year,
					'compare' => 'LIKE'
				)

			)
		];

		return ( new PostQuery( $args ) )->get_posts();
	}

	/**
	 * @return array
	 */
	public function GetAllMagazines(): array
	{
		global $wpdb;
		$res = $wpdb->get_results( "select p.ID, p.post_title, p.post_date, pm.meta_value as release_date, STR_TO_DATE(meta_value, '%d.%m.%Y')
    										as rel_date from wp_posts p left join wp_postmeta pm ON pm.post_id = p.ID where (p.post_type = 'magazine')
											and (pm.meta_key = 'release_date_from') and (STR_TO_DATE(meta_value, '%d.%m.%Y') > '2004-01-01') order by rel_date DESC" );

		$arrResults = [];
		foreach ( $res as $item )
		{
			$arrResults[] = ['ID' => $item->ID, 'title' => $item->post_title];
		}

		return $arrResults;
	}
}