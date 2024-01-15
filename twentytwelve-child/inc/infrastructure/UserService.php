<?php


namespace AmbExpress\infrastructure;


use Timber\Post;

class UserService
{
	/**
	 * @param Post $post
	 *
	 * @return OperationResult|ValidateResult
	 */
	public static function HasRightToViewHiddenDocuments($post)
	{
		$validateResult = new ValidateResult( true );
		$user = null;
		if(is_user_logged_in())
		{
			$user = wp_get_current_user();
		}

		if(!empty($post->custom['page_regonly']) && $post->custom['page_regonly'])
		{
			if($user === null)
			{
				$validateResult->ValidateStatus = ValidateStatuses::USER_MUST_LOGIN;
				$validateResult->Error = 'Материал доступен только для зарегистрированных пользователей';
				$validateResult->Result = false;
			}
			elseif ( static::IsUserSubscriber($user) )
			{
				if ( static::IsSubscriptionExpired($user) )
				{
					update_user_meta( $user->ID, 'is_suspended', 1 );

					$validateResult->ValidateStatus = ValidateStatuses::USER_SUBSCRIBER_EXPIRED;
					$validateResult->Error = 'Срок действия подписки истёк';
					$validateResult->Result = false;
				}
			}
		}
		elseif($post->post_type === 'article')
		{
			$readOnlyArticle = tr_posts_field('art_only_for_registered', $post->ID);
			if($readOnlyArticle !== '0')
			{
				if(($user === null))
				{
					$validateResult->ValidateStatus = ValidateStatuses::USER_MUST_LOGIN;
					$validateResult->Error = 'Материал доступен только для зарегистрированных пользователей';
					$validateResult->Result = false;
				}
				elseif ( static::IsUserSubscriber($user) )
				{
					if ( static::IsSubscriptionExpired($user) )
					{
						update_user_meta( $user->ID, 'is_suspended', 1 );

						$validateResult->ValidateStatus = ValidateStatuses::USER_SUBSCRIBER_EXPIRED;
						$validateResult->Error = 'Срок действия подписки истёк';
						$validateResult->Result = false;
					}
				}
			}
		}

		return $validateResult;
	}

	private static function IsSubscriptionExpired( \WP_User $user ): bool
	{
		$expired = false;

		$current_date = \DateTime::createFromFormat( 'd.m.Y', date( 'd.m.Y' ) );
		$endDate      = get_user_meta( $user->ID, 'subscribe_end', true );
		$subscribeEnd = \DateTime::createFromFormat( 'd.m.Y', $endDate );

		if ( $current_date > $subscribeEnd )
		{
			$expired = true;
		}

		return $expired;
	}

	public static function IsUserSubscriber( \WP_User $user ): bool
	{
		return in_array( SharedConst::SUBSCRIBER_ROLE, $user->roles, true );
	}
}