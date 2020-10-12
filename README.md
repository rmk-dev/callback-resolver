# terry/callback-resolver

Simple callback resolver from any callback form (array, string, etc.). It supports also extracting a service from service container.

# Examples

```php
<?php
// Prepare some callbacks in different forms:

function my_awesome_function() {
    // do some awesome stuff ...
}

$fnCallback = function() {
    // do something cool here...
};

class MyGreatClass {
    public function myMethod()
    {
        // do other cool stuff ...
    }
    
    public function __invoke()
    {
        // Call me when you want to use the object as a function...
    }
};

$object = new MyGreatClass();

$stringCallback = 'my_awesome_function';
$arrayCallback = [$objCallback, 'myMethod'];

// A service container contains a service with name 'MyGreatService' and its value is $object
/** @var Psr\Container\ContainerInterface $serviceContainer */
$serviceContainer = MyServiceContainerFactory::create();

$resolver = new Terry\CallbackResolver\CallbackResolver($serviceContainer);

$resolver->resolve($stringCallback); // Will return a closure for 'my_awesome_function'
$resolver->resolve($arrayCallback); // Will return a closure for 'myMethod' from $object
$resolver->resolve(['MyGreatService']); // Will return the $object because it is callable (contains __invoke() method)
$resolver->resolve(['MyGreatService', 'myMethod']); // Will return a closure to 'myMethod' from $object
$resolver->resolve(MyGreatClass::class); // Can also resolve a callback via class name if it is callable (contains __invoke() method)
$resolver->resolve([MyGreatClass::class, 'myMethod']); //// Will return a closure to 'myMethod' from new MyGreatClass instance
```