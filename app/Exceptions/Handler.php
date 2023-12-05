<?php

namespace App\Exceptions;

use App\Base\Repository\NotFound;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use function response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

	public function render($request, Throwable $exception)
	{
		if ($exception instanceof ValidationException && $request->wantsJson()) {

			return response()->json([
				'message' => 'Los datos ingresados son inválidos',
				'errors' => $exception->validator->getMessageBag()], 422);

		}
        
        if ($exception instanceof NotFound) {
			return response()->json([
				'message' => $exception->getMessage(),
				'errors'  => [],
            ], 404);
        }

		return parent::render($request, $exception);
	}
}
