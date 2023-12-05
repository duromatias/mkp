<?php

namespace App\Modules\Admin\Middlewares;

use App\Modules\Users\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
		/* @Var User $user */
		$user = $request->user();

		if (!$user->esAdministrador()) {
			abort(403);
		}

		return $next($request);
	}
}
