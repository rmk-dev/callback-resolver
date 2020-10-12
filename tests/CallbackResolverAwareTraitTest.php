<?php

namespace Rmk\CallbackResolverTests;

use PHPUnit\Framework\TestCase;
use Rmk\CallbackResolver\CallbackResolver;
use Rmk\CallbackResolver\CallbackResolverAwareTrait;

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