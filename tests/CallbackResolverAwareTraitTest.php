<?php

namespace Terry\CallbackResolverTests;

use PHPUnit\Framework\TestCase;
use Terry\CallbackResolver\CallbackResolver;
use Terry\CallbackResolver\CallbackResolverAwareTrait;

class CallbackResolverAwareTraitTest extends TestCase
{

    public function testGettersSetters()
    {
        $traitMock = $this->getMockForTrait(CallbackResolverAwareTrait::class);
        $resolverMock = $this->createMock(CallbackResolver::class);
        $this->assertSame($traitMock, $traitMock->setCallbackResolver($resolverMock));
        $this->assertSame($resolverMock, $traitMock->getCallbackResolver());
    }
}