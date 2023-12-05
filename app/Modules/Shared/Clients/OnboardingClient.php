<?php

namespace App\Modules\Shared\Clients;

use Illuminate\Support\Facades\Http;

class OnboardingClient
{
	private string $baseUrl;
	public static ?string $accessToken = null;

	public function __construct() {
		$this->baseUrl = config('onboarding.api_url');
	}
    
    static public function make(): self {
        return app()->make(static::class);
    }

	public function constructUrl(string $path) {
		return $this->baseUrl . '/api' . $path;
	}

	public function constructHeaders(array $headers = []) {
		return array_merge(
			$headers,
			[ 'Accept' => 'application/json' ],
			static::$accessToken === null ? [] : ['Authorization' => 'Bearer ' . static::$accessToken]
		);
	}

	public function login(string $email, string $password) {
		$url = $this->baseUrl . '/oauth/token';
		$body = [
			"grant_type" => "password",
			"client_id" => config('onboarding.client_id'),
			"client_secret" => config('onboarding.client_secret'),
			"scope" => "*",
			'username' => $email,
			'password' => $password,
		];
		$headers = $this->constructHeaders();

		$response = Http::withHeaders($headers)->post($url, $body);

		if ($response->ok()) {
			static::$accessToken = $response->json('access_token');
		}

		return $response;
	}


	public function get(string $path, array $parameters = [], array $headers = []) {
		$url = $this->constructUrl($path);
		$headers = $this->constructHeaders($headers);

		return Http::withHeaders($headers)->get($url, $parameters);
	}


	public function post(string $path, array $body, array $headers = []) {
		$url = $this->constructUrl($path);
		$headers = $this->constructHeaders($headers);

		return Http::withHeaders($headers)->post($url, $body);
	}
}