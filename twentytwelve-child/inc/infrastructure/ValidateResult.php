<?php


namespace AmbExpress\infrastructure;


class ValidateResult extends OperationResult
{
	public $ValidateStatus;

	/**
	 * ValidateResult constructor.
	 *
	 * @param bool $Result
	 * @param int $ValidateStatus
	 */
	public function __construct( $Result, $ValidateStatus = ValidateStatuses::USER_VALIDATED )
	{
		parent::__construct($Result);

		$this->ValidateStatus = $ValidateStatus;
	}


}