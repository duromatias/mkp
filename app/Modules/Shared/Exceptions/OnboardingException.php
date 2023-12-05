<?php

namespace App\Modules\Shared\Exceptions;

use Exception;
use Throwable;

class OnboardingException extends Exception
{
	private array $body;

	public function __construct(array $body, $message = "", $code = 422, Throwable $previous = null) {
		$this->body = $body;

		parent::__construct($message, $code, $previous);
	}

	public function render() {
		$responseBody = array_merge(
			['name' => basename(str_replace('\\', '/',static::class))],
			$this->body
		);

    	return response()->json($responseBody, $this->getCode());
	}
}
