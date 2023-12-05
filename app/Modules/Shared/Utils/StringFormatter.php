<?php

namespace App\Modules\Shared\Utils;

class StringFormatter
{
	public static function capitalizeText(string $string) {
		$lowerCase = strtolower($string);
		$words = explode(' ', $lowerCase);

		return implode(" ", array_map(function($word) {
			return ucfirst($word);
		}, $words));
	}
}