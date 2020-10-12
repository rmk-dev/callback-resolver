<?php

namespace Terry\CallbackResolverTests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Terry\CallbackResolver\CallbackResolver;
use Terry\CallbackResolver\ResolverException;

class TestClass {
    public function process(): int
    {
        return 1;
    }
    public function __invoke()
    {
        return $this->process();
    }
}

class CallbackResolverTest extends TestCase
{

    protected $resolver;

    protected $container;

    protected $callbacks;

    protected function setUp(): void
    {
        $callbacks = [
            'test' => new TestClass(),
        ];
        $this->container = $this->createStub(ContainerInterface::class);
        $this->container->method('has')->willReturnCallback(static function ($arg1) use ($callbacks) {
            return array_key_exists($arg1, $callbacks);
        });
        $this->container->method('get')->willReturnCallback(static function ($arg1) use ($callbacks) {
            return $callbacks[$arg1];
        });
        $this->resolver = new CallbackResolver($this->container);
    }

    public function testGettersSetters()
    {
        $this->assertSame($this->container, $this->resolver->getContainer());
    }

    public function testResolveCallback()
    {
        $test = $this->resolver->resolve(['test', 'process']);
        $this->assertIsArray($test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());

        $test2 = $this->resolver->resolve([TestClass::class, 'process']);
        $this->assertIsArray($test2);
        $this->assertIsCallable($test2);
        $this->assertEquals(1, $test2());

        $testCallable = $this->resolver->resolve(static function() { return 2; });
        $this->assertIsCallable($testCallable);
        $this->assertEquals(2, $testCallable());

        $test3 = $this->resolver->resolve([new TestClass()]);
        $this->assertIsObject($test3);
        $this->assertInstanceOf(TestClass::class, $test3);
        $this->assertIsCallable($test3);
        $this->assertEquals(1, $test3());

        $test4 = $this->resolver->resolve(new TestClass());
        $this->assertIsObject($test4);
        $this->assertInstanceOf(TestClass::class, $test3);
        $this->assertIsCallable($test4);
        $this->assertEquals(1, $test4());

        $test5 = $this->resolver->resolve(TestClass::class);
        $this->assertIsObject($test5);
        $this->assertInstanceOf(TestClass::class, $test5);
        $this->assertIsCallable($test5);
        $this->assertEquals(1, $test5());
    }

    public function testInvalidArgumentException()
    {
        $this->expectException(ResolverException::class);
        $this->expectExceptionCode(ResolverException::INVALID_PARAMETER_TYPE);
        $this->resolver->resolve([]);
    }

    public function testInvalidArrayException()
    {
        $this->expectException(ResolverException::class);
        $this->expectExceptionCode(ResolverException::INVALID_ARRAY_ELEMENTS);
        $this->resolver->resolve([[TestClass::class], 1]);
    }

    public function testInvalidServiceException()
    {
        $this->expectException(ResolverException::class);
        $this->expectExceptionCode(ResolverException::INVALID_OBJECT_ELEMENT);
        $this->resolver->resolve(['stdClassClass', 'a']);
    }
    public function testInvalidMethodException()
    {
        $this->expectException(ResolverException::class);
        $this->expectExceptionCode(ResolverException::INVALID_METHOD_ELEMENT);
        $this->resolver->resolve([TestClass::class, 'unknownMethod']);
    }
}