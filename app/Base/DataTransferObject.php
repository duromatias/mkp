<?php

namespace App\Base;

use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{

	public function __construct(array $parameters = [])
	{
		$class = new ReflectionClass(static::class);

		foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty){
			$property = $reflectionProperty->getName();

			if (array_key_exists($property, $parameters)) {
				$this->{$property} = $parameters[$property];
			}
		}
	}

	/**
	 * @param array $datos
	 * @return static
	 */
	public static function fromArray(array $datos)  {
		return new static($datos);
	}
}