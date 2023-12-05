<?php

namespace App\Modules\Shared\Dtos;

use ReflectionClass;
use ReflectionProperty;

/**
 * @deprecated Usar \App\Base\DataTransferObject en su lugar.
 */
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