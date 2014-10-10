<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

use Eloquent\Phony\Phpunit as a;
use Eloquent\Phony\Phpunit\Phony;

class FunctionalTest extends PHPUnit_Framework_TestCase
{
    public function testSpy()
    {
        $spy = Phony::spy();
        $spy('a', 'b', 'c');
        $spy(111);

        $spy->twice()->called();
        $spy->calledWith('a', 'b', 'c');
        $spy->calledWith('a', 'b');
        $spy->calledWith('a');
        $spy->calledWith();
        $spy->calledWith(111);
        $spy->calledWith($this->identicalTo('a'), $this->anything());
        $spy->callAt(0)->calledWith('a', 'b', 'c');
        $spy->callAt(1)->calledWith(111);
    }

    public function testSpyFunction()
    {
        $spy = a\spy();
        $spy('a', 'b', 'c');
        $spy(111);

        $spy->twice()->called();
        $spy->calledWith('a', 'b', 'c');
        $spy->calledWith('a', 'b');
        $spy->calledWith('a');
        $spy->calledWith();
        $spy->calledWith(111);
        $spy->calledWith($this->identicalTo('a'), $this->anything());
        $spy->callAt(0)->calledWith('a', 'b', 'c');
        $spy->callAt(1)->calledWith(111);
    }

    public function testStub()
    {
        $stub = Phony::stub()
            ->returns('x')
            ->with(111)->returns('y');

        $this->assertSame('x', $stub('a', 'b', 'c'));
        $this->assertSame('y', $stub(111));
        $stub->calledWith('a', 'b', 'c');
        $stub->calledWith('a', 'b');
        $stub->calledWith('a');
        $stub->calledWith();
        $stub->calledWith(111);
        $stub->calledWith($this->identicalTo('a'), $this->anything());
        $stub->callAt(0)->calledWith('a', 'b', 'c');
        $stub->callAt(1)->calledWith(111);
        $stub->returned('x');
        $stub->returned('y');
    }

    public function testStubFunction()
    {
        $stub = a\stub()
            ->returns('x')
            ->with(111)->returns('y');

        $this->assertSame('x', $stub('a', 'b', 'c'));
        $this->assertSame('y', $stub(111));
        $stub->calledWith('a', 'b', 'c');
        $stub->calledWith('a', 'b');
        $stub->calledWith('a');
        $stub->calledWith();
        $stub->calledWith(111);
        $stub->calledWith($this->identicalTo('a'), $this->anything());
        $stub->callAt(0)->calledWith('a', 'b', 'c');
        $stub->callAt(1)->calledWith(111);
        $stub->returned('x');
        $stub->returned('y');
    }
}
