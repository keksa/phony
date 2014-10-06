<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Call\Factory;

use Eloquent\Phony\Call\Call;
use Eloquent\Phony\Call\Event\GeneratedEvent;
use Eloquent\Phony\Invocation\Invoker;
use Eloquent\Phony\Test\TestCallEventFactory;
use PHPUnit_Framework_TestCase;
use RuntimeException;

/**
 * @covers \Eloquent\Phony\Call\Factory\CallFactory
 */
class CallFactoryWithGeneratorsTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->eventFactory = new TestCallEventFactory();
        $this->invoker = new Invoker();
        $this->subject = new CallFactory($this->eventFactory, $this->invoker);

        $this->exception = new RuntimeException('You done goofed.');
    }

    public function testCreateGeneratedEvent()
    {
        $generatorFactory = eval('return function () { return; yield null; };');
        $generator = call_user_func($generatorFactory);
        $expected = new GeneratedEvent(0, 0.0, $generator);
        $actual = $this->eventFactory->createGenerated($generator);

        $this->assertEquals($expected, $actual);
    }

    public function testCreateWithGeneratorEvents()
    {
        $calledEvent = $this->eventFactory->createCalled();
        $generatedEvent = $this->eventFactory->createGenerated();
        $generatorEvents = array($this->eventFactory->createSent());
        $expected = new Call($calledEvent, $generatedEvent, $generatorEvents);
        $actual = $this->subject->create($calledEvent, $generatedEvent, $generatorEvents);

        $this->assertEquals($expected, $actual);
    }
}
