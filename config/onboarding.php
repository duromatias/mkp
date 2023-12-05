<?php

return [
	'api_url'       => env('ONBOARDING_URL'),
	'client_id'     => env('ONBOARDING_CLIENT_ID', 4),
	'client_secret' => env('ONBOARDING_CLIENT_SECRET'),
    'db_name'       => env('ONBOARDING_DB_NAME'),

	'user_email' => env('ONBOARDING_USER_EMAIL'),
	'user_password' => env('ONBOARDING_USER_PASSWORD')
];