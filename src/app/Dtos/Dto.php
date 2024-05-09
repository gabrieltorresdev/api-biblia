<?php

namespace App\Dtos;

use Illuminate\Support\Arr;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Enumerable;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use Traversable;

abstract class Dto implements Arrayable
{
    public function __construct(array $dados = [])
    {
        $this->registrarDados($dados);
    }

    protected function registrarDados(array $dados = []): void
    {
        $class = new ReflectionClass(static::class);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {

            if ($property->isStatic()) {
                continue;
            }

            $propertyName = $property->getName();
            $propertyType = $property->getType() ? $property->getType()->getName() : null;

            $value = Arr::get($dados, $propertyName);

            if ($value && $this->isDTOInstance($propertyType)) {
                $value = app($propertyType, ['dados' => $value]);
            }

            $this->$propertyName = $value;
        }
    }

    protected function isDTOInstance($propertyType): bool
    {
        if(!$propertyType || !is_string($propertyType) || !class_exists($propertyType)){
            return false;
        }
        $reflect = new ReflectionClass($propertyType);

        return $reflect->newInstanceWithoutConstructor() instanceof self;
    }

    /**
     * Retorna todas as propriedades públicas do DTO no formato de array
     * @return array
     */
    public function all(): array
    {
        return get_object_vars($this);
    }

    public function toArray(array $except = []): array
    {
        return Arr::except($this->all(), $except);
    }

    protected static function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof Enumerable) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        } elseif ($items instanceof JsonSerializable) {
            return (array) $items->jsonSerialize();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }

    /**
     * Cria um array de objetos DTO atraves de um array de dados
     * @param mixed $dados
     * @return array<static>
     */
    public static function arrayOf($dados): array
    {
        $dados = static::getArrayableItems($dados);

        return array_map(function ($entry) {
            if($entry instanceof static){
                return $entry;
            }
            return new static((array) $entry);
        }, $dados);
    }

}