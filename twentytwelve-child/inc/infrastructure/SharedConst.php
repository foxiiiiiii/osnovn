<?php


namespace AmbExpress\infrastructure;


class SharedConst
{
	const SUBSCRIBER_ROLE = 'ab_subscriber';
}

class ValidateStatuses
{
	const USER_VALIDATED = 0;
	const USER_MUST_LOGIN = 1;
	const USER_SUBSCRIBER_EXPIRED = 2;
}