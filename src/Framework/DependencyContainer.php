<?php

namespace App\Framework;


use InvalidArgumentException;

class DependencyContainer
{
    private array $objects = [];

    public function __construct(array $objects)
    {
        foreach ($objects as $className => $object) {
            if (!is_object($object) || !class_exists($className)) {
                continue;
            }

            $this->objects[$className] = $object;
        }
    }

    public function get(string $className): object
    {
        if (!isset($this->objects[$className])) {
            throw new InvalidArgumentException("Object $className does not exist");
        }

        return $this->objects[$className];
    }
}