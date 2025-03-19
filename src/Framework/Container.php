<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;
use ReflectionClass;
use ReflectionNamedType;

class Container {
    
    private array $definitions = [];
    private array $resolved = [];

    public function resolve($class){
        $reflectionClass = new ReflectionClass($class);

        if(!$reflectionClass->isInstantiable())
            throw new ContainerException("Class {$class} is not instantiable");

        $reflectionConstructor = $reflectionClass->getConstructor();

        if(!$reflectionConstructor)
            throw new ContainerException("Missing constructor for {$class} class");

        $reflectionParameters = $reflectionConstructor->getParameters();

        $dependencies = [];

        foreach($reflectionParameters as $parameter){
            $name = $parameter->getName();
            $type = $parameter->getType();

            if(!$type)
                throw new ContainerException("No type given to {$name} parameter for {$class} class");

            if(!$type instanceof ReflectionNamedType || $type->isBuiltin())
                throw new ContainerException("Invalid data type for parameter {$name} in class {$class}");

            $dependencies[$name] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get($className){
        if(array_key_exists($className, $this->resolved)){
            return $this->resolved[$className];
        }
        if(!array_key_exists($className, $this->definitions))
            throw new ContainerException("Definition for {$className} does not exists");

        $depedency = $this->definitions[$className]($this);

        $this->resolved[$className] = $depedency;
        return $depedency;
        
    }

    public function addDefinitions(array $newDefinitions){
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }
}