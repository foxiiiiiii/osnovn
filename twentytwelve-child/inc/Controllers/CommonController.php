<?php

namespace AmbExpress\Controllers;

use AmbExpress\infrastructure\UserService;
use AmbExpress\infrastructure\ValidateStatuses;
use AmbExpress\ViewModels\SubpagesViewModel;
use Timber\Timber;

class CommonController
{
	private $Action;
	private $Form;
	private $BakAddr;
	private $View;
	private $Context = [];

	/**
	 * CommonController constructor.
	 *
	 * @param string $Action
	 * @param string $Form
	 * @param string $BakAddr
	 * @param string $View
	 */
	public function __construct( string $Action, string $Form, string $View, string $BakAddr = null )
	{
		$this->Action = $Action;
		$this->Form   = $Form;
		$this->View   = $View;

		$this->BakAddr = $BakAddr === null ? esc_url( admin_url( 'admin-post.php' ) ) : $BakAddr;

		$this->Context['post'] = Timber::get_post();

        if(function_exists('bcn_display'))
            $this->Context['breadcrumbs'] = bcn_display(true);

		$this->Context['limitation'] = '';
    $this->Context['reg_only'] = get_post_meta(get_the_id(), 'page_regonly', true);

    if(isset($_COOKIE["token"])) {
      $user_id = VerifyToken($_COOKIE["token"]);
      $userdata = get_userdata($user_id);
      if($userdata) {
        $subscribe_end_compare = strtotime(get_user_meta($userdata->ID, 'subscribe_end', true));
        $current_date = date('d.m.Y');
        $this->Context['access'] = 'denied';
        if(current_time( 'timestamp' ) < $subscribe_end_compare) {
          $this->Context['access'] = 'accepted';
        }
      } else {
        $this->Context['access'] = 'unauthed';
      }
    } else {
      $this->Context['access'] = 'unauthed';
    }
		$agreeResult = UserService::HasRightToViewHiddenDocuments( $this->Context['post'] );
		if ( ! $agreeResult->Result )
		{
			switch ( $agreeResult->ValidateStatus )
			{
				case ValidateStatuses::USER_MUST_LOGIN:
					$this->Context['limitation_form'] = 'part/auth/not_auth.twig';
					break;
				case ValidateStatuses::USER_SUBSCRIBER_EXPIRED:
					$this->Context['limitation_form'] = 'part/auth/is_suspended.twig';
					break;
				default:
					$this->Context['limitation_form'] = 'part/auth/not_auth.twig';
			}

			$this->Context['limitation'] = $agreeResult->Error;
		}

		$this->Context['subpages'] = SubpagesViewModel::GetSubPages( $this->Context['post']->ID );

		$this->Context['action_addr'] = $this->BakAddr;
		$this->Context['action']      = $this->Action;
		$this->Context['form']        = $this->Form;

        if(isset($_SESSION['userinfo']['error']))
        {
            $this->Context['auth_login'] = $_SESSION['userinfo']['error'];
            unset($_SESSION['userinfo']['error']);
        }

	}

	public function RenderView()
	{
		Timber::render( $this->View, $this->Context );
	}

	public function SetMessage( $message )
	{
		$this->Context['flash_message'] = $message;
	}


}