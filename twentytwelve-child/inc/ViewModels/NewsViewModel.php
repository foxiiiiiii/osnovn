<?php


namespace AmbExpress\ViewModels;


use Timber\PostQuery;

class NewsViewModel extends ViewModelBase
{
	public function LoadNews( $newsCount = 3, $fns = false )
	{
		$meta = [];

		if($fns)
        {
            $args = [
                'post_type'   => 'news_fns',
                'post_status' => 'publish',
                'numberposts' => $newsCount,
            ];
        }
		else
        {
            $args = [
                'post_type'   => 'news',
                'post_status' => 'publish',
                'numberposts' => $newsCount,
            ];
        }

		$query = new PostQuery( $args );
		return $query->get_posts();
	}
}