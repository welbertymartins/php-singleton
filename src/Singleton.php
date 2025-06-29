<?php declare(strict_types=1);

namespace WelbertyMartins\Singleton;

/**
 * Singleton container for managing and retrieving shared instances.
 * This class allows storing and accessing object instances.
 * (via `root()`) or in isolated contexts (via `local()`).
 */
final class Singleton
{
    /**
     * Shared global singleton instance.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Map of hashed names to their respective stored objects.
     *
     * @var array<string, object>
     */
    private array $instances = [];

    /**
     * Stores the name of the most recently remembered instance.
     * Used as fallback when calling get() without a name.
     *
     * @var string
     */
    private string $current = '';

    /**
     * Constructor is private to enforce singleton behavior.
     */
    private function __construct() {}

    /**
     * Returns the globally shared Singleton instance.
     * Use this to store and retrieve global objects.
     *
     * @return self The root singleton instance.
     */
    public static function root(): self
    {
        return self::$instance ??= new self();
    }

    /**
     * Creates a new isolated Singleton instance (not shared globally).
     * Useful when you need a scoped container for testing or temporary logic.
     *
     * @return self A fresh local singleton instance.
     */
    public static function local(): self
    {
        return new self();
    }

    /**
     * Stores an object returned by the provided factory, optionally under a custom name.
     * If no name is given, the object's class name is used.
     *
     * @param callable $factory A callable that returns an object to store.
     * @param string $name Optional identifier for the instance.
     * @param bool $force If true, overwrites any existing instance with the same name.
     *
     * @return self Fluent interface for chaining.
     *
     * @throws \InvalidArgumentException If the factory does not return an object,
     *                                   or if an instance with the same name already exists (unless $force is true).
     */
    public function remember(callable $factory, string $name = '', bool $force = false): self
    {
        $object = $factory();

        if (!is_object($object)) {
            throw new \InvalidArgumentException("Factory must return an object.");
        }

        $storedName = $name ?: get_class($object);
        $hash = $this->hashName($storedName);

        if (!$force && isset($this->instances[$hash])) {
            throw new \InvalidArgumentException("Instance with name '{$storedName}' already exists.");
        }

        $this->instances[$hash] = $object;
        $this->current = $storedName;

        return $this;
    }

    /**
     * Retrieves a previously stored instance by its name.
     * If no name is given, it attempts to return the last remembered instance.
     *
     * @param string $name Optional name of the instance to retrieve.
     *
     * @return object The stored object instance.
     *
     * @throws \InvalidArgumentException If no name is provided and no fallback is set,
     *                                   or if the instance does not exist.
     */
    public function make(string $name = ''): ?object
    {
        $storedName = $name ?: $this->current;

        if (empty($storedName)) {
            throw new \InvalidArgumentException("No instance name provided.");
        }

        $hash = $this->hashName($storedName);

        if (!isset($this->instances[$hash])) {
            return null;
        }

        $this->current = '';

        return $this->instances[$hash];
    }

    /**
     * Generates a unique hash based on the provided name.
     * Used internally to safely map instance names.
     *
     * @param string $name Name or identifier of the instance.
     *
     * @return string SHA-256 hash of the name.
     */
    private function hashName(string $name): string
    {
        return hash('sha256', $name);
    }
}
