<?php

declare(strict_types=1);

namespace Core\Enum;

use ReflectionException;

abstract class AbstractEnum
{
    /**
     * @var array|null
     */
    protected static array $constants = [];

    /**
     * @param $key
     *
     * @return string
     *
     * @throws ReflectionException
     */
    public static function name(string $key)
    {
        return \array_search($key, static::choices());
    }

    /**
     * @param $value
     *
     * @return bool
     * @throws ReflectionException
     */
    public static function exist($value): bool
    {
        return null !== static::get($value);
    }

    /**
     * @param $value
     *
     * @return string|null
     * @throws ReflectionException
     */
    public static function get($value): ?string
    {
        $constants = static::initialize();

        return false === ($key = \array_search($value, $constants)) ? null : $constants[$key];
    }

    /**
     * @param $value
     *
     * @return bool
     * @throws ReflectionException
     */
    public static function has($value): bool
    {
        $constants = static::initialize();

        return false !== \array_search($value, $constants);
    }

    /**
     * @return null
     *
     * @throws ReflectionException
     */
    public static function getByName(string $name)
    {
        $constants = static::initialize();

        return $constants[$name] ?? null;
    }

    /**
     * @throws ReflectionException
     */
    public static function names(): array
    {
        return self::choices();
    }

    /**
     * @throws ReflectionException
     */
    public static function choices(): array
    {
        return static::initialize();
    }

    /**
     * @return mixed
     *
     * @throws ReflectionException
     */
    private static function initialize()
    {
        if (!isset(static::$constants[static::class])) {
            $class = new \ReflectionClass(\get_called_class());
            static::$constants[static::class] = $class->getConstants();
        }

        return static::$constants[static::class];
    }
}
