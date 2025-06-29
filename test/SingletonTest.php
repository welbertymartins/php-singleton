<?php declare(strict_types=1);

namespace Test\WelbertyMartins\Singleton;

use PHPUnit\Framework\TestCase;
use WelbertyMartins\Singleton\Singleton;

final class SingletonTest extends TestCase
{
    public function testRootReturnsSameInstance(): void
    {
        $a = Singleton::root();
        $b = Singleton::root();

        $this->assertSame($a, $b, 'Singleton::root() should always return the same instance.');
    }

    public function testLocalReturnsDifferentInstances(): void
    {
        $a = Singleton::local();
        $b = Singleton::local();

        $this->assertNotSame($a, $b, 'Singleton::local() should return a new instance every time.');
    }

    public function testRememberStoresInstance(): void
    {
        $singleton = Singleton::local();
        $obj = new \stdClass();
        $singleton->remember(fn() => $obj, 'myObject');

        $retrieved = $singleton->make('myObject');
        $this->assertSame($obj, $retrieved, 'Stored and retrieved instance should be the same.');
    }

    public function testRememberUsesClassNameByDefault(): void
    {
        $singleton = Singleton::local();
        $obj = new \DateTime();
        $singleton->remember(fn() => $obj);

        $retrieved = $singleton->make(\DateTime::class);
        $this->assertSame($obj, $retrieved, 'Should use class name as default key.');
    }

    public function testRememberThrowsIfNameExists(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $singleton = Singleton::local();
        $singleton->remember(fn() => new \stdClass(), 'dup');
        $singleton->remember(fn() => new \stdClass(), 'dup');
    }

    public function testRememberAllowsForceOverride(): void
    {
        $singleton = Singleton::local();
        $first = new \stdClass();
        $second = new \stdClass();

        $singleton->remember(fn() => $first, 'key');
        $singleton->remember(fn() => $second, 'key', force: true);

        $retrieved = $singleton->make('key');
        $this->assertSame($second, $retrieved, 'Force should override existing instance.');
    }

    public function testRememberRejectsNonObject(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $singleton = Singleton::local();
        $singleton->remember(fn() => 'not an object');
    }

    public function testMakeReturnsNullIfNotExists(): void
    {
        $singleton = Singleton::local();
        $this->assertNull($singleton->make('missing'), 'Should return null if instance not found.');
    }

    public function testMakeThrowsIfNoFallbackSet(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $singleton = Singleton::local();
        $singleton->make(); // No fallback set
    }

    public function testMakeUsesFallbackName(): void
    {
        $singleton = Singleton::local();
        $obj = new \stdClass();

        $singleton->remember(fn() => $obj, 'fallback');
        $retrieved = $singleton->make(); // no name provided

        $this->assertSame($obj, $retrieved);
    }
}
