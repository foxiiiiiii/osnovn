<?php


namespace AmbExpress\ViewModels;


use Timber\Timber;

class SubpagesViewModel
{
	public static function GetSubPages( $rootPageId )
	{
		$args = [
			'parent'    => $rootPageId,
			'sort_column' => 'post_date',
			'sort_order'  => 'desc'

		];

		$arrPages =  get_pages($args);
		$arrVM = [];
		foreach ( $arrPages as $page )
		{
			$arr = [];
			$arr['subpage'] =  $page;
			$arr['link'] = get_post_permalink($page->ID);

			$arrVM[] = $arr;
		}

		return $arrVM;
	}
}