<?php

namespace spec\BenConstable\Lock\Exception;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LockExceptionSpec extends ObjectBehavior
{
    function it_should_be_an_exception()
    {
        $this->shouldHaveType('Exception');
    }
}
