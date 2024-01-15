<?php


namespace AmbExpress\ViewModels;


use Timber\Timber;

class ViewModelBase
{
	protected $context = [];

	/**
	 * @return array
	 */
	public function getContext()
	{
		return $this->context;
	}

	public function Render($view)
	{
		Timber::render('glavnaya.twig', $this->context);
	}
}