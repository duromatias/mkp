<?php

namespace App\Modules\Shared\Helpers;

class TraducirOnboardingErrors
{
	public static function traducirErrores(array $errorResponse) {
		if (array_key_exists('errors', $errorResponse)) {
			if (array_key_exists('email', $errorResponse['errors'])) {
				for ($i = 0; $i < count($errorResponse['errors']['email']) ; $i++) {
					if ($errorResponse['errors']['email'][$i] == 'The email has already been taken.') {
						$errorResponse['errors']['email'][$i] = 'El email ya se encuentra en uso';
					}
				}
			}

			if (array_key_exists('cuit', $errorResponse['errors'])) {
				for ($i = 0; $i < count($errorResponse['errors']['cuit']) ; $i++) {
					if ($errorResponse['errors']['cuit'][$i] == 'The cuit must be a valid CUIT.') {
						$errorResponse['errors']['cuit'][$i] = 'El cuit ingresado es inválido';
					}
				}
			}
		}

		if (array_key_exists('error', $errorResponse)) {
			if ($errorResponse['error'] === 'invalid_grant') {
				$errorResponse['message'] = 'Usuario y/o contraseña inválidos';
				unset($errorResponse['error_description']);
				unset($errorResponse['hint']);
			}
			else if ($errorResponse['error'] === 'disabled') {
				$errorResponse['message'] = 'Su usuario no está habilitado';
				unset($errorResponse['error_description']);
			}

			unset($errorResponse['error']);
		}

		return $errorResponse;
	}
}