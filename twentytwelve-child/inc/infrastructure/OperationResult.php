<?php


namespace AmbExpress\infrastructure;


class OperationResult
{
	public $Result;
	public $Error;
	public $Message;

	/**
	 * OperationResult constructor.
	 *
	 * @param $Result
	 */
	public function __construct( $Result )
	{
		$this->Result = $Result;}
}