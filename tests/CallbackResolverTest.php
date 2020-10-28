<?php

namespace Rmk\CallbackResolverTests;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Rmk\CallbackResolver\CallbackResolver;
use Rmk\CallbackResolver\ResolverException;

class TestClass {
    public function process(): int
    {
        return 1;
    }
    public function __invoke()
    {
        return $this->process();
    }
    public static function staticMethod(): int
    {
        return 123;
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

    public function testResolveArrayWithString()
    {
        $test = $this->resolver->resolve(['test', 'process']);
        $this->assertIsArray($test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveArrayWithClassname()
    {
        $test = $this->resolver->resolve([TestClass::class, 'process']);
        $this->assertIsArray($test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveStaticFunction()
    {
        $testCallable = $this->resolver->resolve(static function () {
            return 2;
        });
        $this->assertIsCallable($testCallable);
        $this->assertEquals(2, $testCallable());
    }

    public function testResolveArrayWithObject()
    {
        $test = $this->resolver->resolve([new TestClass()]);
        $this->assertIsObject($test);
        $this->assertInstanceOf(TestClass::class, $test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveCallbaleObject()
    {
        $test = $this->resolver->resolve(new TestClass());
        $this->assertIsObject($test);
        $this->assertInstanceOf(TestClass::class, $test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveStringWithClassname()
    {
        $test = $this->resolver->resolve(TestClass::class);
        $this->assertIsObject($test);
        $this->assertInstanceOf(TestClass::class, $test);
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveStringWithScopeOperator()
    {
        $test = $this->resolver->resolve(TestClass::class . '::staticMethod');
        $this->assertIsCallable($test);
        $this->assertEquals(123, $test());
    }

    public function testResolveStringWithStaticMethocCallNotation()
    {
        $test = $this->resolver->resolve(TestClass::class . '::staticMethod()');
        $this->assertIsCallable($test);
        $this->assertEquals(123, $test());
    }

    public function testResolveStringWithObjectCallNotation()
    {
        $test = $this->resolver->resolve(TestClass::class . '::process()');
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }
    public function testResolveStringWithAtNotation()
    {
        $test = $this->resolver->resolve(TestClass::class . '@process()');
        $this->assertIsCallable($test);
        $this->assertEquals(1, $test());
    }

    public function testResolveStringWithFunctionName()
    {
        $funcName = 'strlen';
        $this->assertTrue(function_exists($funcName));
        $test = $this->resolver->resolve($funcName);
        $this->assertIsCallable($test);
        $this->assertEquals(4, $test('test'));
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