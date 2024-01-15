<?php


namespace AmbExpress\ViewModels;


use Timber\Post;
use Timber\Timber;

class FavoriteViewModel
{
	public function IsArticleAdded( $artId  )
	{
		$fav = tr_get_model('favorite')->where('post_id', '=', $artId)->first();

		return ($fav != null);
	}

	public function RemoveArticle( $id )
	{
		$fav = tr_get_model('favorite')->where('post_id', '=', $id)->first();
		$fav->delete();
	}

	public function GetUserFavArticles( $id )
	{
		$arts = [];

		$artsObjs = tr_get_model('favorite')->where('user_id', '=',  $id)->findAll()->get();

		if($artsObjs !== null)
		{
			foreach ( $artsObjs as $obj )
			{
				$arts[] = Timber::get_post($obj->post_id);
			}

			foreach ( $arts as $article )
			{
				$mag_id                      = $article->custom['art_links'][0];
				$magazine                    = new Post( $mag_id );
				$article->custom['magazine'] = $magazine;
			}
		}

		return $arts;
	}

	public function AddArticle( $id )
	{
		$model = tr_get_model('favorite');
		if($model !== null)
		{
			$model->post_id = $_POST['id'];
			$model->user_id = wp_get_current_user()->ID;
			$model->note = '';
			$model->create();
		}
	}
}