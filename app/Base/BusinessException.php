<?php

namespace App\Base;

use Exception;
use Throwable;

class BusinessException extends Exception
{
    private array $errors;
	private array $data = [];

    public function __construct($message = "", array $errors = [], $code = 422, Throwable $previous = null) {
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

	public function setData(array $data) {
		$this->data = $data;
	}

    public function render() {
        return response()->json([
            'name' => basename(str_replace('\\', '/',static::class)),
            'message' => $this->getMessage(),
            'errors' => $this->errors,
			'data' => $this->data,
        ], $this->getCode());
    }
}