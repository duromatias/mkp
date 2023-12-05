<?php

namespace App\Modules\Auth\Rules;

use App\Modules\Users\Models\User;
use Illuminate\Contracts\Validation\Rule;
use App\Base\Repository\RepositoryException;

class EmailExistenteRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
    	try {
    		$user = User::getOne([
    			'email' => $value
			]);

    		return true;
		}
		catch (RepositoryException $exception) {
    		return false;
		}
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El email ingresado no está registrado';
    }
}
